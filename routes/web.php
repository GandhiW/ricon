<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HistoryController;
use App\Models\LockerSession;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::resource('/', DashboardController::class)->only(['index']);

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

Route::resource('/history', HistoryController::class);
