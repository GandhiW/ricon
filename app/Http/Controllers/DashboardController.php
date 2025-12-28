<?php

namespace App\Http\Controllers;

use App\Models\Locker;
use App\Models\LockerSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // memastikan user login
    }

    public function index()
    {
        $booking = LockerSession::where('user_id', Auth::id())
        ->where('status', 'active') // atau booked
        ->latest()
        ->first();
        // dd($booking);

        $availableLockerCount = Locker::where('status', 'available')->count();

        return view('dashboard.index', compact('booking', 'availableLockerCount'));
    }
}
