<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Backend\CMS\OurMissionController;
use App\Http\Controllers\Web\Backend\FaqController;
use App\Http\Controllers\Web\Backend\BlogController;
use App\Http\Controllers\Web\Backend\CMS\HeroSectionController;
use App\Http\Controllers\Web\Backend\CMS\AboutSectionController;
use App\Http\Controllers\Web\Backend\CMS\OurValueController;
use App\Http\Controllers\Web\Backend\CMS\PlatformOverviewController;

//Our Mission Routes
Route::controller(OurMissionController::class)->group(function () {
    Route::get('/cms/our-mission', 'index')->name('admin.our_mission.index');
    Route::post('/cms/our-mission/update', 'update')->name('admin.our_mission.update');
});


//FAQ Routes
Route::controller(FaqController::class)->group(function () {
    Route::get('/faqs', 'index')->name('admin.faqs.index');
    Route::get('/faqs/create', 'create')->name('admin.faqs.create');
    Route::post('/faqs/store', 'store')->name('admin.faqs.store');
    Route::get('/faqs/edit/{id}', 'edit')->name('admin.faqs.edit');
    Route::post('/faqs/update/{id}', 'update')->name('admin.faqs.update');
    Route::post('/faqs/status/{id}', 'status')->name('admin.faqs.status');
    Route::post('/faqs/destroy/{id}', 'destroy')->name('admin.faqs.destroy');
});

//blog routes
Route::controller(BlogController::class)->group(function () {
    Route::get('/blogs', 'index')->name('admin.blogs.index');
    Route::get('/blogs/create', 'create')->name('admin.blogs.create');
    Route::post('/blogs/store', 'store')->name('admin.blogs.store');
    Route::get('/blogs/edit/{id}', 'edit')->name('admin.blogs.edit');
    Route::post('/blogs/update/{id}', 'update')->name('admin.blogs.update');
    Route::post('/blogs/status/{id}', 'status')->name('admin.blogs.status');
    Route::delete('/blogs/destroy/{id}', 'destroy')->name('admin.blogs.destroy');
});

/**
 * CMS
 * Page Home
 * Hero Section
 */
Route::controller(HeroSectionController::class)->group(function () {
    Route::get('/cms/hero-section', 'index')->name('admin.hero_section.index');
    Route::post('/cms/hero-section/update', 'update')->name('admin.hero_section.update');
});

/**
 * CMS
 * Section About */
Route::controller(AboutSectionController::class)->group(function () {
    Route::get('/cms/about-section', 'index')->name('admin.about_section.index');
    Route::post('/cms/about-section/update', 'update')->name('admin.about_section.update');

    Route::get('/cms/about-section/item/create', 'create')->name('admin.about_section.item.create');
    Route::post('/cms/about-section/item/store', 'store')->name('admin.about_section.item.store');
    Route::get('/cms/about-section/item/edit/{id}', 'edit')->name('admin.about_section.item.edit');
    Route::post('/cms/about-section/item/update/{id}', 'itemUpdate')->name('admin.about_section.item.update');
    Route::post('/cms/about-section/item/status/{id}', 'status')->name('admin.about_section.item.status');
    Route::delete('/cms/about-section/item/destroy/{id}', 'destroy')->name('admin.about_section.item.destroy');
});

/**
 * CMS
 * Section Platform Overview
 */
Route::controller(PlatformOverviewController::class)->group(function () {
    Route::get('/cms/platform-overview', 'index')->name('admin.platform_overview.index');
    Route::post('/cms/platform-overview/update', 'update')->name('admin.platform_overview.update');

    Route::get('/cms/platform-overview/slider/create', 'sliderCreate')->name('admin.platform_overview.slider.create');
    Route::post('/cms/platform-overview/slider/store', 'sliderStore')->name('admin.platform_overview.slider.store');
    Route::get('/cms/platform-overview/slider/edit/{id}', 'sliderEdit')->name('admin.platform_overview.slider.edit');
    Route::post('/cms/platform-overview/slider/update/{id}', 'sliderUpdate')->name('admin.platform_overview.slider.update');
    Route::post('/cms/platform-overview/slider/status/{id}', 'sliderStatus')->name('admin.platform_overview.slider.status');
    Route::delete('/cms/platform-overview/slider/destroy/{id}', 'sliderDestroy')->name('admin.platform_overview.slider.destroy');
});

//Our Value Routes
Route::controller(OurValueController::class)->group(function () {
    Route::get('/cms/our-value', 'index')->name('admin.our_value.index');
    Route::get('/cms/our-value/{id}/update', 'edit')->name('admin.our_value.edit');
    Route::post('/cms/our-value/update', 'update')->name('admin.our_value.update');
});
