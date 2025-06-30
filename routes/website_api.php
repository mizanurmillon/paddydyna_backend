<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\HomePageController;
use App\Http\Controllers\Api\AboutPageController;
use App\Http\Controllers\Api\BecomeNixrController;
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

Route::controller(HomePageController::class)->group(function () {
    Route::get('/get-home-page', 'getHomePage');
});

Route::controller(AboutPageController::class)->group(function () {
    Route::get('/get-about-page', 'getAboutPage');
});

Route::controller(BecomeNixrController::class)->group(function () {
    Route::post('/add-become-nixr', 'addBecomeNixr');
});
