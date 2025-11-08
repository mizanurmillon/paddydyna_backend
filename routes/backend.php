<?php

use Illuminate\Support\Facades\Route;
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

    Route::get('/approve/{id}', 'approve')->name('admin.approve');
    Route::get('/reject/{id}', 'reject')->name('admin.reject');
});
