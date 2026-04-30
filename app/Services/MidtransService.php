<?php

namespace App\Services;

use App\Models\Akun;
use App\Models\Order;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey    = config('midtrans.server_key');
        Config::$clientKey    = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized  = true;
        Config::$is3ds        = true;
    }

    public function createSnapToken(Order $order, Akun $akun, ?string $midtransOrderId = null): array
    {
        $params = [
            'transaction_details' => [
                'order_id'     => $midtransOrderId ?? $order->order_id,
                'gross_amount' => (int) $order->jumlah,
            ],
            'customer_details' => [
                'first_name' => $akun->nama,
                'email'      => $akun->email,
                'phone'      => $akun->telepon ?? '',
            ],
            'item_details' => [
                [
                    'id'       => $order->paket->slug . '_' . $order->tipe_lisensi,
                    'price'    => (int) $order->jumlah,
                    'quantity' => 1,
                    'name'     => $order->paket->nama . ' — ' . $this->labelTipe($order->tipe_lisensi),
                ],
            ],
            'callbacks' => [
                'finish' => route('customer.order.status', $order->order_id),
            ],
        ];

        $snap = Snap::createTransaction($params);
        return ['token' => $snap->token, 'redirect_url' => $snap->redirect_url];
    }

    public function verifySignature(array $payload): bool
    {
        $expected = hash('sha512',
            $payload['order_id'] .
            $payload['status_code'] .
            $payload['gross_amount'] .
            config('midtrans.server_key')
        );
        return $expected === ($payload['signature_key'] ?? '');
    }

    private function labelTipe(string $tipe): string
    {
        return match ($tipe) {
            'lifetime'             => 'Lifetime',
            'subscription_bulanan' => 'Bulanan',
            'subscription_tahunan' => 'Tahunan',
            default                => $tipe,
        };
    }
}
