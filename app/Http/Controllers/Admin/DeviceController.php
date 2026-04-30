<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Device;

class DeviceController extends Controller
{
    public function index()
    {
        $devices = Device::with(['lisensi.akun', 'lisensi.paket'])->latest()->paginate(20);
        return view('admin.device.index', compact('devices'));
    }
}
