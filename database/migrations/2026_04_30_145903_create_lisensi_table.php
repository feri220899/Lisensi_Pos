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
        Schema::create('lisensi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('akun_id')->constrained('akun')->cascadeOnDelete();
            $table->foreignId('paket_id')->constrained('paket');
            $table->string('license_key')->unique();
            $table->enum('tipe', ['lifetime', 'subscription']);
            $table->enum('status', ['aktif', 'nonaktif', 'expired', 'suspended'])->default('aktif');
            $table->timestamp('tanggal_mulai')->nullable();
            $table->timestamp('tanggal_berakhir')->nullable(); // null = lifetime
            $table->timestamp('last_validated_at')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lisensi');
    }
};
