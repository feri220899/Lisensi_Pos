<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AktivasiLog extends Model
{
    protected $table = 'aktivasi_log';

    public $timestamps = false;

    protected $fillable = [
        'lisensi_id', 'device_id', 'aksi', 'hasil', 'ip_address', 'keterangan', 'terjadi_at',
    ];

    protected $casts = [
        'terjadi_at' => 'datetime',
    ];

    public function lisensi()
    {
        return $this->belongsTo(Lisensi::class);
    }
}
