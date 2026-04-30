<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\MidtransService;
use Illuminate\Http\Request;

class OrderStatusController extends Controller
{
    public function __construct(private MidtransService $midtrans) {}
    public function show(string $orderId)
    {
        $order = Order::with(['akun', 'paket', 'lisensi'])
            ->where('order_id', $orderId)
            ->firstOrFail();

        return view('customer.order-status', compact('order'));
    }

    public function cekStatus(string $orderId)
    {
        $order = Order::with('lisensi')
            ->where('order_id', $orderId)
            ->firstOrFail();

        return response()->json([
            'status'      => $order->status,
            'license_key' => $order->lisensi?->license_key,
        ]);
    }

    public function tokenBaru(string $orderId)
    {
        $order = Order::with(['akun', 'paket'])
            ->where('order_id', $orderId)
            ->where('status', 'pending')
            ->firstOrFail();

        $midtransOrderId = $order->order_id . '-R' . time();

        $snap = $this->midtrans->createSnapToken($order, $order->akun, $midtransOrderId);

        $order->update([
            'midtrans_token'   => $snap['token'],
            'midtrans_payload' => array_merge($order->midtrans_payload ?? [], [
                'midtrans_order_id' => $midtransOrderId,
            ]),
        ]);

        return response()->json(['token' => $snap['token']]);
    }
}
