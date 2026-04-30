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
        Schema::create('aktivasi_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lisensi_id')->constrained('lisensi')->cascadeOnDelete();
            $table->string('device_id');
            $table->enum('aksi', ['aktivasi', 'validasi', 'deaktivasi', 'revoke', 'expired']);
            $table->enum('hasil', ['sukses', 'gagal']);
            $table->string('ip_address')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamp('terjadi_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aktivasi_log');
    }
};
