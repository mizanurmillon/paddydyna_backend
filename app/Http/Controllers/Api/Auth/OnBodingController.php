<?php
namespace App\Http\Controllers\Api\Auth;

use App\Traits\ApiResponse;
use App\Models\Craftsperson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\CraftspersonAvailability;
use App\Models\CraftspersonServiceImage;
use Illuminate\Support\Facades\Validator;

class OnBodingController extends Controller
{
    use ApiResponse;

    /**
     * Function: onBoarding
     */

    public function onBoarding(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'price'       => 'required|string|max:255',
            'description' => 'required|string|max:50000',
            'images.*'    => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:51200',
            'day.*'       => 'required|string|max:255',
            'start_time'  => 'required|string|max:255',
            'end_time'    => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), "Validation Error", 422);
        }

        $user = auth()->user();

        if (! $user) {
            return $this->error([], 'User not found', 404);
        }

        try {
            /**
             * Begin transaction
             */
            DB::beginTransaction();

            /**
             * Create craftspeople
             */
            $data = Craftsperson::create([
                'user_id'     => $user->id,
                'category_id' => $request->category_id,
                'price'       => $request->price,
                'description' => $request->description,
            ]);

            /**
             * Handle images
             * @var $image
             * @var $imageName
             */
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $imageName = uploadImage($image, 'service/images');
                    CraftspersonServiceImage::create([
                        'craftspeople_id' => $data->id,
                        'image'           => $imageName,
                    ]);
                }
            }

            /**
             * Handle availability
             */
            if ($request->has('day')) {
                foreach ($request->day as $day) {
                    CraftspersonAvailability::create([
                        'craftspeople_id' => $data->id,
                        'day'             => $day,
                        'start_time'      => $request->start_time,
                        'end_time'        => $request->end_time,
                    ]);
                }
            }

            /**
             * Commit transaction
             */
            DB::commit();
            $data->load('category', 'images', 'availability');
            return $this->success($data, 'Onboarding successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error([], $e->getMessage(), 500);
        }

    }
}
