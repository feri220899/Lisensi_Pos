<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LisensiRequest;
use App\Models\Akun;
use App\Models\AktivasiLog;
use App\Models\Lisensi;
use App\Models\Paket;
use Illuminate\Support\Str;

class LisensiController extends Controller
{
    public function index()
    {
        $lisensi = Lisensi::with(['akun', 'paket'])->latest()->paginate(20);
        return view('admin.lisensi.index', compact('lisensi'));
    }

    public function create()
    {
        $akun  = Akun::where('aktif', true)->orderBy('nama')->get();
        $paket = Paket::where('aktif', true)->orderBy('id')->get();
        return view('admin.lisensi.form', ['lisensi' => new Lisensi(), 'akun' => $akun, 'paket' => $paket]);
    }

    public function store(LisensiRequest $request)
    {
        Lisensi::create([
            ...$request->validated(),
            'license_key'     => $this->generateKey(),
            'status'          => 'aktif',
            'tanggal_berakhir' => $request->tipe === 'lifetime' ? null : $request->tanggal_berakhir,
        ]);
        return redirect()->route('admin.lisensi.index')->with('sukses', 'Lisensi berhasil dibuat.');
    }

    public function show(Lisensi $lisensi)
    {
        $lisensi->load(['akun', 'paket', 'devices', 'aktivasiLog' => fn($q) => $q->latest('terjadi_at')->take(20)]);
        return view('admin.lisensi.show', compact('lisensi'));
    }

    public function toggleStatus(Lisensi $lisensi)
    {
        $baru = $lisensi->status === 'aktif' ? 'suspended' : 'aktif';
        $lisensi->update(['status' => $baru]);
        return back()->with('sukses', 'Status lisensi diperbarui.');
    }

    public function revokeDevice(Lisensi $lisensi, string $deviceId)
    {
        $lisensi->devices()->where('device_id', $deviceId)->update(['aktif' => false]);
        AktivasiLog::create([
            'lisensi_id' => $lisensi->id,
            'device_id'  => $deviceId,
            'aksi'       => 'revoke',
            'hasil'      => 'sukses',
            'ip_address' => request()->ip(),
            'keterangan' => 'Revoke oleh admin.',
        ]);
        return back()->with('sukses', 'Device berhasil di-revoke.');
    }

    private function generateKey(): string
    {
        do {
            $key = strtoupper(implode('-', str_split(Str::random(20), 5)));
        } while (Lisensi::where('license_key', $key)->exists());
        return $key;
    }
}
