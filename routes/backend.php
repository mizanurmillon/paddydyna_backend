<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Backend\FaqController;
use App\Http\Controllers\Web\Backend\CategoryController;
use App\Http\Controllers\Web\Backend\DashboardController;
use App\Http\Controllers\Web\Backend\CraftspersonController;

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