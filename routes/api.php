<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\LockerBookingController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::get('/notifications/{id}', [NotificationController::class, 'show']);
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::get('/locker-session/{session}/taken-notif', [NotificationController::class, 'itemTakenNotificationOnly']);
    Route::get('/notifications/booking', [NotificationController::class, 'indexBookingNotifications']);

    // Route::resource('/', DashboardController::class)->only(['index']);
    // Route::resource('/history', HistoryController::class);
    // Route::resource('/book', LockerBookingController::class);
});
