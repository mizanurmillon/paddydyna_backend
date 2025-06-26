<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class GetCraftspersonController extends Controller
{
    use ApiResponse;

    public function getCraftsperson(Request $request)
    {
        $user = auth()->user();

        // dd($user->addresses);

        $query = User::where('role', 'craftsperson')
            ->with('addresses', 'craftsperson', 'craftsperson.category', 'craftsperson.availability', 'craftsperson.images')
            ->withCount('giver_reviews')
            ->withAvg('giver_reviews', 'rating');

        if ($user->addresses->isNotEmpty() && $request->radius) {
            $address = $user->addresses->first();

            if ($address->latitude && $address->longitude) {
                $query->whereHas('addresses', function ($q) use ($address, $request) {
                    $latitude  = $address->latitude;
                    $longitude = $address->longitude;
                    $radius    = $request->radius;

                    $haversine = "(6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude))))";

                    $q->select('*')
                        ->selectRaw("$haversine AS distance", [$latitude, $longitude, $latitude])
                        ->having('distance', '<', $radius);
                });
            }
        }

        if ($request->has('category_id')) {
            $query->whereHas('craftsperson', function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhereHas('craftsperson.category', function ($q2) use ($request) {
                        $q2->where('name', 'like', '%' . $request->search . '%');
                    });
            });
        }
        if($request->has('avg_review'))
        {
            $query->whereHas('giver_reviews', function ($q) use ($request) {
                $q->where('rating', '>=', $request->avg_review);
            });
        }
        $data = $query->get();

        if (! $data) {
            return $this->error([], 'Craftsperson Not Found', 404);
        }

        return $this->success($data, 'Craftsperson Found', 200);
    }

    public function craftspersonDetails($id)
    {
        $data = User::where('id', $id)->where('role', 'craftsperson')
            ->with('addresses', 'craftsperson', 'craftsperson.category', 'craftsperson.availability', 'craftsperson.images','giver_reviews.customer:id,name,avatar')
            ->withCount('giver_reviews')
            ->withAvg('giver_reviews', 'rating')
            ->first();

        if (! $data) {
            return $this->error([], 'Craftsperson Not Found', 404);
        }

        return $this->success($data, 'Craftsperson Found', 200);
    }

}
