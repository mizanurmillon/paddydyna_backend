<?php

namespace App\Http\Controllers\Api;

use App\Models\Booking;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MyBookingController extends Controller
{
    use ApiResponse;
    
    public function myBooking() {
        
        $user = auth()->user();

        $data = Booking::with('craftsperson:id,name,role,avatar','address')->where('user_id', $user->id)->get();

        if(!$data) {
            return $this->error([],'Booking not found', 404);
        }

        return $this->success($data, 'Booking found', 200);
        
    }

    public function myBookingDetails($id) {
        
        $user = auth()->user();

        $data = Booking::with('craftsperson:id,name,role,avatar','address')->where('user_id', $user->id)->where('id', $id)->first();

        if(!$data) {
            return $this->error([],'Booking not found', 404);
        }

        return $this->success($data, 'Booking found', 200);
        
    }
}
