<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Order;

class Akun extends Authenticatable
{
    protected $table = 'akun';

    protected $fillable = [
        'nama', 'email', 'telepon', 'nama_toko', 'password', 'aktif',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'aktif'             => 'boolean',
        'email_verified_at' => 'datetime',
    ];

    public function lisensi()
    {
        return $this->hasMany(Lisensi::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
