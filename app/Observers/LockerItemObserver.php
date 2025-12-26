<?php

namespace App\Observers;

use App\Models\LockerItem;
use App\Models\Notification;

class LockerItemObserver
{
    /**
     * Trigger saat locker_item di-update
     */
    public function updated(LockerItem $item): void
    {
        // -------- Notif saat barang masuk ke locker --------
        if ($item->wasChanged('added_at') && $item->added_at !== null) {
            $itemName = $item->item_name ?? 'Barang';
            $userId   = optional($item->session)->user_id;

            if ($userId) {
                Notification::create([
                    'user_id'        => $userId,
                    'locker_item_id' => $item->id,
                    'title'          => "{$itemName} telah masuk ke locker",
                    'is_read'        => false,
                ]);
            }
        }
    }

}
