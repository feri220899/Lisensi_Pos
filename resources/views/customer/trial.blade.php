<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coba Gratis — POS Desktop</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center py-12">
<div class="w-full max-w-sm px-4">

    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Coba Gratis 7 Hari</h1>
        <p class="text-gray-500 mt-2">Aktifkan trial untuk mendapatkan license key</p>
    </div>

    <div class="bg-white rounded-2xl shadow p-8">

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 mb-4 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        @if ($akun)
            {{-- Akun sudah ada, tampilkan konfirmasi --}}
            <div class="mb-6 text-center">
                <p class="text-gray-600 text-sm">Akun terdaftar:</p>
                <p class="font-semibold text-gray-900 mt-1">{{ $akun->email }}</p>
            </div>

            <form method="POST" action="{{ route('customer.trial.post') }}">
                @csrf
                <input type="hidden" name="akun_id" value="{{ $akun->id }}" />
                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition">
                    Aktifkan Trial Gratis
                </button>
            </form>
        @else
            {{-- Akses langsung, tampilkan form email --}}
            <form method="POST" action="{{ route('customer.trial.post') }}" class="space-y-4">
                @csrf
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="email@contoh.com" />

                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition">
                    Mulai Trial Gratis
                </button>
            </form>
        @endif

        <p class="text-center text-xs text-gray-400 mt-6">
            1 trial per email &bull; Berlaku 7 hari &bull; 1 perangkat
        </p>
    </div>

</div>
</body>
</html>
