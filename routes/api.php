<?php

use App\Http\Controllers\API\V1\ApartmentController;
use App\Http\Controllers\API\V1\BookingController;
use App\Http\Controllers\API\V1\DashboardController;
use App\Http\Controllers\API\V1\TenantController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::apiResource('apartments', ApartmentController::class);
    Route::apiResource('tenants', TenantController::class);
    Route::apiResource('bookings', BookingController::class);
    Route::get('dashboard/summary', [DashboardController::class, 'summary']);
});
