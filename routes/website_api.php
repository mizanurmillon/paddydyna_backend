<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\SocialMediaController;

Route::controller(SettingController::class)->group(function () {
    Route::get('/get-setting', 'getSetting');
});

Route::controller(SocialMediaController::class)->group(function () {
    Route::get('/get-social-media', 'getSocialMedia');
});

Route::controller(FaqController::class)->group(function () {
    Route::get('/get-faq', 'getFaq');
});

Route::controller(BlogController::class)->group(function () {
    Route::get('/get-blogs', 'getBlogs');
    Route::get('/get-blogs/{slug}', 'getBlog');
});
