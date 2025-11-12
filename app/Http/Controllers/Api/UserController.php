<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use ApiResponse;

    /**
     * Fetch Login User Data
     *
     * @return \Illuminate\Http\JsonResponse  JSON response with success or error.
     */

    public function userData()
    {

        $user = User::with('addresses')->where('id', auth()->user()->id)->first();

        if ($user->role == "craftsperson") {
            $user = User::with('addresses', 'craftsperson.category', 'craftsperson.availability', 'craftsperson.images')->where('id', auth()->user()->id)->first();
        }

        if (!$user) {
            return $this->error([], 'User Not Found', 404);
        }

        return $this->success($user, 'User data fetched successfully', 200);
    }

    /**
     * Update User Information
     *
     * @param  \Illuminate\Http\Request  $request  The HTTP request with the register query.
     * @return \Illuminate\Http\JsonResponse  JSON response with success or error.
     */

    public function userUpdate(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'avatar'  => 'nullable|image|mimes:jpeg,png,jpg,svg|max:5120',
            'name'    => 'required|string|max:255',
            'surname' => 'nullable|string|max:255',
            'email'   => 'nullable|string|email|max:255|unique:users,email,' . auth()->user()->id,
            'phone'   => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'driving_license_or_passport' => 'nullable|file|mimes:jpeg,png,jpg,svg,pdf,doc,docx|max:10240',
            'garda_vetting_certificate' => 'nullable|file|mimes:jpeg,png,jpg,svg,pdf,doc,docx|max:10240',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), "Validation Error", 422);
        }

        try {
            // Find the user by ID
            $user = auth()->user();

            // If user is not found, return an error response
            if (!$user) {
                return $this->error([], "User Not Found", 404);
            }

            if ($request->hasFile('avatar')) {
                $image     = $request->file('avatar');
                $imageName = uploadImage($image, 'User/Avatar');
            } else {
                $imageName = $user->avatar;
            }

            if ($request->hasFile('driving_license_or_passport')) {
                $image     = $request->file('driving_license_or_passport');
                $imageName2 = uploadImage($image, 'User/DrivingLicenseOrPassport');
            } else {
                $imageName2 = $user->driving_license_or_passport;
            }

            if($request->hasFile('garda_vetting_certificate')) {
                $image     = $request->file('garda_vetting_certificate');
                $imageName3 = uploadImage($image, 'User/GardaVettingCertificate');
            } else {
                $imageName3 = $user->garda_vetting_certificate;
            }
            

            // Update user details

            $user->userUpdate()->updateOrCreate(
                ['user_id' => $user->id], // condition
                [
                    'user_id' => $user->id,
                    'name' => $request->name,
                    'surname' => $request->surname,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'date_of_birth' => $request->date_of_birth,
                    'avatar' => $imageName,
                    'driving_license_or_passport' => $imageName2,
                    'garda_vetting_certificate' => $imageName3
                ]
            );

            $user->update_status = 'pending';

            $user->save();

            return $this->success($user, 'Your update request has been submitted for admin approval.', 200);
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), 500);
        }
    }

    /**
     * Logout the authenticated user's account
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse JSON response with success or error.
     */
    public function logoutUser()
    {

        try {
            JWTAuth::invalidate(JWTAuth::getToken());

            return $this->success([], 'Successfully logged out', 200);
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), 500);
        }
    }

    /**
     * Delete the authenticated user's account
     *
     * @return \Illuminate\Http\JsonResponse JSON response with success or error.
     */
    public function deleteUser()
    {
        try {
            // Get the authenticated user
            $user = auth()->user();

            // If user is not found, return an error response
            if (!$user) {
                return $this->error([], "User Not Found", 404);
            }

            // Delete the user's avatar if it exists
            if ($user->avatar) {
                $previousImagePath = public_path($user->avatar);
                if (file_exists($previousImagePath)) {
                    unlink($previousImagePath);
                }
            }

            // Delete the user
            $user->delete();

            return $this->success([], 'User deleted successfully', 200);
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), 500);
        }
    }

    public function changePassword(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string|min:8',
            'new_password'     => 'required|string|min:8',
            'confirm_password' => 'required|string|min:8|same:new_password',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), "Validation Error", 422);
        }

        try {
            $user = auth()->user();

            if (!$user) {
                return $this->error([], "User Not Found", 404);
            }

            if (!Hash::check($request->current_password, $user->password)) {
                return $this->error([], "Current password is incorrect", 422);
            }

            $user->password = Hash::make($request->new_password);
            $user->save();


            return $this->success([], 'Password changed successfully', 200);
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), 500);
        }
    }
}
