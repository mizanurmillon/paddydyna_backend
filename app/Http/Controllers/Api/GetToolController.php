<?php
namespace App\Http\Controllers\Api;

use App\Models\Tool;
use App\Models\User;
use App\Models\ToolBooking;
use App\Traits\ApiResponse;
use App\Http\Controllers\Controller;
use function Laravel\Prompts\select;

class GetToolController extends Controller
{

    use ApiResponse;

    public function getTool()
    {
        $user = User::with('addresses')->where('id', auth()->user()->id)->first();

        if (!$user) {
            return $this->error([], 'User Not Found', 404);
        }

        $bookedToolIds = ToolBooking::where('user_id', $user->id)->whereNot('status', 'completed')->whereNot('status', 'cancelled')->pluck('tool_id')->toArray();

        $stockedToolIds = ToolBooking::where('user_id', $user->id)->whereNot('status', 'completed')->whereNot('status', 'cancelled')->pluck('tool_id')->toArray();

        $data = Tool::with(['images','user:id,name,avatar','user.addresses:id,user_id,address,latitude,longitude'])->select('id', 'user_id', 'name', 'price', 'deposit')->withAvg('toolReviews', 'rating')->get()
            ->map(function ($tool) use ($user,$bookedToolIds,$stockedToolIds) {

                // Format average rating
                $tool->tool_reviews_avg_rating = number_format($tool->tool_reviews_avg_rating, 2);

                // Distance calculation
                $from = $user->addresses->first();  // assuming single address
                $to   = $tool->user->addresses->first();

                if ($from && $to) {
                    $tool->distance = calculateDistance(
                        $from->latitude,
                        $from->longitude,
                        $to->latitude,
                        $to->longitude
                    );
                } else {
                    $tool->distance_km = 0;
                }
                $tool->is_booked = in_array($tool->id, $bookedToolIds);
                $tool->is_stocked = in_array($tool->id, $stockedToolIds);
                return $tool;
            });

        if (! $data) {
            return $this->error([], 'Tools Not Found', 404);
        }

        return $this->success($data, 'Tools Found', 200);
    }

    

    public function toolDetails($id)
    {
        $data = Tool::with('images', 'availabilities', 'toolReviews')->withCount('toolReviews')->withAvg('toolReviews', 'rating')->find($id);

        if (! $data) {
            return $this->error([], 'Tool Not Found', 404);
        }

        return $this->success($data, 'Tool Found', 200);
    }
}
