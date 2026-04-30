<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paket extends Model
{
    protected $table = 'paket';

    protected $fillable = [
        'nama', 'slug', 'max_device', 'grace_period_hari',
        'support_lifetime', 'support_subscription',
        'harga_lifetime', 'harga_bulanan', 'harga_tahunan', 'aktif',
    ];

    protected $casts = [
        'support_lifetime'    => 'boolean',
        'support_subscription' => 'boolean',
        'aktif'               => 'boolean',
    ];

    public function lisensi()
    {
        return $this->hasMany(Lisensi::class);
    }

    public function isUnlimited(): bool
    {
        return $this->max_device === -1;
    }
}
