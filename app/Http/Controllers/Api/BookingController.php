<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\PaymentHistory;
use App\Models\SystemSetting;
use App\Models\User;
use App\Notifications\UserNotification;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class BookingController extends Controller
{
    use ApiResponse;

    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function addBooking(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'service_type'        => 'nullable|string|max:255',
            'service_description' => 'nullable|string|max:50000',
            'day'                 => 'required|string|max:255',
            'start_time'          => 'required|string|max:255',
            'end_time'            => 'required|string|max:255',
            'address_id'          => 'required|integer|exists:addresses,id',
            'agree_to_terms'      => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), 'Validation Error', 422);
        }

        $user = auth()->user();

        if ($user->role == 'craftsperson') {
            return $this->error([], 'You are not allowed to add booking', 400);
        }

        $craftsperson = User::with('craftsperson', 'craftsperson.availability')->find($id);

        if (! $craftsperson->isAvailableForBooking($request->day, $request->start_time, $request->end_time)) {
            return $this->error([], 'This craftsman is not available for this time slot', 400);
        }

        $booking = new Booking();

        // if($booking->overlaps($request->start_time, $request->end_time, $request->day)) {
        //     return $this->error([], 'This craftsman this time slot is already booked', 400);
        // }

        try {

            $amountInCents = (int) ($request->total_amount * 100);

            $platformFee = SystemSetting::find(1);

            $percentageToTake = $platformFee->platform_fee ?? 0; // e.g., 10
            $percentageToTake = min($percentageToTake, 100);     // Just to be safe

            $applicationFeeAmount = (int) ($amountInCents * ($percentageToTake / 100));

            $checkoutSession = Session::create([
                'payment_method_types' => ['card'],
                'line_items'           => [[
                    'price_data' => [
                        'currency'     => 'usd',
                        'unit_amount'  => $amountInCents,
                        'product_data' => [
                            'name' => $craftsperson->name,
                        ],
                    ],
                    'quantity'   => 1,
                ]],
                'customer_email'       => $user->email,
                'mode'                 => 'payment',
                'success_url'          => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url'           => route('checkout.cancel') . '?redirect_url=' . $request->get('cancel_redirect_url'),
                'payment_intent_data'  => [
                    'application_fee_amount' => $applicationFeeAmount,
                    'transfer_data'          => [
                        'destination' => $craftsperson->stripe_account_id, // Movement owner
                    ],
                ],
                'metadata'             => [
                    'user_id'              => $user->id,
                    'craftsperson_name'    => $craftsperson->name,
                    'craftsperson_id'      => $craftsperson->id,
                    'service_type'         => $request->service_type,
                    'service_description'  => $request->service_description,
                    'day'                  => $request->day,
                    'start_time'           => $request->start_time,
                    'end_time'             => $request->end_time,
                    'address_id'           => $request->address_id,
                    'agree_to_terms'       => $request->agree_to_terms,
                    'platform_fee'         => $platformFee->platform_fee,
                    'service_fee'          => $request->service_fee,
                    'amount'               => $request->total_amount,
                    'success_redirect_url' => $request->success_redirect_url,
                    'cancel_redirect_url'  => $request->cancel_redirect_url,
                ],
            ]);

            return $this->success($checkoutSession->url, 'Checkout session created successfully.', 201);
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), 500);
        }

    }

    public function checkoutSuccess(Request $request)
    {
        if (! $request->query('session_id')) {
            return $this->error([], 'Session ID not found.', 200);
        }

        DB::beginTransaction();
        try {
            $sessionId       = $request->query('session_id');
            $checkoutSession = \Stripe\Checkout\Session::retrieve($sessionId);
            $metadata        = $checkoutSession->metadata;
            $success_redirect_url = $metadata->success_redirect_url ?? null;
            $craftsperson_name    = $metadata->craftsperson_name ?? null;
            $user_id              = $metadata->user_id ?? null;
            $craftsperson_id      = $metadata->craftsperson_id ?? null;
            $service_type         = $metadata->service_type ?? null;
            $service_description  = $metadata->service_description ?? null;
            $day                  = $metadata->day ?? null;
            $start_time           = $metadata->start_time ?? null;
            $end_time             = $metadata->end_time ?? null;
            $address_id           = $metadata->address_id ?? null;
            $platformFee          = $metadata->platform_fee ?? null;
            $service_fee          = $metadata->service_fee ?? null;
            $amount               = $metadata->amount ?? null;
            $agree_to_terms       = $metadata->agree_to_terms ?? null;

            $user = User::find($user_id);

            if (! $user) {
                return $this->error([], 'User not found.', 200);
            }

            $booking = Booking::create([
                'user_id'              => $user_id,
                'craftsperson_id'      => $craftsperson_id,
                'service_type'         => $service_type,
                'service_description'  => $service_description,
                'day'                  => $day,
                'start_time'           => $start_time,
                'end_time'             => $end_time,
                'address_id'           => $address_id,
                'platform_fee'         => $platformFee,
                'service_fee'          => $service_fee,
                'total_amount'         => $amount,
                'agree_to_terms'      => $agree_to_terms,
            ]);

            if(! $booking) {
                return $this->error([], 'Booking not created.', 200);
            }

            $paymentHistory = PaymentHistory::create([
                'user_id'    => $user_id,
                'booking_id' => $booking->id,
                'amount'     => $amount,
            ]);
            $booking->craftsperson->notify(new UserNotification(
                subject: 'Booking request',
                message: 'Your booking has been requested',
                type: 'booking',
                channels: ['database'],
            ));
            DB::commit();
            return redirect($success_redirect_url);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error([], $e->getMessage(), 500);
        }
    }

    public function checkoutCancel(Request $request)
    {
        $sessionId = $request->query('session_id');

        if (! $sessionId) {
            return redirect($request->redirect_url ?? null);
        }

        $checkoutSession = \Stripe\Checkout\Session::retrieve($sessionId);
        $metadata        = $checkoutSession->metadata;

        $cancel_redirect_url = $metadata->cancel_redirect_url ?? null;

        return redirect($cancel_redirect_url);
    }

    public function rescheduleBooking(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'day'        => 'required|string|max:255',
            'start_time' => 'nullable|string|max:255',
            'end_time'   => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), 'Validation Error', 422);
        }

        $user = auth()->user();

        if ($user->role == 'craftsperson') {
            return $this->error([], 'You are not allowed to add booking', 400);
        }

        $booking = Booking::find($id);

        if (! $booking) {
            return $this->error([], 'Booking not found', 404);
        }

        $craftsperson = User::with('craftsperson', 'craftsperson.availability')->find($booking->craftsperson_id);

        if (! $craftsperson->isAvailableForBooking($request->day, $request->start_time, $request->end_time)) {
            return $this->error([], 'This craftsman is not available for this time slot', 400);
        }

        // if($booking->overlaps($request->start_time, $request->end_time, $request->day)) {
        //     return $this->error([], 'This craftsman this time slot is already booked', 400);
        // }

        $booking->update([
            'day'        => $request->day,
            'start_time' => $request->start_time,
            'end_time'   => $request->end_time,
        ]);

        if (! $booking) {
            return $this->error([], 'Something went wrong', 500);
        }

        $booking->craftsperson->notify(new UserNotification(
            subject: 'Booking Rescheduled',
            message: 'Your booking has been rescheduled',
            type: 'booking',
            channels: ['database'],
        ));

        return $this->success($booking, 'Booking request sent successfully', 200);
    }

    public function cancelBooking($id)
    {

        $user = auth()->user();

        if ($user->role == 'craftsperson') {
            return $this->error([], 'You are not allowed to add booking', 400);
        }

        $booking = Booking::find($id);

        if ($booking->status == 'cancelled') {
            return $this->error([], 'Booking already cancelled', 400);
        }

        if (! $booking) {
            return $this->error([], 'Booking not found', 404);
        }

        $booking->update([
            'status' => 'cancelled',
        ]);

        $booking->craftsperson->notify(new UserNotification(
            subject: 'Booking Cancelled',
            message: 'Your booking has been cancelled',
            type: 'booking',
            channels: ['database'],
        ));

        return $this->success($booking, 'Booking cancelled successfully', 200);

    }

    public function completedBooking($id)
    {

        $user = auth()->user();

        if ($user->role == 'craftsperson') {
            return $this->error([], 'You are not allowed to add booking', 400);
        }

        $booking = Booking::find($id);

        if ($booking->status == "cancelled") {
            return $this->error([], 'Booking already cancelled', 400);
        }

        if ($booking->status == 'completed') {
            return $this->error([], 'Booking already completed', 400);
        }

        if (! $booking) {
            return $this->error([], 'Booking not found', 404);
        }

        $booking->update([
            'status' => 'completed',
        ]);

        $booking->craftsperson->notify(new UserNotification(
            subject: 'Booking Completed',
            message: 'Your booking has been completed',
            type: 'booking',
            channels: ['database'],
        ));

        return $this->success($booking, 'Booking completed successfully', 200);

    }
}
