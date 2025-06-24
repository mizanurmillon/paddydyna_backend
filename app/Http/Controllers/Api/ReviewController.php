<?php
namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Review;
use App\Models\Booking;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    use ApiResponse;
    
    /**
     * Review Add function
     * Customer and Craftsperson
     * Craftsperson and Customer
     */

    public function addReview(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'required|numeric|min:1|max:5',
            'title' => 'required|string|max:255',
            'review' => 'required|string|max:50000',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), 'Validation Error', 422);
        }

        $user = auth()->user();

        if (!$user) {
            return $this->error([], 'User not found', 404);
        }

        $booking = Booking::where('id', $id)->first();

        if(!$booking) {
            return $this->error([], 'Booking not found', 404);
        }

        if($user->role == "craftsperson") {
            $review = Review::create([
                'booking_id' => $id,
                'user_id' => $user->id,
                'giver_id' => $booking->user_id,
                'rating' => $request->rating,
                'title' => $request->title,
                'review' => $request->review,
            ]);
        }else{
            $review = Review::create([
                'booking_id' => $id,
                'user_id' => $user->id,
                'giver_id' => $booking->craftsperson_id,
                'rating' => $request->rating,
                'title' => $request->title,
                'review' => $request->review,
            ]);
        }

        if(!$review) {
            return $this->error([], 'Review not added', 400);
        }

        return $this->success($review, 'Review added successfully', 201);
       
    }

    /**
     * Review Get function
     * Customer and Craftsperson
     * Craftsperson and Customer
     */

    public function getReview(Request $request)
    {
        $user = User::where('id', auth()->user()->id)->select('id')->withCount('giver_reviews')->withAvg('giver_reviews', 'rating')->first();

        if(!$user) {
            return $this->error([], 'User not found', 404);
        }

        $query = Review::with('customer:id,name,avatar')->where('giver_id', $user->id);

        if($request->params == "new") {
          $query->latest(); 
        }
        if($request->params == "previous") {
          $query->whereDate('created_at', '<', now()); 
        }
        $data = $query->get();

        if($data->isEmpty()) {
            return $this->error([], 'Review not found', 404);
        }
        
        $data = [
            'data' => $user,
            'review' => $data
        ];
        return $this->success($data, 'Review fetched successfully', 200);
    }
}
