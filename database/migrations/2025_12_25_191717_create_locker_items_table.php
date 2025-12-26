<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('locker_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('locker_session_id')->constrained('locker_sessions')->cascadeOnDelete();
            $table->string('item_name');
            $table->text('item_detail')->nullable();
            $table->string('key')->unique()->nullable(); // The QR string
            $table->boolean('opened_by_sender')->default(1); // 1 = Fresh, 0 = Used
            $table->string('qr_path')->nullable(); // For your Laravel dashboard
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locker_items');
    }
};
