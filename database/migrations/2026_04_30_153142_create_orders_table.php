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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('akun_id')->constrained('akun')->cascadeOnDelete();
            $table->foreignId('paket_id')->constrained('paket');
            $table->foreignId('lisensi_id')->nullable()->constrained('lisensi')->nullOnDelete();
            $table->string('order_id')->unique(); // untuk Midtrans
            $table->enum('tipe_lisensi', ['lifetime', 'subscription_bulanan', 'subscription_tahunan']);
            $table->decimal('jumlah', 12, 2);
            $table->enum('status', ['pending', 'paid', 'failed', 'expired'])->default('pending');
            $table->string('midtrans_token')->nullable();
            $table->string('midtrans_url')->nullable();
            $table->json('midtrans_payload')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
