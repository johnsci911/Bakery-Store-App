<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\StoreHoursController;
use App\Http\Controllers\UserNotificationPreferencesController;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/store-hours', [StoreHoursController::class, 'edit'])->name('admin.store-hours.edit');
    Route::post('/admin/store-hours', [StoreHoursController::class, 'update'])->name('admin.store-hours.update');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/user/notification-preferences', [UserNotificationPreferencesController::class, 'edit'])
        ->name('user-notification-preferences.edit');

    Route::post('/user/notification-preferences', [UserNotificationPreferencesController::class, 'update'])
        ->name('user-notification-preferences.update');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
