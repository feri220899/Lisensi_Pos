<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AktivasiRequest;
use App\Http\Requests\Api\DeaktivasiRequest;
use App\Http\Requests\Api\ValidasiRequest;
use App\Models\AktivasiLog;
use App\Models\Device;
use App\Models\Lisensi;
use Illuminate\Http\JsonResponse;

class LisensiController extends Controller
{
    public function aktivasi(AktivasiRequest $request): JsonResponse
    {
        $lisensi = Lisensi::with('paket')
            ->where('license_key', $request->license_key)
            ->first();

        if (!$lisensi) {
            return $this->gagal($request, null, 'aktivasi', 'License key tidak ditemukan.');
        }

        if (!$lisensi->isAktif()) {
            return $this->gagal($request, $lisensi, 'aktivasi', 'Lisensi tidak aktif atau sudah expired.');
        }

        $deviceSudahAda = Device::where('device_id', $request->device_id)
            ->where('lisensi_id', $lisensi->id)
            ->first();

        if (!$deviceSudahAda) {
            $deviceLisensiLain = Device::where('device_id', $request->device_id)->first();
            if ($deviceLisensiLain) {
                if ($deviceLisensiLain->aktif) {
                    return $this->gagal($request, $lisensi, 'aktivasi', 'Device sudah aktif di lisensi lain.');
                }

                if (!$lisensi->bisaTambahDevice()) {
                    return $this->gagal($request, $lisensi, 'aktivasi', 'Batas maksimal device untuk paket ini sudah tercapai.');
                }

                $deviceLisensiLain->update([
                    'lisensi_id'   => $lisensi->id,
                    'nama_device'  => $request->nama_device,
                    'os'           => $request->os,
                    'hostname'     => $request->hostname,
                    'aktif'        => true,
                    'last_seen_at' => now(),
                ]);
                $this->log($lisensi->id, $request->device_id, 'aktivasi', 'sukses', $request->ip(), 'Device dipindahkan dari lisensi lain.');
                return $this->sukses($lisensi, 'Aktivasi berhasil.');
            }
        }

        if ($deviceSudahAda) {
            if ($deviceSudahAda->aktif) {
                $deviceSudahAda->update(['last_seen_at' => now()]);
                $this->log($lisensi->id, $request->device_id, 'aktivasi', 'sukses', $request->ip(), 'Device sudah aktif.');
                return $this->sukses($lisensi, 'Device sudah aktif.');
            }

            if (!$lisensi->bisaTambahDevice()) {
                return $this->gagal($request, $lisensi, 'aktivasi', 'Batas maksimal device untuk paket ini sudah tercapai.');
            }

            $deviceSudahAda->update(['aktif' => true, 'last_seen_at' => now()]);
            $this->log($lisensi->id, $request->device_id, 'aktivasi', 'sukses', $request->ip(), 'Device diaktifkan ulang.');
            return $this->sukses($lisensi, 'Device berhasil diaktifkan ulang.');
        }

        if (!$lisensi->bisaTambahDevice()) {
            return $this->gagal($request, $lisensi, 'aktivasi', 'Batas maksimal device untuk paket ini sudah tercapai.');
        }

        Device::create([
            'lisensi_id'  => $lisensi->id,
            'device_id'   => $request->device_id,
            'nama_device' => $request->nama_device,
            'os'          => $request->os,
            'hostname'    => $request->hostname,
            'aktif'       => true,
            'last_seen_at' => now(),
        ]);

        $this->log($lisensi->id, $request->device_id, 'aktivasi', 'sukses', $request->ip());
        return $this->sukses($lisensi, 'Aktivasi berhasil.');
    }

    public function validasi(ValidasiRequest $request): JsonResponse
    {
        $lisensi = Lisensi::with('paket')
            ->where('license_key', $request->license_key)
            ->first();

        if (!$lisensi) {
            return $this->gagal($request, null, 'validasi', 'License key tidak ditemukan.');
        }

        $device = Device::where('device_id', $request->device_id)
            ->where('lisensi_id', $lisensi->id)
            ->where('aktif', true)
            ->first();

        if (!$device) {
            return $this->gagal($request, $lisensi, 'validasi', 'Device tidak terdaftar atau sudah dinonaktifkan.');
        }

        if (!$lisensi->isAktif()) {
            $this->log($lisensi->id, $request->device_id, 'validasi', 'gagal', $request->ip(), 'Lisensi expired.');
            return response()->json([
                'valid'      => false,
                'status'     => 'expired',
                'pesan'      => 'Lisensi sudah expired.',
                'grace_sisa' => $this->hitungGraceSisa($lisensi),
            ], 403);
        }

        $device->update(['last_seen_at' => now()]);
        $lisensi->update(['last_validated_at' => now()]);
        $this->log($lisensi->id, $request->device_id, 'validasi', 'sukses', $request->ip());

        return $this->sukses($lisensi, 'Lisensi valid.');
    }

    public function deaktivasi(DeaktivasiRequest $request): JsonResponse
    {
        $lisensi = Lisensi::where('license_key', $request->license_key)->first();

        if (!$lisensi) {
            return $this->gagal($request, null, 'deaktivasi', 'License key tidak ditemukan.');
        }

        $device = Device::where('device_id', $request->device_id)
            ->where('lisensi_id', $lisensi->id)
            ->first();

        if (!$device) {
            return $this->gagal($request, $lisensi, 'deaktivasi', 'Device tidak ditemukan.');
        }

        $device->update(['aktif' => false]);
        $this->log($lisensi->id, $request->device_id, 'deaktivasi', 'sukses', $request->ip());

        return response()->json(['sukses' => true, 'pesan' => 'Device berhasil dideaktivasi.']);
    }

    private function sukses(Lisensi $lisensi, string $pesan): JsonResponse
    {
        return response()->json([
            'valid'            => true,
            'pesan'            => $pesan,
            'paket'            => $lisensi->paket->nama,
            'tipe'             => $lisensi->tipe,
            'tanggal_berakhir' => $lisensi->tanggal_berakhir?->toDateString(),
            'grace_period'     => $lisensi->paket->grace_period_hari,
        ]);
    }

    private function gagal($request, ?Lisensi $lisensi, string $aksi, string $pesan): JsonResponse
    {
        if ($lisensi) {
            $this->log($lisensi->id, $request->device_id ?? '-', $aksi, 'gagal', $request->ip(), $pesan);
        }
        return response()->json(['valid' => false, 'pesan' => $pesan], 422);
    }

    private function log(int $lisensiId, string $deviceId, string $aksi, string $hasil, ?string $ip, ?string $keterangan = null): void
    {
        AktivasiLog::create([
            'lisensi_id'  => $lisensiId,
            'device_id'   => $deviceId,
            'aksi'        => $aksi,
            'hasil'       => $hasil,
            'ip_address'  => $ip,
            'keterangan'  => $keterangan,
        ]);
    }

    private function hitungGraceSisa(Lisensi $lisensi): int
    {
        if (!$lisensi->tanggal_berakhir) return 0;
        $grace = $lisensi->paket->grace_period_hari;
        $expiredPlusGrace = $lisensi->tanggal_berakhir->addDays($grace);
        return max(0, (int) now()->diffInDays($expiredPlusGrace, false));
    }
}
