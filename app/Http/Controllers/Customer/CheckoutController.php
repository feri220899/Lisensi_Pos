<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Akun;
use App\Models\Lisensi;
use App\Models\Order;
use App\Models\Paket;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function __construct(private MidtransService $midtrans) {}

    public function show(Request $request)
    {
        $akun  = Akun::findOrFail($request->query('akun_id'));
        $paket = Paket::where('aktif', true)->findOrFail($request->query('paket_id'));
        $tipe  = $request->query('tipe', 'subscription_bulanan');
        $harga = $this->harga($paket, $tipe);

        $lisensiLamaId = null;
        if ($request->query('lisensi_lama_id')) {
            $lisensiLama   = Lisensi::where('id', $request->query('lisensi_lama_id'))
                ->where('akun_id', $akun->id)
                ->firstOrFail();
            $lisensiLamaId = $lisensiLama->id;
        }

        $order = Order::create([
            'akun_id'        => $akun->id,
            'paket_id'       => $paket->id,
            'lisensi_lama_id'=> $lisensiLamaId,
            'order_id'       => 'POS-' . strtoupper(Str::random(10)),
            'tipe_lisensi'   => $tipe,
            'jumlah'         => $harga,
            'status'         => 'pending',
        ]);

        $snap = $this->midtrans->createSnapToken($order, $akun);
        $order->update(['midtrans_token' => $snap['token'], 'midtrans_url' => $snap['redirect_url']]);

        $isUpgrade = $lisensiLamaId !== null;

        return view('customer.checkout', compact('order', 'akun', 'paket', 'snap', 'isUpgrade'));
    }

    private function harga(Paket $paket, string $tipe): int
    {
        return (int) match ($tipe) {
            'lifetime'             => $paket->harga_lifetime,
            'subscription_bulanan' => $paket->harga_bulanan,
            'subscription_tahunan' => $paket->harga_tahunan,
        };
    }
}
