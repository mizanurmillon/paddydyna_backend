<?php

use App\Http\Controllers\Web\Backend\CategoryController;
use App\Http\Controllers\Web\Backend\DashboardController;
use Illuminate\Support\Facades\Route;

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
