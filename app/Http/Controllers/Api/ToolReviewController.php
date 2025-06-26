<?php
namespace App\Http\Controllers\Api;

use App\Models\ToolReview;
use App\Models\ToolBooking;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ToolReviewController extends Controller
{
    use ApiResponse;

    public function addToolReview(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'required|numeric|min:1|max:5',
            'review' => 'required|string|max:50000',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), 'Validation Error', 422);
        }

        $user = auth()->user();

        if (! $user) {
            return $this->error([], 'User not found', 404);
        }

        $tool_booking = ToolBooking::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (! $tool_booking) {
            return $this->error([], 'Tool Booking not found or access denied', 404);
        }

        $review = ToolReview::create([
            'tool_booking_id' => $id,
            'user_id'         => $user->id,
            'tool_id'         => $tool_booking->tool_id,
            'rating'          => $request->rating,
            'review'          => $request->review,
        ]);

        if (! $review) {
            return $this->error([], 'Review not added', 400);
        }

        return $this->success($review, 'Review added successfully', 200);

    }
}
