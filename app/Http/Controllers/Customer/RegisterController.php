<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Akun;
use App\Models\Paket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function landing()
    {
        $paket = Paket::where('aktif', true)->orderBy('id')->get();
        return view('customer.landing', compact('paket'));
    }

    public function showRegister(Request $request)
    {
        $request->validate([
            'paket_id' => 'required|exists:paket,id',
            'tipe'     => 'required|in:lifetime,subscription_bulanan,subscription_tahunan',
        ]);
        $paket  = Paket::where('aktif', true)->findOrFail($request->query('paket_id'));
        $tipe   = $request->query('tipe', 'subscription_bulanan');
        return view('customer.register', compact('paket', 'tipe'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama'      => 'required|string|max:100',
            'email'     => 'required|email|unique:akun,email',
            'telepon'   => 'nullable|string|max:20',
            'nama_toko' => 'nullable|string|max:100',
            'password'  => ['required', 'confirmed', Password::min(6)],
            'paket_id'  => 'required|exists:paket,id',
            'tipe'      => 'required|in:lifetime,subscription_bulanan,subscription_tahunan',
        ]);

        $akun = Akun::create([
            'nama'      => $request->nama,
            'email'     => $request->email,
            'telepon'   => $request->telepon,
            'nama_toko' => $request->nama_toko,
            'password'  => Hash::make($request->password),
            'aktif'     => true,
        ]);

        return redirect()->route('customer.checkout', [
            'paket_id' => $request->paket_id,
            'tipe'     => $request->tipe,
            'akun_id'  => $akun->id,
        ]);
    }
}
