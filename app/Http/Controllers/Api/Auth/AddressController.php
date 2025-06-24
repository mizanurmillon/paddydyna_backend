<?php

namespace App\Http\Controllers\Api\Auth;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Support\Facades\Validator;

class AddressController extends Controller
{

    use ApiResponse;
    
    public function addAddress(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'address' => 'required|string|max:500',
            'street' => 'nullable|string|max:225',
            'post_code' => 'nullable|string|max:225',
            'apartment' => 'nullable|string|max:225',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), "Validation Error", 422);
        }

        $user = auth()->user();

        if(!$user) {
            return $this->error([], 'User not found', 404);
        }

        $data = Address::create([
            'user_id' => $user->id,
            'type' => $request->type,
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'street' => $request->street,
            'post_code' => $request->post_code,
            'apartment' => $request->apartment
        ]);

        if(!$data) {
            return $this->error([], 'Something went wrong', 500);
        }

        return $this->success($data, 'Address added successfully.', 200);
    }

    public function updateAddress(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'address' => 'required|string|max:500',
            'street' => 'nullable|string|max:225',
            'post_code' => 'nullable|string|max:225',
            'apartment' => 'nullable|string|max:225',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), "Validation Error", 422);
        }

        $user = auth()->user();

        if(!$user) {
            return $this->error([], 'User not found', 404);
        }

        $data = Address::where('id', $id)->first();

        if(!$data) {
            return $this->error([], 'Address not found', 404);
        }

        $data->update([
            'user_id' => $user->id,
            'type' => $request->type,
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'street' => $request->street,
            'post_code' => $request->post_code,
            'apartment' => $request->apartment
        ]);

        if(!$data) {
            return $this->error([], 'Something went wrong', 500);
        }

        return $this->success($data, 'Address updated successfully.', 200);
    }

    public function deleteAddress($id)
    {
        $user = auth()->user();

        if(!$user) {
            return $this->error([], 'User not found', 404);
        }

        $data = Address::where('id', $id)->first();

        if(!$data) {
            return $this->error([], 'Address not found', 404);
        }

        $data->delete();

        return $this->success([], 'Address deleted successfully.', 200);
    }

    
}
