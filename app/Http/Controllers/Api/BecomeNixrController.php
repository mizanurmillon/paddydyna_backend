<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Support\Str;
use App\Mail\BecomeNixrMail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class BecomeNixrController extends Controller
{
    use ApiResponse;

    public function addBecomeNixr(Request $request){

       $validator = Validator::make($request->all(), [
            'name'           => 'required|string|max:255',
            'email'    => 'required|string|email|unique:users,email',
            'role' => 'required|in:customer,craftsperson',
            'agree_to_terms' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), $validator->errors()->first(), 422);
        }

        $password = Str::random(8);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($password),
            'role' => $request->role,
            'agree_to_terms' => $request->agree_to_terms
        ]);

        $data = [
                'name' => $request->name,
                'email'      => $request->email,
                'password'   => $password,
            ];
        Mail::to($user->email)->send(new BecomeNixrMail($data));

        if($user){
            return $this->success($user, 'User added successfully.', 200);
        }

        return $this->error([], 'Something went wrong', 500);

    }
}
