<?php
namespace App\Http\Controllers\Api;

use App\Models\Tool;
use App\Models\ToolBooking;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\UserNotification;
use Illuminate\Support\Facades\Validator;

class ToolBookingController extends Controller
{
    use ApiResponse;

    public function toolBooking(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'           => 'nullable|string|max:255',
            'description'    => 'nullable|string|max:50000',
            'day'            => 'required|string|max:255',
            'start_time'     => 'required|string|max:255',
            'end_time'       => 'required|string|max:255',
            'address_id'     => 'required|integer|exists:addresses,id',
            'agree_to_terms' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), 'Validation Error', 422);
        }

        $user = auth()->user();

        if ($user->role == 'craftsperson') {
            return $this->error([], 'You are not allowed to add booking', 400);
        }

        $tool = Tool::with('availabilities')->find($id);
        // dd($tool->availabilities);

        if (!$tool->isToolAvailableForBooking($request->day, $request->start_time, $request->end_time)) {
            return $this->error([], 'This tool is not available for this time slot', 400);
        }

        $toolBooking = new ToolBooking();

        // if ($toolBooking->overlaps($request->start_time, $request->end_time, $request->day)) {
        //     return $this->error([], 'This tool this time slot is already booked', 400);
        // }

        $data = $toolBooking->create([
            'user_id'      => $user->id,
            'tool_id'      => $id,
            'name'         => $request->name,
            'description'  => $request->description,
            'rent_fee'  => $request->rent_fee,
            'deposit' => $request->deposit,
            'platform_fee' => $request->platform_fee,
            'total_amount' => $request->total_amount,
            'day'          => $request->day,
            'start_time'   => $request->start_time,
            'end_time'     => $request->end_time,
            'address_id'   => $request->address_id,
            'agree_to_terms' => $request->agree_to_terms
        ]);

        if(!$data) {
            return $this->error([], 'Something went wrong', 400);
        }
        $data->tool->user->notify(new UserNotification(
            subject: 'Tool Rent Request',
            message: 'You have a new tool booking request',
            type: 'tool',
            channels: ['database'],
        ));
        return $this->success($data, 'Tool Booking added successfully', 200);
    }
}
