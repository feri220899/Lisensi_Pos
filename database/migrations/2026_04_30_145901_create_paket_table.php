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
        Schema::create('paket', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('slug')->unique();
            $table->integer('max_device'); // -1 = unlimited
            $table->integer('grace_period_hari')->default(1);
            $table->boolean('support_lifetime')->default(true);
            $table->boolean('support_subscription')->default(true);
            $table->decimal('harga_lifetime', 12, 2)->nullable();
            $table->decimal('harga_bulanan', 12, 2)->nullable();
            $table->decimal('harga_tahunan', 12, 2)->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paket');
    }
};
