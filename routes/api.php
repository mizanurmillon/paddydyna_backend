<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\ConnectAccount;
use App\Http\Controllers\Api\ToolController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\GetToolController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ToolRentController;
use App\Http\Controllers\Api\MyBookingController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\JobRequestController;
use App\Http\Controllers\Api\SocialAuthController;
use App\Http\Controllers\Api\ToolReviewController;
use App\Http\Controllers\Api\ToolBookingController;
use App\Http\Controllers\Api\Auth\AddressController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\Auth\OnBodingController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\GetCraftspersonController;
use App\Http\Controllers\Api\HomePageController;
use App\Http\Controllers\Api\MyToolBookingController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


//Social Login
Route::post('/social-login', [SocialAuthController::class, 'socialLogin']);

//Register API
Route::controller(RegisterController::class)->prefix('users/register')->group(function () {
    // User Register
    Route::post('/', 'userRegister');

    // Verify OTP
    Route::post('/otp-verify', 'otpVerify');

    // Resend OTP
    Route::post('/otp-resend', 'otpResend');
});

//Login API
Route::controller(LoginController::class)->prefix('users/login')->group(function () {

    // User Login
    Route::post('/', 'userLogin');

    // Verify Email
    Route::post('/email-verify', 'emailVerify');

    // Resend OTP
    Route::post('/otp-resend', 'otpResend');

    // Verify OTP
    Route::post('/otp-verify', 'otpVerify');

    //Reset Password
    Route::post('/reset-password', 'resetPassword');
});

Route::group(['middleware' => ['jwt.verify']], function () {

    Route::controller(UserController::class)->prefix('users')->group(function () {
        Route::get('/data', 'userData');
        Route::post('/data/update', 'userUpdate');
        Route::post('/logout', 'logoutUser');
        Route::delete('/delete', 'deleteUser');
        Route::post('/change-password', 'changePassword');
    });

    Route::controller(AddressController::class)->group(function () {
        Route::post('/add/address', 'addAddress');
        Route::post('/update/address/{id}', 'updateAddress');
        Route::delete('/delete/address/{id}', 'deleteAddress');
        Route::post('/default/address/{id}', 'defaultAddress');
    });

    Route::controller(ConnectAccount::class)->prefix('stripe/account')->group(function () {
        Route::post('/connect', 'connectAccount');
    });

    /**
     * Chat API
     * customer and craftsperson
     * craftsperson and customer
     */
    Route::controller(ChatController::class)->group(function () {
        Route::post('/send-message/{id}', 'sendMessage');
        Route::get('/chat/{id}', 'getChat');
        Route::get('/conversations', 'conversations');
        Route::post('/message-react/{id}', 'messageReact');
    });


    /**
     * Notification API
     * customer and craftsperson
     * craftsperson and customer
     */
    Route::controller(NotificationController::class)->group(function () {
        Route::get('/notification', 'getNotification');
    });


    /**
     * Customer Middleware
     */
    Route::group(['middleware' => ['customer']], function () {

        Route::controller(GetCraftspersonController::class)->group(function () {
            Route::get('/get-craftsperson', 'getCraftsperson');
            Route::get('/craftsperson-details/{id}', 'craftspersonDetails');
        });

        Route::controller(GetToolController::class)->group(function () {
            Route::get('/get-tool', 'getTool');
            Route::get('/tool-details/{id}', 'toolDetails');
        });

        /**
         * Booking API
         * @param id
         */
        Route::controller(BookingController::class)->group(function () {
            Route::post('/add-booking/{id}', 'addBooking');
            Route::post('/reschedule-booking/{id}', 'rescheduleBooking');
            Route::get('/cancel-booking/{id}', 'cancelBooking');
            Route::get('/completed-booking/{id}', 'completedBooking');
        });

        /**
         * My Booking API
         */

        Route::controller(MyBookingController::class)->group(function () {
            Route::get('/my-booking', 'myBooking');
            Route::get('/my-booking/{id}', 'myBookingDetails');
        });

        /**
         * Tool Booking API
         * @param id
         */
        Route::controller(ToolBookingController::class)->group(function () {
            Route::post('/tool-booking/{id}', 'toolBooking');
            Route::get('/tool-booking-cancel/{id}', 'toolBookingCancel');
            Route::get('/tool-booking-completed/{id}', 'toolBookingCompleted');
        });

        Route::controller(MyToolBookingController::class)->group(function () {
            Route::get('/my-tool-booking', 'myToolBooking');
            Route::get('/my-tool-booking/details/{id}', 'myToolBookingDetails');
        });

        Route::controller(ToolReviewController::class)->group(function () {
            Route::post('/add-tool-review/{id}', 'addToolReview');
        });
    });

    /**
     * Craftsperson Middleware
     */
    Route::group(['middleware' => ['craftsperson']], function () {

        Route::controller(OnBodingController::class)->group(function () {
            Route::post('/on-boarding', 'onBoarding');
        });

        Route::controller(JobRequestController::class)->group(function () {
            Route::get('/job-request', 'jobRequest');
            Route::get('/job-request-accept/{id}', 'jobRequestAccept');
            Route::get('/job-request-cancel/{id}', 'jobRequestCancel');
            Route::get('/job-in-progress/{id}', 'jobInProgress');
        });

        Route::controller(ToolController::class)->group(function () {
            Route::post('/add-tool', 'addTool');
        });

        Route::controller(ToolRentController::class)->group(function () {
            Route::get('/rent-request', 'rentRequest');
            Route::get('/rent-request-accept/{id}', 'rentRequestAccept');
            Route::get('/rent-request-cancel/{id}', 'rentRequestCancel');
        });
    });

    /**
     * Review API Route
     * customer and craftsperson
     * craftsperson and customer
     */

    Route::controller(ReviewController::class)->group(function () {
        Route::post('/add-review/{id}', 'addReview');
        Route::get('/get-review', 'getReview');
    });
});

/**
 * Category Get API
 */
Route::controller(CategoryController::class)->group(function () {
    Route::get('/categories', 'getCategories');
});


Route::controller(ConnectAccount::class)->prefix('instructor')->group(function () {
    Route::get('/connect/success', 'success')->name('connect.success');
    Route::get('/connect/cancel', 'cancel')->name('connect.cancel');
});

Route::controller(BookingController::class)->group(function () {
    Route::get('/checkout-success', 'checkoutSuccess')->name('checkout.success');
    Route::get('/checkout-cancel', 'checkoutCancel')->name('checkout.cancel');
});
