<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\LockerBookingController;
use App\Models\LockerSession;


Route::get('/login', function () {
    return view('login.login');
});

Route::get('/kiosk', function () {
    return view('layouts.kiosk');
})->name('kiosk.scan');

Route::get('/users/{user}/active-lockers', function ($userId) {
    return LockerSession::where('user_id', $userId)
        ->where('status', 'active')
        ->get(['locker_id']);
});

Route::resource('/', DashboardController::class)->only(['index']);
Route::resource('/history', HistoryController::class);
Route::resource('/book', LockerBookingController::class);
// Route::resource('/history', HistoryController::class);

// Route::middleware(['jwt.verify'])->group(function () {
//     Route::get('/', [DashboardController::class, 'index']);
//     Route::resource('history', HistoryController::class);
// });
