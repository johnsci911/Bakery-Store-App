<?php

use App\Http\Controllers\Api\StoreHoursController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    // Public API routes
    Route::get('/store-hours', [StoreHoursController::class, 'index']);
});
