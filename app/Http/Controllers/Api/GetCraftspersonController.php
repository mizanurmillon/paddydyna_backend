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

        $latitude  = $request->query('latitude');
        $longitude = $request->query('longitude');
        $radius    = $request->query('radius', 20);

        $user = auth()->user();

        $query = User::where('role', 'craftsperson')
            ->with([
                'addresses',
                'craftsperson',
                'craftsperson.category',
                'craftsperson.availability',
                'craftsperson.images',
            ])
            ->withCount('giver_reviews')
            ->withAvg('giver_reviews', 'rating');

        if ($latitude && $longitude) {
            $query->whereHas('addresses', function ($q) use ($latitude, $longitude, $radius) {
                $haversine = "(6371 * acos(
                    cos(radians($latitude)) * cos(radians(latitude)) *
                    cos(radians(longitude) - radians($longitude)) +
                    sin(radians($latitude)) * sin(radians(latitude))
                ))";

                $q->whereRaw("$haversine < ?", [$radius]);
            });
        }

        // // Radius filter based on user's saved address if provided
        if ($user->addresses->isNotEmpty() && $request->filled('radius')) {
            $address = $user->addresses->first();

            if ($address->latitude && $address->longitude) {
                $userLat  = $address->latitude;
                $userLong = $address->longitude;
                $radius   = $request->radius;

                $query->whereHas('addresses', function ($q) use ($userLat, $userLong, $radius) {
                    $haversine = "(6371 * acos(
                    cos(radians(?)) * cos(radians(latitude)) *
                    cos(radians(longitude) - radians(?)) +
                    sin(radians(?)) * sin(radians(latitude))
                ))";

                    $q->select('*')
                        ->selectRaw("$haversine AS distance", [$userLat, $userLong, $userLat])
                        ->having('distance', '<', $radius);
                });
            }
        }

        // Category filter
        if ($request->filled('category_id')) {
            $query->whereHas('craftsperson', function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        // Search filter
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhereHas('craftsperson.category', function ($q2) use ($request) {
                        $q2->where('name', 'like', '%' . $request->search . '%');
                    });
            });
        }

        // Avg review filter
        if ($request->filled('avg_review')) {
            $avgReview = floatval($request->avg_review);
            $query->having('giver_reviews_avg_rating', '>=', $avgReview);
        }

        // Price range filter
        if ($request->filled('price_range') && is_array($request->price_range)) {
            [$minPrice, $maxPrice] = $request->price_range;

            $query->whereHas('craftsperson', function ($q) use ($minPrice, $maxPrice) {
                $q->whereBetween('price', [floatval($minPrice), floatval($maxPrice)]);
            });
        }

        $data = $query->get();

        $data->transform(function ($item) {
            $item->giver_reviews_avg_rating = $item->giver_reviews_avg_rating ?? 0;
            return $item;
        });

        if ($data->isEmpty()) {
            return $this->error([], 'Craftsperson Not Found', 404);
        }

        return $this->success($data, 'Craftsperson Found', 200);
    }

    public function craftspersonDetails($id)
    {
        $data = User::where('id', $id)->where('role', 'craftsperson')
            ->with('addresses', 'craftsperson', 'craftsperson.category', 'craftsperson.availability', 'craftsperson.images', 'giver_reviews.customer:id,name,avatar')
            ->withCount('giver_reviews')
            ->withAvg('giver_reviews', 'rating')
            ->first();

        if (! $data) {
            return $this->error([], 'Craftsperson Not Found', 404);
        }

        return $this->success($data, 'Craftsperson Found', 200);
    }

}
