<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\ReportController; 

// Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Resource Routes for CRUD operations
Route::resource('customers', CustomerController::class);
Route::resource('cars', CarController::class);
Route::resource('rentals', RentalController::class);

// Reports Route
Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');