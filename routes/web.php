<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\ReportController; 

use Illuminate\Support\Facades\Artisan;

Route::get('/gas-migrate', function () {
    try {
        Artisan::call('migrate', ['--force' => true]);
        return "<h1>Migrasi Berhasil!</h1><pre>" . Artisan::output() . "</pre>";
    } catch (\Exception $e) {
        return "<h1>Gagal:</h1>" . $e->getMessage();
    }
});

// Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Resource Routes for CRUD operations
Route::resource('customers', CustomerController::class);
Route::resource('cars', CarController::class);
Route::resource('rentals', RentalController::class);

// Reports Route
Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
