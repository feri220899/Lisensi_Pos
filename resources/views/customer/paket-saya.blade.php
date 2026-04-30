<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paket Saya — POS Desktop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 min-h-screen py-12">
<div class="max-w-xl mx-auto px-4">

    <div class="text-center mb-8">
        <a href="{{ route('landing') }}" class="text-xl font-bold text-blue-600">POS Desktop</a>
        <p class="text-sm text-gray-400 mt-1">Cek lisensi Anda</p>
    </div>

    @isset($pakets)
    <div x-data="upgradeModal()" x-show="tampil" x-cloak
         @buka-upgrade.window="buka($event.detail)"
         class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md max-h-[90vh] overflow-y-auto" @click.stop>
            <div class="p-6">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="font-bold text-gray-900">Pilih Paket Baru</h3>
                    <button @click="tutup()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <p class="text-xs text-amber-600 bg-amber-50 border border-amber-200 rounded-xl px-3 py-2 mb-5">
                    Lisensi lama beserta semua perangkat terdaftar akan dihapus setelah pembayaran berhasil.
                </p>

                {{-- Pilih Paket --}}
                <p class="text-xs font-medium text-gray-500 mb-2">PAKET</p>
                <div class="space-y-2 mb-5">
                    @foreach($pakets as $p)
                    <button @click="selectedPaket = {{ $p->id }}; selectedTipe = null"
                            :class="selectedPaket === {{ $p->id }} ? 'border-blue-500 bg-blue-50' : 'border-gray-200 bg-white hover:border-gray-300'"
                            class="w-full text-left border rounded-xl px-4 py-3 transition">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-semibold text-sm text-gray-900">{{ $p->nama }}</p>
                                <p class="text-xs text-gray-400">Max {{ $p->max_device == -1 ? 'Unlimited' : $p->max_device }} device</p>
                            </div>
                            <div class="text-right text-xs text-gray-400">
                                @if($p->support_lifetime && $p->harga_lifetime)
                                <p>Lifetime Rp {{ number_format($p->harga_lifetime, 0, ',', '.') }}</p>
                                @endif
                                @if($p->support_subscription && $p->harga_bulanan)
                                <p>Bulanan Rp {{ number_format($p->harga_bulanan, 0, ',', '.') }}</p>
                                @endif
                            </div>
                        </div>
                    </button>
                    @endforeach
                </div>

                {{-- Pilih Tipe --}}
                <template x-if="selectedPaket">
                    <div>
                        <p class="text-xs font-medium text-gray-500 mb-2">TIPE LISENSI</p>
                        <div class="space-y-2 mb-5">
                            <template x-for="opt in tipeOptions()" :key="opt.value">
                                <button @click="selectedTipe = opt.value"
                                        :class="selectedTipe === opt.value ? 'border-blue-500 bg-blue-50' : 'border-gray-200 bg-white hover:border-gray-300'"
                                        class="w-full text-left border rounded-xl px-4 py-3 transition flex justify-between items-center">
                                    <span class="text-sm font-medium text-gray-900" x-text="opt.label"></span>
                                    <span class="text-sm font-bold text-blue-600" x-text="'Rp ' + opt.harga.toLocaleString('id-ID')"></span>
                                </button>
                            </template>
                        </div>
                    </div>
                </template>

                <button @click="checkout()"
                        :disabled="!selectedPaket || !selectedTipe"
                        class="w-full bg-blue-600 hover:bg-blue-700 disabled:opacity-40 text-white font-semibold py-2.5 rounded-xl text-sm transition">
                    Lanjut ke Pembayaran
                </button>
            </div>
        </div>
    </div>

    <script>
    function upgradeModal() {
        return {
            tampil: false,
            lisensiLamaId: null,
            selectedPaket: null,
            selectedTipe: null,
            akunId: {{ $akun->id ?? 0 }},
            pakets: @json($paketsJs),

            buka(lisensiId) {
                this.lisensiLamaId = lisensiId;
                this.selectedPaket = null;
                this.selectedTipe  = null;
                this.tampil        = true;
            },

            tutup() {
                this.tampil = false;
            },

            tipeOptions() {
                const p = this.pakets.find(x => x.id === this.selectedPaket);
                if (!p) return [];
                const opts = [];
                if (p.support_lifetime && p.harga_lifetime)
                    opts.push({ value: 'lifetime', label: 'Lifetime', harga: p.harga_lifetime });
                if (p.support_subscription) {
                    if (p.harga_bulanan) opts.push({ value: 'subscription_bulanan', label: 'Bulanan', harga: p.harga_bulanan });
                    if (p.harga_tahunan) opts.push({ value: 'subscription_tahunan', label: 'Tahunan', harga: p.harga_tahunan });
                }
                return opts;
            },

            checkout() {
                if (!this.selectedPaket || !this.selectedTipe) return;
                const url = new URL('{{ route("customer.checkout") }}');
                url.searchParams.set('akun_id', this.akunId);
                url.searchParams.set('paket_id', this.selectedPaket);
                url.searchParams.set('tipe', this.selectedTipe);
                url.searchParams.set('lisensi_lama_id', this.lisensiLamaId);
                window.location.href = url.toString();
            },
        }
    }
    </script>
    @endisset

    @if(!isset($akun))
    {{-- Form login cek lisensi --}}
    <div class="bg-white rounded-2xl shadow-sm p-8">
        <h2 class="text-lg font-bold text-gray-900 mb-1">Lihat Paket Saya</h2>
        <p class="text-sm text-gray-400 mb-6">Masukkan email dan password yang digunakan saat pembelian.</p>

        @if($errors->any())
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-xl">
            {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ route('customer.paket-saya.cek') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password" required
                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-xl text-sm transition">
                Lihat Lisensi Saya
            </button>
        </form>
    </div>

    @else
    {{-- Hasil lisensi --}}
    <div class="mb-6 bg-white rounded-2xl shadow-sm p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold text-lg">
            {{ strtoupper(substr($akun->nama, 0, 1)) }}
        </div>
        <div>
            <p class="font-semibold text-gray-900">{{ $akun->nama }}</p>
            <p class="text-sm text-gray-400">{{ $akun->email }}{{ $akun->nama_toko ? ' · ' . $akun->nama_toko : '' }}</p>
        </div>
        <a href="{{ route('customer.paket-saya') }}" class="ml-auto text-xs text-gray-400 hover:underline">Ganti akun</a>
    </div>

    @forelse($lisensi as $l)
    <div x-data="{ buka: {{ $loop->first ? 'true' : 'false' }} }"
         class="bg-white rounded-2xl shadow-sm mb-4 overflow-hidden">

        <button @click="buka = !buka"
                class="w-full px-5 py-4 flex items-center justify-between text-left">
            <div class="flex items-center gap-3">
                <div class="w-2 h-2 rounded-full {{ $l->status === 'aktif' ? 'bg-green-400' : 'bg-red-400' }}"></div>
                <div>
                    <p class="font-semibold text-gray-900 text-sm">{{ $l->paket->nama }}</p>
                    <p class="text-xs text-gray-400">
                        {{ $l->tipe === 'lifetime' ? 'Lifetime' : 'Subscription' }} ·
                        {{ $l->status === 'aktif' ? 'Aktif' : ucfirst($l->status) }}
                    </p>
                </div>
            </div>
            <svg :class="buka ? 'rotate-180' : ''" class="w-4 h-4 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        <div x-show="buka" x-transition class="border-t px-5 pb-5 pt-4">
            <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 mb-4 text-center">
                <p class="text-xs text-gray-400 mb-1">LICENSE KEY</p>
                <p class="font-mono font-bold text-blue-700 text-lg tracking-widest select-all">{{ $l->license_key }}</p>
            </div>

            <div class="grid grid-cols-2 gap-3 text-sm">
                <div class="bg-gray-50 rounded-xl p-3">
                    <p class="text-xs text-gray-400 mb-0.5">Max Device</p>
                    <p class="font-semibold">{{ $l->paket->max_device == -1 ? 'Unlimited' : $l->paket->max_device }}</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-3">
                    <p class="text-xs text-gray-400 mb-0.5">Berlaku Hingga</p>
                    <p class="font-semibold">{{ $l->tanggal_berakhir ? $l->tanggal_berakhir->format('d M Y') : 'Selamanya' }}</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-3">
                    <p class="text-xs text-gray-400 mb-0.5">Mulai</p>
                    <p class="font-semibold">{{ $l->tanggal_mulai?->format('d M Y') ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-3">
                    <p class="text-xs text-gray-400 mb-0.5">Grace Period</p>
                    <p class="font-semibold">{{ $l->paket->grace_period_hari }} hari</p>
                </div>
            </div>

            <p class="text-xs text-gray-400 mt-4 text-center">
                Gunakan license key di atas untuk aktivasi di aplikasi POS.
            </p>

            <button @click="$dispatch('buka-upgrade', {{ $l->id }})"
                    class="mt-4 w-full border border-blue-200 text-blue-600 hover:bg-blue-50 font-medium py-2 rounded-xl text-sm transition">
                Upgrade / Ganti Paket
            </button>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-2xl shadow-sm p-8 text-center">
        <p class="text-gray-400 text-sm">Belum ada lisensi aktif.</p>
        <a href="{{ route('landing') }}#paket" class="mt-3 inline-block text-sm text-blue-600 hover:underline">Beli sekarang &rarr;</a>
    </div>
    @endforelse

    @endif

</div>
</body>
</html>
