<?php

namespace App\Services;

use App\Mail\LisensiMail;
use App\Models\Lisensi;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class LisensiService
{
    public function generateDariOrder(Order $order): Lisensi
    {
        [$tipe, $tanggalBerakhir] = $this->resolveTypeDanExpiry($order->tipe_lisensi);

        $lisensi = DB::transaction(function () use ($order, $tipe, $tanggalBerakhir) {
            if ($order->lisensi_lama_id) {
                Lisensi::find($order->lisensi_lama_id)?->delete();
            }

            return Lisensi::create([
                'akun_id'          => $order->akun_id,
                'paket_id'         => $order->paket_id,
                'license_key'      => $this->generateKey(),
                'tipe'             => $tipe,
                'status'           => 'aktif',
                'tanggal_mulai'    => now(),
                'tanggal_berakhir' => $tanggalBerakhir,
            ]);
        });

        $order->update(['lisensi_id' => $lisensi->id, 'status' => 'paid', 'paid_at' => now()]);

        Mail::to($order->akun->email)->send(new LisensiMail($lisensi, $order->akun));

        return $lisensi;
    }

    private function resolveTypeDanExpiry(string $tipePembelian): array
    {
        return match ($tipePembelian) {
            'lifetime'             => ['lifetime', null],
            'subscription_bulanan' => ['subscription', now()->addMonth()],
            'subscription_tahunan' => ['subscription', now()->addYear()],
        };
    }

    private function generateKey(): string
    {
        do {
            $key = strtoupper(implode('-', str_split(Str::random(20), 5)));
        } while (Lisensi::where('license_key', $key)->exists());
        return $key;
    }
}
