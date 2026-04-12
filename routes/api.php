<?php

use App\Http\Controllers\API\V1\AdminDashboardController;
use App\Http\Controllers\API\V1\ApartmentController;
use App\Http\Controllers\API\V1\BookingController;
use App\Http\Controllers\API\V1\DashboardController;
use App\Http\Controllers\API\V1\TenantController;
use App\Models\Apartment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Tenant Routes
    // Register
    Route::post('register', [TenantController::class, 'register']);
    Route::post('login', [TenantController::class, 'login']);

    // Apartment Data
    Route::get('apartment-data', function () {
        $apartments = Apartment::get();
        return response()->json([
            'message' => 'Data retrieve successfully',
            'data' => $apartments,
        ]);
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/bookings', [TenantController::class, 'bookings'])->name('bookings');
        Route::post('logout', [TenantController::class, 'logout']);
    });

    // Admin Routes
    Route::post('/admin/login', [AdminDashboardController::class, 'adminLogin']);
    Route::prefix('/admin/dashboard')->middleware('auth:sanctum')->group(function () {
        Route::get('data', [AdminDashboardController::class, 'data']);
        Route::apiResource('apartments', ApartmentController::class);
        Route::get('apartment/{id}/edit', [ApartmentController::class, 'edit']);
        Route::apiResource('tenants', TenantController::class);
        Route::apiResource('bookings', BookingController::class);
        Route::get('dashboard/summary', [DashboardController::class, 'summary']);
        Route::post('logout', [AdminDashboardController::class, 'logout']);
    });
});
