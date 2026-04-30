<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pembayaran — POS Desktop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @if($order->status === 'pending' && $order->midtrans_token)
    <script src="https://app.{{ config('midtrans.is_production') ? '' : 'sandbox.' }}midtrans.com/snap/snap.js"
            data-client-key="{{ config('midtrans.client_key') }}"></script>
    @endif
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center py-12">
<div class="w-full max-w-md px-4">

    <div class="text-center mb-6">
        <a href="{{ route('landing') }}" class="text-xl font-bold text-blue-600">POS Desktop</a>
    </div>

    <div x-data="statusPolling()" x-init="init()" class="bg-white rounded-2xl shadow-sm p-8 text-center">

        {{-- Paid --}}
        <template x-if="status === 'paid'">
            <div>
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-gray-900 mb-1">Pembayaran Berhasil!</h2>
                <p class="text-sm text-gray-500 mb-6">License key telah dikirim ke email Anda.</p>

                <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 mb-6">
                    <p class="text-xs text-gray-400 mb-1">LICENSE KEY</p>
                    <p class="font-mono text-lg font-bold text-blue-700 tracking-widest" x-text="licenseKey"></p>
                </div>

                <div class="text-sm text-gray-500 space-y-1 text-left bg-gray-50 rounded-xl p-4 mb-6">
                    <div class="flex justify-between">
                        <span>Paket</span>
                        <span class="font-medium">{{ $order->paket->nama }}</span>
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
                    <div class="flex justify-between">
                        <span>Email</span>
                        <span class="font-medium">{{ $order->akun->email }}</span>
                    </div>
                </div>

                <p class="text-xs text-gray-400 mb-4">Simpan license key ini. Gunakan untuk aktivasi aplikasi POS di komputer Anda.</p>
                <a href="{{ route('customer.paket-saya') }}" class="text-sm text-blue-600 hover:underline">Lihat semua lisensi saya &rarr;</a>
            </div>
        </template>

        {{-- Pending / menunggu --}}
        <template x-if="status === 'pending'">
            <div>
                <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-yellow-500 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-gray-900 mb-1">Menunggu Pembayaran</h2>
                <p class="text-sm text-gray-500 mb-2">Belum selesai bayar? Klik tombol di bawah untuk melanjutkan.</p>
                <p class="text-xs text-gray-400 mb-6">Halaman otomatis update dalam <span x-text="countdown"></span> detik...</p>

                @if($order->status === 'pending')
                <button id="btn-lanjutkan" onclick="lanjutkanBayar()"
                        class="w-full bg-blue-600 hover:bg-blue-700 disabled:opacity-50 text-white font-semibold py-2.5 rounded-xl text-sm transition mb-3">
                    Lanjutkan Pembayaran
                </button>
                @endif

                <div class="text-xs text-gray-400 space-y-1 text-left bg-gray-50 rounded-xl p-3 mt-2">
                    <div class="flex justify-between">
                        <span>Order ID</span><span class="font-mono font-medium">{{ $order->order_id }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Paket</span><span class="font-medium">{{ $order->paket->nama }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Total</span><span class="font-medium">Rp {{ number_format($order->jumlah, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </template>

        {{-- Failed --}}
        <template x-if="status === 'failed'">
            <div>
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-gray-900 mb-1">Pembayaran Gagal</h2>
                <p class="text-sm text-gray-500 mb-6">Transaksi dibatalkan atau ditolak.</p>
                <a href="{{ route('landing') }}#paket"
                   class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-6 py-2.5 rounded-xl">
                    Coba Lagi
                </a>
            </div>
        </template>

    </div>
</div>

<script>
@if($order->status === 'pending' && $order->midtrans_token)
function lanjutkanBayar() {
    const btn = document.getElementById('btn-lanjutkan');
    snap.pay('{{ $order->midtrans_token }}', {
        onSuccess: function() { window.location.reload(); },
        onPending: function() { window.location.reload(); },
        onError:   function() { window.location.reload(); },
        onClose:   function() {},
    });
}
@endif

function statusPolling() {
    return {
        status: '{{ $order->status }}',
        licenseKey: @json($order->lisensi?->license_key ?? ''),
        countdown: 5,
        timer: null,

        init() {
            if (this.status === 'pending') {
                this.startPolling();
            }
        },

        startPolling() {
            this.timer = setInterval(() => {
                this.countdown--;
                if (this.countdown <= 0) {
                    this.countdown = 5;
                    this.poll();
                }
            }, 1000);
        },

        async poll() {
            try {
                const res = await fetch('{{ route('customer.order.cek-status', $order->order_id) }}');
                const data = await res.json();
                this.status = data.status;
                if (data.status === 'paid') {
                    this.licenseKey = data.license_key;
                    clearInterval(this.timer);
                } else if (data.status === 'failed') {
                    clearInterval(this.timer);
                }
            } catch(e) {}
        }
    }
}
</script>
</body>
</html>
