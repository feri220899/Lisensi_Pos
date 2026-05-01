<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lisensi extends Model
{
    protected $table = 'lisensi';

    protected $fillable = [
        'akun_id', 'paket_id', 'license_key', 'tipe', 'status',
        'tanggal_mulai', 'tanggal_berakhir', 'last_validated_at', 'catatan',
    ];

    protected $casts = [
        'tanggal_mulai'      => 'datetime',
        'tanggal_berakhir'   => 'datetime',
        'last_validated_at'  => 'datetime',
    ];

    public function akun()
    {
        return $this->belongsTo(Akun::class);
    }

    public function paket()
    {
        return $this->belongsTo(Paket::class);
    }

    public function devices()
    {
        return $this->hasMany(Device::class);
    }

    public function aktivasiLog()
    {
        return $this->hasMany(AktivasiLog::class);
    }

    public function isAktif(): bool
    {
        if ($this->status !== 'aktif') return false;
        if ($this->tipe === 'lifetime') return true;
        if ($this->tipe === 'trial') return now()->lte($this->tanggal_berakhir);

        $grace = $this->paket->grace_period_hari ?? 1;
        return now()->lte($this->tanggal_berakhir->addDays($grace));
    }

    public function deviceAktifCount(): int
    {
        return $this->devices()->where('aktif', true)->count();
    }

    public function bisaTambahDevice(): bool
    {
        if ($this->paket->isUnlimited()) return true;
        return $this->deviceAktifCount() < $this->paket->max_device;
    }
}
