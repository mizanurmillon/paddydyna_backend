<?php

namespace App\Http\Controllers\Api;

use App\Models\Booking;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\UserNotification;

class JobRequestController extends Controller
{
    use ApiResponse;
    
    public function jobRequest(Request $request)
    {
        $user = auth()->user();

        if(!$user) {
            return $this->error([], 'User not found', 404);
        }

        $query = Booking::with('user:id,name,avatar','address')->where('craftsperson_id', $user->id);

        if($request->params == "job-request") {
            $query->where('status', 'pending');
        }

        if($request->params == "my-jobs") {
            $query->where('status', 'confirmed');
        }

        if($request->params == "completed") {
            $query->where('status', 'completed');
        }

        $data = $query->latest()->get();
        
        if(!$data) {
            return $this->error([],'Booking not found', 404);
        }

        return $this->success($data, 'Booking found', 200);
    }

    public function jobRequestAccept($id)
    {
        $user = auth()->user();

        if(!$user) {
            return $this->error([], 'User not found', 404);
        }

        $booking = Booking::find($id);

        if($booking->status == 'completed') {
            return $this->error([], 'Booking already completed', 400);
        }

        if(!$booking) {
            return $this->error([], 'Booking not found', 404);
        }

        $booking->status = 'confirmed';
        $booking->save();

        $booking->user->notify(new UserNotification(
            subject: 'Booking Accepted',
            message: 'Your booking has been accepted',
            type: 'booking',
            channels: ['database'],
        ));

        return $this->success($booking, 'Booking accepted', 200);
    }

    public function jobRequestCancel($id)
    {
        $user = auth()->user();

        if(!$user) {
            return $this->error([], 'User not found', 404);
        }

        $booking = Booking::find($id);

        if(!$booking) {
            return $this->error([], 'Booking not found', 404);
        }

        $booking->status = 'cancelled';
        $booking->save();

        $booking->user->notify(new UserNotification(
            subject: 'Booking Cancelled',
            message: 'Your booking has been cancelled',
            type: 'booking',
            channels: ['database'],
        ));

        return $this->success($booking, 'Booking cancelled', 200);
    }
}
