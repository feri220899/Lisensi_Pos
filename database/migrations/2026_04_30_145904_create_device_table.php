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
        Schema::create('device', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lisensi_id')->constrained('lisensi')->cascadeOnDelete();
            $table->string('device_id')->unique(); // hardware fingerprint
            $table->string('nama_device')->nullable();
            $table->string('os')->nullable();
            $table->string('hostname')->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamp('last_seen_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device');
    }
};
