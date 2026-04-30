<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 560px; margin: 30px auto; background: #fff; border-radius: 8px; overflow: hidden; }
        .header { background: #1d4ed8; padding: 28px 32px; }
        .header h1 { color: #fff; margin: 0; font-size: 20px; }
        .body { padding: 32px; }
        .key-box { background: #f0f4ff; border: 2px dashed #93c5fd; border-radius: 8px; padding: 18px; text-align: center; margin: 24px 0; }
        .key-box .key { font-family: monospace; font-size: 22px; font-weight: bold; color: #1d4ed8; letter-spacing: 2px; }
        .info-table { width: 100%; border-collapse: collapse; margin: 16px 0; }
        .info-table td { padding: 8px 0; font-size: 14px; color: #444; border-bottom: 1px solid #f0f0f0; }
        .info-table td:first-child { color: #888; width: 140px; }
        .footer { background: #f9fafb; padding: 20px 32px; font-size: 12px; color: #999; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>License Key POS Anda</h1>
    </div>
    <div class="body">
        <p style="color:#333; font-size:15px;">Halo <strong>{{ $akun->nama }}</strong>,</p>
        <p style="color:#555; font-size:14px;">Pembayaran Anda telah dikonfirmasi. Berikut adalah license key untuk mengaktifkan aplikasi POS:</p>

        <div class="key-box">
            <p style="margin:0 0 6px; font-size:12px; color:#888;">LICENSE KEY</p>
            <div class="key">{{ $lisensi->license_key }}</div>
        </div>

        <table class="info-table">
            <tr>
                <td>Paket</td>
                <td><strong>{{ $lisensi->paket->nama }}</strong></td>
            </tr>
            <tr>
                <td>Tipe</td>
                <td>{{ $lisensi->tipe === 'lifetime' ? 'Lifetime' : 'Subscription' }}</td>
            </tr>
            <tr>
                <td>Max Device</td>
                <td>{{ $lisensi->paket->max_device == -1 ? 'Unlimited' : $lisensi->paket->max_device . ' device' }}</td>
            </tr>
            <tr>
                <td>Berlaku Hingga</td>
                <td>{{ $lisensi->tanggal_berakhir ? $lisensi->tanggal_berakhir->format('d F Y') : 'Selamanya' }}</td>
            </tr>
        </table>

        <p style="color:#555; font-size:13px;">
            Masukkan license key di atas saat pertama kali membuka aplikasi POS untuk mengaktifkan.
            Simpan email ini sebagai cadangan.
        </p>
    </div>
    <div class="footer">
        <p>Jika ada pertanyaan, balas email ini atau hubungi support kami.</p>
        <p style="margin:0;">Admin POS &mdash; License Management</p>
    </div>
</div>
</body>
</html>
