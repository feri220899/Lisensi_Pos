<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar — POS Desktop</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center py-12">
<div class="w-full max-w-md px-4">

    <div class="text-center mb-6">
        <a href="{{ route('landing') }}" class="text-xl font-bold text-blue-600">POS Desktop</a>
        <p class="text-sm text-gray-400 mt-1">Buat akun untuk melanjutkan pembelian</p>
    </div>

    {{-- Ringkasan paket --}}
    <div class="bg-blue-50 border border-blue-100 rounded-xl px-4 py-3 mb-6 flex justify-between items-center text-sm">
        <div>
            <p class="font-semibold text-blue-800">{{ $paket->nama }}</p>
            <p class="text-blue-500 text-xs">
                {{ match($tipe) {
                    'lifetime' => 'Lifetime — Rp ' . number_format($paket->harga_lifetime, 0, ',', '.'),
                    'subscription_bulanan' => 'Bulanan — Rp ' . number_format($paket->harga_bulanan, 0, ',', '.') . '/bln',
                    'subscription_tahunan' => 'Tahunan — Rp ' . number_format($paket->harga_tahunan, 0, ',', '.') . '/thn',
                } }}
            </p>
        </div>
        <a href="{{ route('landing') }}#paket" class="text-xs text-blue-400 hover:underline">Ganti</a>
    </div>

    @if($errors->any())
    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-xl">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $e)
            <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('customer.register.post') }}" class="bg-white rounded-2xl shadow-sm p-6 space-y-4">
        @csrf
        <input type="hidden" name="paket_id" value="{{ $paket->id }}">
        <input type="hidden" name="tipe" value="{{ $tipe }}">

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
            <input type="text" name="nama" value="{{ old('nama') }}" required
                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required
                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">No. Telepon</label>
                <input type="text" name="telepon" value="{{ old('telepon') }}"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Toko</label>
                <input type="text" name="nama_toko" value="{{ old('nama_toko') }}"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input type="password" name="password" required
                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" required
                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-xl text-sm transition mt-2">
            Lanjut ke Pembayaran &rarr;
        </button>
    </form>
</div>
</body>
</html>
