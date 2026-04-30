<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Akun;
use App\Models\Paket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PaketSayaController extends Controller
{
    public function show()
    {
        return view('customer.paket-saya');
    }

    public function cek(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $akun = Akun::where('email', $request->email)
            ->where('aktif', true)
            ->first();

        if (!$akun || !Hash::check($request->password, $akun->password)) {
            return back()->withErrors(['email' => 'Email atau password salah.'])->withInput();
        }

        $lisensi = $akun->lisensi()->with('paket')->latest()->get();
        $pakets  = Paket::where('aktif', true)->get();
        $paketsJs = $pakets->map(fn($p) => [
            'id'                   => $p->id,
            'support_lifetime'     => $p->support_lifetime,
            'support_subscription' => $p->support_subscription,
            'harga_lifetime'       => (int) $p->harga_lifetime,
            'harga_bulanan'        => (int) $p->harga_bulanan,
            'harga_tahunan'        => (int) $p->harga_tahunan,
        ])->values();

        return view('customer.paket-saya', compact('akun', 'lisensi', 'pakets', 'paketsJs'));
    }
}
