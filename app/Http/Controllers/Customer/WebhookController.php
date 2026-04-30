<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\LisensiService;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function __construct(
        private MidtransService $midtrans,
        private LisensiService $lisensiService,
    ) {}

    public function handle(Request $request)
    {
        try {
            $payload = $request->all();

            if (!$this->midtrans->verifySignature($payload)) {
                Log::warning('Midtrans webhook: signature tidak valid', $payload);
                return response()->json(['message' => 'Invalid signature'], 403);
            }

            $orderId           = $payload['order_id'];
            $transactionStatus = $payload['transaction_status'];
            $fraudStatus       = $payload['fraud_status'] ?? null;

            $order = Order::with(['akun', 'paket'])->where('order_id', $orderId)->first()
                ?? Order::with(['akun', 'paket'])
                    ->whereJsonContains('midtrans_payload->midtrans_order_id', $orderId)
                    ->first();

            if (!$order) {
                Log::warning('Midtrans webhook: order tidak ditemukan', ['order_id' => $orderId]);
                return response()->json(['message' => 'Order tidak ditemukan'], 404);
            }

            if ($order->status === 'paid') {
                return response()->json(['message' => 'Sudah diproses']);
            }

            $order->update(['midtrans_payload' => $payload]);
            if ($transactionStatus === 'capture' && $fraudStatus === 'accept') {
                $this->lisensiService->generateDariOrder($order);
            } elseif ($transactionStatus === 'settlement') {
                $this->lisensiService->generateDariOrder($order);
            } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
                $order->update(['status' => 'failed']);
            }

        } catch (\Exception $e) {
            Log::error('Midtrans webhook error: ' . $e->getMessage());
            return response()->json(['message' => 'Error'], 500);
        }

        return response()->json(['message' => 'OK']);
    }
}
