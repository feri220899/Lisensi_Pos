@extends('layouts.admin')
@section('title', 'Detail Lisensi')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

    {{-- Info Lisensi --}}
    <div class="bg-white rounded-xl shadow-sm p-5 space-y-3">
        <h2 class="text-sm font-semibold text-gray-700 border-b pb-2">Info Lisensi</h2>
        <div>
            <p class="text-xs text-gray-400">License Key</p>
            <p class="font-mono text-sm font-semibold">{{ $lisensi->license_key }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-400">Akun</p>
            <p class="text-sm">{{ $lisensi->akun->nama }} <span class="text-gray-400">({{ $lisensi->akun->email }})</span></p>
        </div>
        <div>
            <p class="text-xs text-gray-400">Paket</p>
            <p class="text-sm">{{ $lisensi->paket->nama }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-400">Tipe</p>
            <p class="text-sm capitalize">{{ $lisensi->tipe }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-400">Berlaku</p>
            <p class="text-sm">
                {{ $lisensi->tanggal_mulai?->format('d/m/Y') }} —
                {{ $lisensi->tanggal_berakhir ? $lisensi->tanggal_berakhir->format('d/m/Y') : 'Lifetime' }}
            </p>
        </div>
        <div>
            <p class="text-xs text-gray-400">Status</p>
            <span class="px-2 py-0.5 rounded-full text-xs font-medium
                {{ $lisensi->status === 'aktif' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                {{ $lisensi->status }}
            </span>
        </div>
        <form method="POST" action="{{ route('admin.lisensi.toggle-status', $lisensi) }}" class="pt-2">
            @csrf
            <button type="submit"
                    class="w-full text-sm py-2 rounded-lg border {{ $lisensi->status === 'aktif' ? 'border-red-300 text-red-600 hover:bg-red-50' : 'border-green-300 text-green-600 hover:bg-green-50' }}">
                {{ $lisensi->status === 'aktif' ? 'Suspend Lisensi' : 'Aktifkan Lisensi' }}
            </button>
        </form>
    </div>

    {{-- Device --}}
    <div class="bg-white rounded-xl shadow-sm p-5">
        <h2 class="text-sm font-semibold text-gray-700 border-b pb-2 mb-3">
            Device ({{ $lisensi->devices->where('aktif', true)->count() }} / {{ $lisensi->paket->max_device == -1 ? '∞' : $lisensi->paket->max_device }})
        </h2>
        <div class="space-y-3">
            @forelse($lisensi->devices as $device)
            <div class="flex items-start justify-between text-sm border rounded-lg p-3 {{ $device->aktif ? '' : 'opacity-50' }}">
                <div>
                    <p class="font-medium">{{ $device->nama_device ?? $device->device_id }}</p>
                    <p class="text-xs text-gray-400">{{ $device->os }} — {{ $device->hostname }}</p>
                    <p class="text-xs text-gray-400">Terakhir: {{ $device->last_seen_at?->diffForHumans() ?? '-' }}</p>
                </div>
                @if($device->aktif)
                <form method="POST" action="{{ route('admin.lisensi.revoke-device', [$lisensi, $device->device_id]) }}">
                    @csrf
                    <button type="submit" class="text-xs text-red-500 hover:underline"
                            onclick="return confirm('Revoke device ini?')">Revoke</button>
                </form>
                @else
                <span class="text-xs text-gray-400">Dinonaktifkan</span>
                @endif
            </div>
            @empty
            <p class="text-sm text-gray-400">Belum ada device terdaftar</p>
            @endforelse
        </div>
    </div>

    {{-- Log --}}
    <div class="bg-white rounded-xl shadow-sm p-5">
        <h2 class="text-sm font-semibold text-gray-700 border-b pb-2 mb-3">Log Aktivitas</h2>
        <div class="space-y-2">
            @forelse($lisensi->aktivasiLog as $log)
            <div class="text-xs border-l-2 pl-3 {{ $log->hasil === 'sukses' ? 'border-green-400' : 'border-red-400' }}">
                <p class="font-medium capitalize">{{ $log->aksi }} —
                    <span class="{{ $log->hasil === 'sukses' ? 'text-green-600' : 'text-red-600' }}">{{ $log->hasil }}</span>
                </p>
                <p class="text-gray-400">{{ $log->device_id }}</p>
                @if($log->keterangan)
                <p class="text-gray-400">{{ $log->keterangan }}</p>
                @endif
                <p class="text-gray-300">{{ $log->terjadi_at->format('d/m/Y H:i') }}</p>
            </div>
            @empty
            <p class="text-sm text-gray-400">Belum ada log</p>
            @endforelse
        </div>
    </div>

</div>
@endsection
