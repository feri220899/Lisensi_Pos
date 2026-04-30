<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $table = 'device';

    protected $fillable = [
        'lisensi_id', 'device_id', 'nama_device', 'os', 'hostname', 'aktif', 'last_seen_at',
    ];

    protected $casts = [
        'aktif'        => 'boolean',
        'last_seen_at' => 'datetime',
    ];

    public function lisensi()
    {
        return $this->belongsTo(Lisensi::class);
    }
}
