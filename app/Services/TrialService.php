<?php

namespace App\Services;

use App\Models\Akun;
use App\Models\Lisensi;
use App\Models\Paket;
use Illuminate\Support\Str;

class TrialService
{
    const DURASI_HARI = 7;

    public function sudahTrialEmail(string $email): bool
    {
        $akun = Akun::where('email', $email)->first();
        if (!$akun) return false;
        return $akun->lisensi()->where('tipe', 'trial')->exists();
    }

    public function buat(string $email): Lisensi
    {
        $akun = Akun::firstOrCreate(
            ['email' => $email],
            [
                'nama'       => $email,
                'nama_toko'  => '-',
                'telepon'    => '-',
                'password'   => bcrypt(Str::random(32)),
                'aktif'      => true,
            ]
        );

        return $this->buatDariAkun($akun);
    }

    public function buatDariAkun(Akun $akun): Lisensi
    {
        $paket = Paket::where('slug', 'trial')->firstOrFail();

        return Lisensi::create([
            'akun_id'          => $akun->id,
            'paket_id'         => $paket->id,
            'license_key'      => $this->generateKey(),
            'tipe'             => 'trial',
            'status'           => 'aktif',
            'tanggal_mulai'    => now(),
            'tanggal_berakhir' => now()->addDays(self::DURASI_HARI),
        ]);
    }

    private function generateKey(): string
    {
        do {
            $key = strtoupper(implode('-', str_split(Str::random(20), 5)));
        } while (Lisensi::where('license_key', $key)->exists());
        return $key;
    }
}
