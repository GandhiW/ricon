<?php

namespace App\Observers;

use App\Models\LockerSession;
use App\Models\Notification;

class LockerSessionObserver
{
    /**
     * Handle the LockerSession "created" event.
     */
    public function created(LockerSession $session)
    {
        // Trigger notif ketika session baru dibuat (booking)
        Notification::create([
            'user_id' => $session->user_id,
            'locker_item_id' => null, // belum ada item
            'title' => "Locker {$session->locker?->id} berhasil dibooking!",
            'is_read' => false,
        ]);
    }

    /**
     * Handle the LockerSession "updated" event.
     */
    public function updated(LockerSession $session)
    {
        if ($session->wasChanged('ended_at') && $session->ended_at) {
            Notification::create([
                'user_id' => $session->user_id,
                'title' => 'Barang telah diambil dari locker',
            ]);
        }

        // SESSION EXPIRED (waktu habis)
        if ($session->wasChanged('status') && $session->status === 'expired') {
            $lockerCode = optional($session->locker)->id ?? 'Unknown';

            Notification::create([
                'user_id' => $session->user_id,
                'title'   => "Booking locker {$lockerCode} telah expired",
                'type'    => 'session_expired',
                'is_read' => false,
            ]);
        }

        // SESSION DONE / RELEASE MANUAL
        if ($session->wasChanged('status') && $session->status === 'done') {
            $lockerCode = optional($session->locker)->id ?? 'Unknown';

            Notification::create([
                'user_id' => $session->user_id,
                'title'   => "Penggunaan locker {$lockerCode} telah selesai",
                'type'    => 'session_done',
                'is_read' => false,
            ]);
        }


        if ($session->wasChanged('taken_at') && $session->taken_at !== null) {

            $takenByName = optional($session->assignedTaker)->name ?? 'Unknown';
            $lockerCode = optional($session->locker)->id ?? 'Unknown';

            // Gabung nama barang
            $itemNames = $session->items
                ->pluck('item_name')
                ->implode(', ') ?: 'Barang';

            Notification::create([
                'user_id' => $session->user_id,
                'locker_item_id' => null, // notif level session
                'title' => "Barang ({$itemNames}) di locker {$lockerCode} telah diambil oleh {$takenByName}. Penggunaan locker selesai.",
                'data' => [
                    'taken_by' => $takenByName,
                    'taken_at' => $session->taken_at,
                    'locker_code' => $lockerCode,
                    'session_id' => $session->id,
                ],
                'is_read' => false,
            ]);
        }
    }

    /**
     * Handle the LockerSession "deleted" event.
     */
    public function deleted(LockerSession $lockerSession): void
    {
        //
    }

    /**
     * Handle the LockerSession "restored" event.
     */
    public function restored(LockerSession $lockerSession): void
    {
        //
    }

    /**
     * Handle the LockerSession "force deleted" event.
     */
    public function forceDeleted(LockerSession $lockerSession): void
    {
        //
    }
}
