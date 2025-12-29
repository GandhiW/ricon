<?php

namespace App\Http\Controllers;

use App\Models\LockerItem;
use App\Models\LockerSession;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{

    public function index()
    {
        return Notification::where('user_id', Auth::id())
            ->latest()
            ->get();
    }

    public function show($id)
    {
        $notification = Notification::with([
            'lockerItem.session.locker'
        ])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return response()->json([
            'id' => $notification->id,
            'title' => $notification->title,
            'is_read' => $notification->is_read,
            'created_at' => $notification->created_at,

            'item' => [
                'name' => $notification->lockerItem?->item_name,
                'detail' => $notification->lockerItem?->item_detail,
                'added_at' => $notification->lockerItem?->added_at,
            ],

            'session' => [
                'status' => $notification->lockerItem?->session?->status,
                'taken_at' => $notification->lockerItem?->session?->taken_at,
                'ended_at' => $notification->lockerItem?->session?->ended_at,
            ],

            'locker' => [
                'locker_code' => $notification->lockerItem?->session?->locker?->locker_code,
            ],
        ]);
    }

    public function markAsRead($id)
    {
        $notif = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $notif->update(['is_read' => true]);

        return response()->json([
            'message' => 'Notification marked as read'
        ]);
    }

    // =============== BARANG DIAMBIL ===============
    public function itemTakenNotificationOnly(LockerSession $session)
    {
        if (!$session) {
            return response()->json(['message' => 'Session not found'], 404);
        }

        $takenByName = optional($session->assignedTaker)->name ?? 'Unknown';
        $itemNames = $session->items?->pluck('item_name')->implode(', ') ?? 'Barang';
        $lockerCode = optional($session->locker)->id ?? 'Unknown';

        Notification::create([
            'user_id' => $session->user_id,
            'locker_item_id' => null,
            'type' => 'item_taken',
            'title' => "Barang ({$itemNames}) di locker {$lockerCode} telah diambil oleh {$takenByName}",
            'data' => [
                'taken_by' => $takenByName,
                'taken_at' => $session->taken_at,
            ],
            'is_read' => false,
        ]);

        return response()->json([
            'message' => 'Taken notification sent'
        ]);
    }

    public function itemDeliveredNotification(LockerItem $item)
    {
        if (!$item || !$item->session) {
            return null;
        }

        if ((int)$item->opened_by_sender !== 0) {
            return null; 
        }

        return Notification::create([
            'user_id' => $item->session->user_id,
            'locker_item_id' => $item->id,
            'type' => 'item_delivered',
            'title' => "Barang '{$item->item_name}' telah masuk ke loker",
            'data' => [
                'item_name' => $item->item_name,
                'item_detail' => $item->item_detail,
                'added_at' => $item->created_at,
            ],
            'is_read' => false
        ]);
    }
}
