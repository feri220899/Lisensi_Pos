<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'akun_id', 'paket_id', 'lisensi_id', 'lisensi_lama_id', 'order_id', 'tipe_lisensi',
        'jumlah', 'status', 'midtrans_token', 'midtrans_url', 'midtrans_payload', 'paid_at',
    ];

    protected $casts = [
        'midtrans_payload' => 'array',
        'paid_at'          => 'datetime',
    ];

    public function akun()
    {
        return $this->belongsTo(Akun::class);
    }

    public function paket()
    {
        return $this->belongsTo(Paket::class);
    }

    public function lisensi()
    {
        return $this->belongsTo(Lisensi::class);
    }
}
