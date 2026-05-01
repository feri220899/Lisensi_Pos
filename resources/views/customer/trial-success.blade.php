<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trial Aktif — POS Desktop</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center py-12">
<div class="w-full max-w-md px-4">

    <div class="bg-white rounded-2xl shadow p-8 text-center">
        <div class="text-green-500 text-5xl mb-4">✓</div>
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Trial Berhasil Diaktifkan!</h1>
        <p class="text-gray-500 text-sm mb-6">Berlaku hingga {{ $berlaku_hingga }}</p>

        <div class="bg-gray-100 rounded-lg px-4 py-3 mb-6">
            <p class="text-xs text-gray-500 mb-1">License Key Anda</p>
            <p class="text-lg font-mono font-bold tracking-widest text-gray-900">{{ $license_key }}</p>
        </div>

        <p class="text-sm text-gray-500">
            Salin license key di atas dan masukkan ke aplikasi POS Desktop untuk mulai menggunakan.
        </p>
    </div>

</div>
</body>
</html>
