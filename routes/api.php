<?php

use App\Http\Controllers\API\V1\ApartmentController;
use App\Http\Controllers\API\V1\BookingController;
use App\Http\Controllers\API\V1\DashboardController;
use App\Http\Controllers\API\V1\TenantController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Register
    Route::post('register', [TenantController::class, 'register']);
    Route::post('login', [TenantController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::apiResource('apartments', ApartmentController::class);
        Route::apiResource('tenants', TenantController::class);
        Route::apiResource('bookings', BookingController::class);
        Route::get('dashboard/summary', [DashboardController::class, 'summary']);
        Route::post('logout', [TenantController::class, 'logout']);
    });
});
