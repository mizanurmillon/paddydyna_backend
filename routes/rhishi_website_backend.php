<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Backend\CMS\OurMissionController;

Route::controller(OurMissionController::class)->group(function () {
    Route::get('/cms/our-mission', 'index')->name('admin.our_mission.index');
    Route::post('/cms/our-mission/update', 'update')->name('admin.our_mission.update');
});
