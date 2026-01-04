<?php

use App\Models\LockerSession;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;

Schedule::call(function () {
    Log::info('=== CHECKING LOCKER STATUS ===');

    $sessions = LockerSession::where('status', 'active')->get();

    foreach ($sessions as $session) {
        try {
            $response = Http::timeout(5)->get('http://192.168.18.102:2200/api/locker');
            if (! $response->ok()) continue;

            // 🔑 Ambil sensor sesuai locker_id
            $lockerKey = 'locker' . $session->locker_id;
            $sensor = $response->json($lockerKey);

            if ($sensor === null) {
                Log::warning("Sensor {$lockerKey} tidak ditemukan");
                continue;
            }

            $minutesSinceBooking = $session->created_at->diffInMinutes(now());

            // ⏱️ 3 menit tidak isi barang
            if ($sensor === 'KOSONG' && $minutesSinceBooking >= 3) {
                autoReleaseExpired($session);
                Log::warning("Loker {$session->locker_id} expired (tidak diisi).");
            }

        } catch (\Throwable $e) {
            Log::error('Scheduler Error: ' . $e->getMessage());
        }
    }
})->everyMinute();

// Helper function tetap di luar call() atau buat di file terpisah
function autoReleaseExpired($session) {
    DB::transaction(function () use ($session) {
        // Update status session jadi 'completed' atau 'expired'
        $session->update([
            'status' => 'expired'
        ]);

        // Kembalikan loker agar bisa dipakai orang lain
        $session->locker()->update(['status' => 'available']);
    });
}
