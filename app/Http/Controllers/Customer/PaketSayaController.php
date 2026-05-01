<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Akun;
use App\Models\Order;
use App\Models\Paket;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PaketSayaController extends Controller
{
    public function __construct(private MidtransService $midtrans) {}

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

        session(['paket_saya_akun_id' => $akun->id]);

        $lisensi       = $akun->lisensi()->with('paket')->latest()->get();
        $pendingOrders = $akun->orders()->with('paket')->where('status', 'pending')->latest()->get();
        $pakets        = Paket::where('aktif', true)->where('slug', '!=', 'trial')->get();
        $paketsJs = $pakets->map(fn($p) => [
            'id'                   => $p->id,
            'support_lifetime'     => $p->support_lifetime,
            'support_subscription' => $p->support_subscription,
            'harga_lifetime'       => (int) $p->harga_lifetime,
            'harga_bulanan'        => (int) $p->harga_bulanan,
            'harga_tahunan'        => (int) $p->harga_tahunan,
        ])->values();

        return view('customer.paket-saya', compact('akun', 'lisensi', 'pendingOrders', 'pakets', 'paketsJs'));
    }

    public function gantiMetode(Order $order)
    {
        $akunId = session('paket_saya_akun_id');

        if (!$akunId || $order->akun_id !== $akunId || $order->status !== 'pending') {
            return redirect()->route('customer.paket-saya');
        }

        $midtransOrderId = $order->order_id . '-R' . time();
        $snap = $this->midtrans->createSnapToken($order, $order->akun, $midtransOrderId);

        $order->update([
            'midtrans_token'   => $snap['token'],
            'midtrans_url'     => $snap['redirect_url'],
            'midtrans_payload' => array_merge($order->midtrans_payload ?? [], [
                'midtrans_order_id' => $midtransOrderId,
            ]),
        ]);

        return redirect($snap['redirect_url']);
    }
}
