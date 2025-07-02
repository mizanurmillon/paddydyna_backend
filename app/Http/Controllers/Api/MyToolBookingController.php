<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ToolBooking;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class MyToolBookingController extends Controller
{
    use ApiResponse;
    public function myToolBooking()
    {
        $user = auth()->user();

        $data = ToolBooking::with(['address','tool:id,user_id,name,price,deposit','tool.images'])->where('user_id', $user->id)->get();

        if(!$data) {
            return $this->error([],'Tool booking not found', 404);
        }

        return $this->success($data, 'Tool booking found', 200);
    }

    public function myToolBookingDetails($id)
    {
        $user = auth()->user();

        $data = ToolBooking::with(['address','tool:id,user_id,name,price,deposit','tool.images'])->where('user_id', $user->id)->where('id', $id)->first();

        if(!$data) {
            return $this->error([],'Tool booking not found', 404);
        }

        return $this->success($data, 'Tool booking found', 200);
    }
}
