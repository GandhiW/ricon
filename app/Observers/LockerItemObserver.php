<?php

namespace App\Observers;

use App\Models\LockerItem;
use App\Models\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LockerItemObserver
{

    /**
     * Handle the LockerItem "creating" event.
     * Generates a QR key from the Flask AI server.
     */
    public function creating(LockerItem $item): void
    {
        try {
            // Call Flask to generate and save the image
            $response = Http::get('http://localhost:5000/generate');

            if ($response) {
                $data = $response->json();

                // Save both the key and the path to the database
                $item->key = $data['key'];
                $item->qr_path = $data['qr_path'];
                $item->opened_by_sender = 0;
            }
        } catch (\Exception $e) {
            Log::error("QR Generation failed: " . $e->getMessage());
        }
    }

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
