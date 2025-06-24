<?php

namespace App\Http\Controllers\Api;

use App\Models\Tool;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\ToolAvailability;
use App\Models\ToolImage;
use Illuminate\Support\Facades\Validator;

class ToolController extends Controller
{
    use ApiResponse;
    
    public function addTool(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'voltage' => 'required|string|max:255',
            'power_source' => 'required|string|max:255',
            'price' => 'required|string|max:255',
            'deposit' => 'required|string|max:255',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'description' => 'required|string|max:50000',
            'recommended_uses' => 'required|string|max:255',
            'day.*'       => 'required|string|max:255',
            'start_time'  => 'required|string|max:255',
            'end_time'    => 'required|string|max:255', 
        ]);

        if($validator->fails()){
            return $this->error($validator->errors(),'Validation Error', 422);
        }

        $user = auth()->user();

        if(!$user){
            return $this->error([], 'User not found', 404);
        }

        try{
            /**
             * Begin transaction
            */
            DB::beginTransaction();

            $data = Tool::create([
                'user_id' => $user->id,
                'name' => $request->name,
                'brand' => $request->brand,
                'voltage' => $request->voltage,
                'power_source' => $request->power_source,
                'price' => $request->price,
                'deposit' => $request->deposit,
                'description' => $request->description,
                'recommended_uses' => $request->recommended_uses,
            ]);
            
            /**
             * Handle images
             * @var $image
             * @var $imageName
             */
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $imageName = uploadImage($image, 'tools/images');
                    ToolImage::create([
                        'tool_id' => $data->id,
                        'image'  => $imageName,
                    ]);
                }
            }

            /**
             * Handle availability
             */
            if ($request->has('day')) {
                foreach ($request->day as $day) {
                    ToolAvailability::create([
                        'tool_id' => $data->id,
                        'day'       => $day,
                        'start_time' => $request->start_time,
                        'end_time'   => $request->end_time,
                    ]);
                }
            }

            DB::commit();

            $data->load('images', 'availabilities');
            return $this->success($data, 'Tool added successfully', 200);

        }catch(\Exception $e){
            DB::rollBack();
            return $this->error([], $e->getMessage(), 500);
        }
    }
}
