<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Backend\FaqController;
use App\Http\Controllers\Web\Backend\BlogController;
use App\Http\Controllers\Web\Backend\CategoryController;
use App\Http\Controllers\Web\Backend\DashboardController;
use App\Http\Controllers\Web\Backend\CraftspersonController;
use App\Http\Controllers\Web\Backend\CMS\HeroSectionController;
use App\Http\Controllers\Web\Backend\CMS\AboutSectionController;
use App\Http\Controllers\Web\Backend\CMS\PlatformOverviewController;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

Route::controller(CategoryController::class)->group(function () {
    Route::get('/categories', 'index')->name('admin.categories.index');
    Route::get('/categories/create', 'create')->name('admin.categories.create');
    Route::post('/categories', 'store')->name('admin.categories.store');
    Route::get('/categories/{id}/edit', 'edit')->name('admin.categories.edit');
    Route::post('/categories/{id}/update', 'update')->name('admin.categories.update');
    Route::delete('/categories/{id}/delete', 'destroy')->name('admin.categories.destroy');
    Route::post('/categories/{id}/status', 'status')->name('admin.categories.status');
});

Route::controller(CraftspersonController::class)->group(function () {
    Route::get('/craftsperson', 'index')->name('admin.craftsperson.index');
    Route::post('/craftsperson/{id}/status', 'status')->name('admin.craftsperson.status');
    Route::get('/craftsperson/{id}/show', 'show')->name('admin.craftsperson.show');
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
});