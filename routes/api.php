<?php

use App\Http\Controllers\API\V1\ApartmentController;
use App\Http\Controllers\API\V1\TenantController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::apiResource('apartments', ApartmentController::class);
    Route::apiResource('tenants', TenantController::class);
});
