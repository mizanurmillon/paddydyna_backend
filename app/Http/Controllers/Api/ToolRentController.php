<?php

namespace App\Http\Controllers\Api;

use App\Models\ToolBooking;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\UserNotification;

class ToolRentController extends Controller
{

    use ApiResponse;
    
    public function rentRequest(Request $request)
    {
        $user = auth()->user();

        $query = ToolBooking::with('user:id,name,avatar')
        ->whereHas('tool', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        });

        if($request->status == 'completed') {
            $query->where('status', $request->status);
        }

        $data = $query->get();

        if (count($data) == 0) {
            return $this->error([], 'No Tool Booking Found', 400);
        }

        return $this->success($data, 'Tool Booking List', 200);
    }

    public function rentRequestAccept($id)
    {
        $user = auth()->user();

        if(!$user) {
            return $this->error([], 'User not found', 404);
        }
        
        $data = ToolBooking::where('id', $id)->first();

        if($data->status == 'cancelled') {
            return $this->error([], 'Tool Booking already cancelled', 400);
        }

        if (!$data) {
            return $this->error([], 'Tool Booking not found', 404);
        }

        $data->status = 'confirmed';
        $data->save();

        $data->user->notify(new UserNotification(
            subject: 'Tool Booking Accepted',
            message: 'Your tool booking has been accepted',
            type: 'tool',
            channels: ['database'],
        ));

        return $this->success($data, 'Tool Booking Accepted', 200);
    }

    public function rentRequestCancel($id)
    {
        $user = auth()->user();

        if(!$user) {
            return $this->error([], 'User not found', 404);
        }
        
        $data = ToolBooking::where('id', $id)->first();

        if($data->status == 'confirmed') {
            return $this->error([], 'Tool Booking already confirmed', 400);
        }

        if($data->status == 'cancelled') {
            return $this->error([], 'Tool Booking already cancelled', 400);
        }

        if (!$data) {
            return $this->error([], 'Tool Booking not found', 404);
        }

        $data->status = 'cancelled';
        $data->save();

        $data->user->notify(new UserNotification(
            subject: 'Tool Booking Cancelled',
            message: 'Your tool booking has been cancelled',
            type: 'tool',
            channels: ['database'],
        ));

        return $this->success($data, 'Tool Booking Cancelled', 200);
    }
}
