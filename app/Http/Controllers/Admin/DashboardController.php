<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Akun;
use App\Models\Device;
use App\Models\Lisensi;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_akun'    => Akun::count(),
            'total_lisensi' => Lisensi::count(),
            'lisensi_aktif' => Lisensi::where('status', 'aktif')->count(),
            'total_device'  => Device::where('aktif', true)->count(),
        ];
        $lisensi_terbaru = Lisensi::with(['akun', 'paket'])->latest()->take(5)->get();
        return view('admin.dashboard', compact('stats', 'lisensi_terbaru'));
    }
}
