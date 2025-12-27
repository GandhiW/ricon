<?php

namespace App\Http\Controllers;

use App\Models\Locker;
use App\Models\LockerItem;
use App\Models\LockerSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LockerBookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lockers = Locker::all();
        return view('book_locker', compact('lockers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $request->vaildate([
        //     'locker_id'
        // ])
        // dd($request);
          DB::transaction(function () use ($request) {

            //Lock row avoid double booking
            $locker = Locker::where('id', $request->locker_id)
                ->where('status', 'available')
                ->lockForUpdate()
                ->firstOrFail();

            //Create locker session
            $session = LockerSession::create([
                'locker_id' => $locker->id,
                // 'user_id'   => Auth::id(),
                'user_id'   => 1,
                'status'    => 'active',
            ]);

            //Create locker item
            LockerItem::create([
                'locker_id'   => $session->id, // FK ke locker_sessions
                'item_name'   => $request->item_name,
                'item_detail' => $request->item_detail,
            ]);

            //Update locker status
            $locker->update([
                'status' => 'occupied',
            ]);
        });

        return redirect()
            ->route('book.index')
            ->with('success', 'Loker berhasil disewa');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
