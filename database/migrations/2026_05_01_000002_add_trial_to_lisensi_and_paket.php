<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF');

            Schema::create('lisensi_temp', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('akun_id');
                $table->unsignedBigInteger('paket_id');
                $table->string('license_key')->unique();
                $table->enum('tipe', ['lifetime', 'subscription', 'trial']);
                $table->enum('status', ['aktif', 'nonaktif', 'expired', 'suspended'])->default('aktif');
                $table->timestamp('tanggal_mulai')->nullable();
                $table->timestamp('tanggal_berakhir')->nullable();
                $table->timestamp('last_validated_at')->nullable();
                $table->text('catatan')->nullable();
                $table->timestamps();
            });

            DB::statement('INSERT INTO lisensi_temp SELECT * FROM lisensi');
            Schema::drop('lisensi');
            DB::statement('ALTER TABLE lisensi_temp RENAME TO lisensi');
            DB::statement('PRAGMA foreign_keys = ON');

        } elseif ($driver === 'pgsql') {
            DB::statement('ALTER TABLE lisensi DROP CONSTRAINT IF EXISTS lisensi_tipe_check');
            DB::statement("ALTER TABLE lisensi ALTER COLUMN tipe TYPE VARCHAR(20)");
            DB::statement("ALTER TABLE lisensi ADD CONSTRAINT lisensi_tipe_check CHECK (tipe IN ('lifetime', 'subscription', 'trial'))");

        } else {
            DB::statement("ALTER TABLE lisensi MODIFY COLUMN tipe ENUM('lifetime', 'subscription', 'trial')");
        }

        if (!DB::table('paket')->where('slug', 'trial')->exists()) {
            DB::table('paket')->insert([
                'nama'                 => 'Trial',
                'slug'                 => 'trial',
                'max_device'           => 1,
                'grace_period_hari'    => 0,
                'support_lifetime'     => false,
                'support_subscription' => false,
                'aktif'                => true,
                'created_at'           => now(),
                'updated_at'           => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('paket')->where('slug', 'trial')->delete();

        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF');

            Schema::create('lisensi_temp', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('akun_id');
                $table->unsignedBigInteger('paket_id');
                $table->string('license_key')->unique();
                $table->enum('tipe', ['lifetime', 'subscription']);
                $table->enum('status', ['aktif', 'nonaktif', 'expired', 'suspended'])->default('aktif');
                $table->timestamp('tanggal_mulai')->nullable();
                $table->timestamp('tanggal_berakhir')->nullable();
                $table->timestamp('last_validated_at')->nullable();
                $table->text('catatan')->nullable();
                $table->timestamps();
            });

            DB::statement("INSERT INTO lisensi_temp SELECT * FROM lisensi WHERE tipe != 'trial'");
            Schema::drop('lisensi');
            DB::statement('ALTER TABLE lisensi_temp RENAME TO lisensi');
            DB::statement('PRAGMA foreign_keys = ON');

        } elseif ($driver === 'pgsql') {
            DB::statement('ALTER TABLE lisensi DROP CONSTRAINT IF EXISTS lisensi_tipe_check');
            DB::statement("ALTER TABLE lisensi ALTER COLUMN tipe TYPE VARCHAR(20)");
            DB::statement("ALTER TABLE lisensi ADD CONSTRAINT lisensi_tipe_check CHECK (tipe IN ('lifetime', 'subscription'))");

        } else {
            DB::statement("ALTER TABLE lisensi MODIFY COLUMN tipe ENUM('lifetime', 'subscription')");
        }
    }
};
