<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Desktop — Solusi Kasir Offline untuk UMKM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-white text-gray-800">

{{-- Navbar --}}
<nav class="border-b border-gray-100 px-6 py-4 flex items-center justify-between max-w-6xl mx-auto">
    <span class="text-xl font-bold text-blue-600">POS Desktop</span>
    <div class="flex items-center gap-4">
        <a href="{{ route('customer.paket-saya') }}" class="text-sm text-gray-500 hover:text-blue-600">Lisensi Saya</a>
        <a href="{{ route('admin.login') }}" class="text-sm text-gray-400 hover:text-gray-600">Admin</a>
    </div>
</nav>

{{-- Hero --}}
<section class="max-w-6xl mx-auto px-6 pt-20 pb-16 text-center">
    <span class="inline-block bg-blue-50 text-blue-600 text-xs font-semibold px-3 py-1 rounded-full mb-4">Offline-First · Sekali Bayar</span>
    <h1 class="text-4xl lg:text-5xl font-extrabold text-gray-900 leading-tight mb-4">
        Kasir Desktop Ringan<br>untuk Warung & Toko Kecil
    </h1>
    <p class="text-lg text-gray-500 max-w-xl mx-auto mb-8">
        Tidak perlu internet saat bertransaksi. Data tersimpan di komputer Anda. Cepat, andal, dan terjangkau.
    </p>
    <a href="#paket" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-3 rounded-full text-sm transition">
        Lihat Paket &darr;
    </a>
</section>

{{-- Fitur --}}
<section class="bg-gray-50 py-16">
    <div class="max-w-6xl mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-8">
        @foreach([
            ['icon' => '📴', 'title' => 'Offline-First', 'desc' => 'Transaksi tetap berjalan tanpa koneksi internet. Data aman di komputer Anda.'],
            ['icon' => '⚡', 'title' => 'Super Cepat', 'desc' => 'Database lokal SQLite memastikan setiap transaksi diproses dalam milidetik.'],
            ['icon' => '💸', 'title' => 'Harga Terjangkau', 'desc' => 'Pilih lifetime atau subscription bulanan/tahunan sesuai kebutuhan bisnis Anda.'],
        ] as $f)
        <div class="text-center">
            <div class="text-4xl mb-3">{{ $f['icon'] }}</div>
            <h3 class="font-semibold text-gray-900 mb-1">{{ $f['title'] }}</h3>
            <p class="text-sm text-gray-500">{{ $f['desc'] }}</p>
        </div>
        @endforeach
    </div>
</section>

{{-- Paket --}}
<section id="paket" class="max-w-6xl mx-auto px-6 py-20">
    <h2 class="text-3xl font-bold text-center text-gray-900 mb-2">Pilih Paket</h2>
    <p class="text-center text-gray-500 text-sm mb-12">Semua paket tersedia dalam tipe Lifetime, Bulanan, dan Tahunan</p>

    <div x-data="{ tipe: 'subscription_bulanan' }">

        {{-- Toggle tipe --}}
        <div class="flex justify-center mb-10">
            <div class="inline-flex bg-gray-100 rounded-full p-1">
                @foreach(['subscription_bulanan' => 'Bulanan', 'subscription_tahunan' => 'Tahunan', 'lifetime' => 'Lifetime'] as $val => $label)
                <button @click="tipe = '{{ $val }}'"
                        :class="tipe === '{{ $val }}' ? 'bg-white shadow text-blue-600 font-semibold' : 'text-gray-500'"
                        class="px-5 py-2 rounded-full text-sm transition">{{ $label }}</button>
                @endforeach
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($paket as $p)
            <div class="border rounded-2xl p-6 flex flex-col {{ $p->slug === 'pro' ? 'border-blue-500 shadow-lg ring-2 ring-blue-100' : 'border-gray-200' }}">
                @if($p->slug === 'pro')
                <div class="text-xs font-semibold text-blue-600 bg-blue-50 px-3 py-1 rounded-full self-start mb-3">Paling Populer</div>
                @endif
                <h3 class="text-xl font-bold text-gray-900">{{ $p->nama }}</h3>
                <p class="text-sm text-gray-400 mt-1 mb-4">
                    {{ $p->max_device == -1 ? 'Unlimited device' : 'Maks ' . $p->max_device . ' device' }}
                </p>

                <div class="mb-6">
                    <div x-show="tipe === 'subscription_bulanan'">
                        <span class="text-3xl font-extrabold text-gray-900">Rp {{ number_format($p->harga_bulanan, 0, ',', '.') }}</span>
                        <span class="text-sm text-gray-400">/bulan</span>
                    </div>
                    <div x-show="tipe === 'subscription_tahunan'">
                        <span class="text-3xl font-extrabold text-gray-900">Rp {{ number_format($p->harga_tahunan, 0, ',', '.') }}</span>
                        <span class="text-sm text-gray-400">/tahun</span>
                    </div>
                    <div x-show="tipe === 'lifetime'">
                        <span class="text-3xl font-extrabold text-gray-900">Rp {{ number_format($p->harga_lifetime, 0, ',', '.') }}</span>
                        <span class="text-sm text-gray-400"> sekali bayar</span>
                    </div>
                </div>

                <ul class="text-sm text-gray-600 space-y-2 mb-8 flex-1">
                    <li class="flex items-center gap-2"><span class="text-green-500">✓</span> {{ $p->max_device == -1 ? 'Unlimited' : $p->max_device }} device aktif</li>
                    <li class="flex items-center gap-2"><span class="text-green-500">✓</span> Grace period {{ $p->grace_period_hari }} hari</li>
                    <li class="flex items-center gap-2"><span class="text-green-500">✓</span> Update aplikasi gratis</li>
                    <li class="flex items-center gap-2"><span class="text-green-500">✓</span> Support via email</li>
                </ul>

                <a :href="`{{ route('customer.register') }}?paket_id={{ $p->id }}&tipe=${tipe}`"
                   class="block text-center py-3 rounded-xl text-sm font-semibold transition
                   {{ $p->slug === 'pro' ? 'bg-blue-600 hover:bg-blue-700 text-white' : 'border border-blue-600 text-blue-600 hover:bg-blue-50' }}">
                    Beli Sekarang
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Footer --}}
<footer class="border-t border-gray-100 py-8 text-center text-sm text-gray-400">
    &copy; {{ date('Y') }} POS Desktop. Semua hak dilindungi.
</footer>

</body>
</html>
