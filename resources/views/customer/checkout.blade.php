<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout — POS Desktop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ config('midtrans.client_key') }}"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center py-12">
<div class="w-full max-w-md px-4" x-data>

    <div class="text-center mb-6">
        <a href="{{ route('landing') }}" class="text-xl font-bold text-blue-600">POS Desktop</a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-6 mb-4">
        <h2 class="font-semibold text-gray-900 mb-4">Ringkasan Order</h2>
        <div class="space-y-2 text-sm text-gray-600">
            <div class="flex justify-between">
                <span>Nama</span><span class="font-medium">{{ $akun->nama }}</span>
            </div>
            <div class="flex justify-between">
                <span>Email</span><span class="font-medium">{{ $akun->email }}</span>
            </div>
            <div class="flex justify-between">
                <span>Paket</span><span class="font-medium">{{ $paket->nama }}</span>
            </div>
            <div class="flex justify-between">
                <span>Tipe</span>
                <span class="font-medium">
                    {{ match($order->tipe_lisensi) {
                        'lifetime' => 'Lifetime',
                        'subscription_bulanan' => 'Bulanan',
                        'subscription_tahunan' => 'Tahunan',
                    } }}
                </span>
            </div>
            <div class="border-t pt-2 flex justify-between font-semibold text-gray-900">
                <span>Total</span>
                <span>Rp {{ number_format($order->jumlah, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    @if($isUpgrade ?? false)
    <div class="mb-4 bg-amber-50 border border-amber-200 text-amber-700 text-sm px-4 py-3 rounded-xl">
        Lisensi lama beserta semua perangkat terdaftar akan dihapus setelah pembayaran berhasil.
    </div>
    @endif

    <button id="pay-btn" onclick="bayar()"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl text-sm transition">
        Bayar Sekarang
    </button>

    <p class="text-xs text-center text-gray-400 mt-4">
        Setelah pembayaran berhasil, license key akan dikirim ke email Anda.
    </p>
</div>

<script>
    var statusUrl = '{{ route('customer.order.status', $order->order_id) }}';

    function bayar() {
        snap.pay('{{ $snap['token'] }}', {
            onSuccess: function(result) {
                window.location.href = statusUrl;
            },
            onPending: function(result) {
                window.location.href = statusUrl;
            },
            onError: function(result) {
                window.location.href = statusUrl;
            },
            onClose: function() {
                window.location.href = statusUrl;
            },
        });
    }
</script>
</body>
</html>
