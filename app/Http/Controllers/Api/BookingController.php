<?php
namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Booking;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\UserNotification;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    use ApiResponse;

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

        // dd($craftsperson->craftsperson->availability);

        if (!$craftsperson->isAvailableForBooking($request->day, $request->start_time, $request->end_time)) {
            return $this->error([], 'This craftsman is not available for this time slot', 400);
        }

        $booking = new Booking();

        // if($booking->overlaps($request->start_time, $request->end_time, $request->day)) {
        //     return $this->error([], 'This craftsman this time slot is already booked', 400);
        // }

        $data = Booking::create([
            'user_id'             => $user->id,
            'craftsperson_id'     => $id,
            'service_type'        => $request->service_type,
            'service_description' => $request->service_description,
            'service_fee'         => $request->service_fee,
            'platform_fee'        => $request->platform_fee,
            'total_amount'        => $request->total_amount,
            'day'                 => $request->day,
            'start_time'          => $request->start_time,
            'end_time'            => $request->end_time,
            'address_id'          => $request->address_id,
            'agree_to_terms'      => $request->agree_to_terms,
        ]);

        if(!$data) {
            return $this->error([], 'Something went wrong', 500);
        }

        $data->craftsperson->notify(new UserNotification(
            subject: 'Booking Request',
            message: 'You have a new booking request',
            type: 'booking',
            channels: ['database'],
        ));

        return $this->success($data, 'Booking request sent successfully', 200);
    }

    public function rescheduleBooking(Request $request, $id) {

        $validator = Validator::make($request->all(), [
            'day'                 => 'required|string|max:255',
            'start_time'          => 'required|string|max:255',
            'end_time'            => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), 'Validation Error', 422);
        }

        $user = auth()->user();

        if ($user->role == 'craftsperson') {
            return $this->error([], 'You are not allowed to add booking', 400);
        }

        $booking = Booking::find($id);

        if (!$booking) {
            return $this->error([], 'Booking not found', 404);
        }

        $craftsperson = User::with('craftsperson', 'craftsperson.availability')->find($booking->craftsperson_id);

        if (!$craftsperson->isAvailableForBooking($request->day, $request->start_time, $request->end_time)) {
            return $this->error([], 'This craftsman is not available for this time slot', 400);
        }

        if($booking->overlaps($request->start_time, $request->end_time, $request->day)) {
            return $this->error([], 'This craftsman this time slot is already booked', 400);
        }

        $booking->update([
            'day'                 => $request->day,
            'start_time'          => $request->start_time,
            'end_time'            => $request->end_time,
        ]);

        if(!$booking) {
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

    public function cancelBooking($id) {

        $user = auth()->user();

        if ($user->role == 'craftsperson') {
            return $this->error([], 'You are not allowed to add booking', 400);
        }

        $booking = Booking::find($id);

        if($booking->status == 'cancelled') {
            return $this->error([], 'Booking already cancelled', 400);
        }

        if (!$booking) {
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

    public function completedBooking($id) {

        $user = auth()->user();

        if ($user->role == 'craftsperson') {
            return $this->error([], 'You are not allowed to add booking', 400);
        }

        $booking = Booking::find($id);

        if($booking->status == "cancelled") {
            return $this->error([], 'Booking already cancelled', 400);
        }

        if($booking->status == 'completed') {
            return $this->error([], 'Booking already completed', 400);
        }

        if (!$booking) {
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
