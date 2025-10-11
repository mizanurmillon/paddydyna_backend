<?php
namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Mail\RegistationOtp;
use App\Models\Craftsperson;
use App\Models\EmailOtp;
use App\Models\User;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class RegisterController extends Controller
{

    use ApiResponse;

    /**
     * Send a Register (OTP) to the user via email.
     *
     * @param  \App\Models\User  $user
     * @return void
     */

    private function sendOtp($user)
    {
        $code = rand(1000, 9999);

        // Store verification code in the database
        $verification = EmailOtp::updateOrCreate(
            ['user_id' => $user->id],
            [
                'verification_code' => $code,
                'expires_at'        => Carbon::now()->addMinutes(15),
            ]
        );

        Mail::to($user->email)->send(new RegistationOtp($user, $code));
    }

    /**
     * Register User
     *
     * @param  \Illuminate\Http\Request  $request  The HTTP request with the register query.
     * @return \Illuminate\Http\JsonResponse  JSON response with success or error.
     */

    public function userRegister(Request $request)
    {
        $rules = [
            'name'           => 'required|string|max:255',
            'surname'        => 'required|string|max:255', // 'surname'
            'email'          => 'required|email|unique:users,email',
            'phone'          => 'required|string|max:20',
            'role'           => 'required|in:customer,craftsperson',
            'password'       => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
            'agree_to_terms' => 'required|boolean',
        ];

        if ($request->input('role') == 'craftsperson') {
            $rules['craftsperson_id']               = 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx|max:5120';
            $rules['police_verification_document'] = 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx|max:5120';
        }

        $messages = [
            'password.min' => 'The password must be at least 8 characters long.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if($request->file('craftsperson_id')) {
            $craftsperson_id     = $request->file('craftsperson_id');
            $craftspersonIdName = uploadImage($craftsperson_id, 'User/documents');
        }else{
            $craftspersonIdName = null;
        }

        if($request->file('police_verification_document')) {
            $police_verification_document     = $request->file('police_verification_document');
            $policeVerificationDocumentName = uploadImage($police_verification_document, 'User/documents');
        }else{
            $policeVerificationDocumentName = null;
        }

        try {
            // Find the user by ID
            $user                 = new User();
            $user->name           = $request->input('name');
            $user->surname        = $request->input('surname');
            $user->email          = $request->input('email');
            $user->phone          = $request->input('phone');
            $user->password       = Hash::make($request->input('password')); // Hash the password
            $user->role           = $request->input('role');
            $user->agree_to_terms = $request->input('agree_to_terms');

            if ($request->input('role') == 'craftsperson') {
                $user->status = 'pending';
            } else {
                $user->status = 'active';
            }

            $user->save();

            if ($request->input('role') == 'craftsperson') {
                Craftsperson::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'craftsperson_id' => $craftspersonIdName,
                        'police_verification_document' => $policeVerificationDocumentName
                    ]
                );
            }

            $this->sendOtp($user);

            $user->load('craftsperson');

            return $this->success($user, 'Verification email sent', 201);
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), 500);
        }
    }

    /**
     * Verify the OTP sent to the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function otpVerify(Request $request)
    {

        // Validate the request
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'otp'   => 'required|numeric|digits:4',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), "Validation Error", 422);
        }

        try {
            // Retrieve the user by email
            $user = User::where('email', $request->input('email'))->first();

            $verification = EmailOtp::where('user_id', $user->id)
                ->where('verification_code', $request->input('otp'))
                ->where('expires_at', '>', Carbon::now())
                ->first();

            if ($verification) {

                $user->email_verified_at = Carbon::now();
                $user->save();

                $verification->delete();

                $token = JWTAuth::fromUser($user);

                $user->setAttribute('token', $token);

                return $this->success($user, 'OTP verified successfully', 200);
            } else {

                return $this->error([], 'Invalid or expired OTP', 400);
            }
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), 500);
        }
    }

    /**
     * Resend an OTP to the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function otpResend(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors(), "Validation Error", 422);
        }

        try {
            // Retrieve the user by email
            $user = User::where('email', $request->input('email'))->first();

            $this->sendOtp($user);

            return $this->success($user, 'OTP has been sent successfully.', 200);
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), 500);
        }
    }
}
