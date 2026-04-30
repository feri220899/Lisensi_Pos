@extends('layouts.admin')
@section('title', 'Device')

@section('content')
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
            <tr>
                <th class="px-4 py-3 text-left">Device ID</th>
                <th class="px-4 py-3 text-left">Nama / OS</th>
                <th class="px-4 py-3 text-left">Akun</th>
                <th class="px-4 py-3 text-left">Paket</th>
                <th class="px-4 py-3 text-center">Status</th>
                <th class="px-4 py-3 text-center">Terakhir Online</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($devices as $d)
            <tr class="{{ $d->aktif ? '' : 'opacity-50' }}">
                <td class="px-4 py-3 font-mono text-xs">{{ Str::limit($d->device_id, 20) }}</td>
                <td class="px-4 py-3">
                    <p>{{ $d->nama_device ?? '-' }}</p>
                    <p class="text-xs text-gray-400">{{ $d->os }} — {{ $d->hostname }}</p>
                </td>
                <td class="px-4 py-3">{{ $d->lisensi->akun->nama }}</td>
                <td class="px-4 py-3">{{ $d->lisensi->paket->nama }}</td>
                <td class="px-4 py-3 text-center">
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium
                        {{ $d->aktif ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                        {{ $d->aktif ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                <td class="px-4 py-3 text-center text-gray-400 text-xs">
                    {{ $d->last_seen_at?->diffForHumans() ?? '-' }}
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-4 py-6 text-center text-gray-400">Belum ada device</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3 border-t">{{ $devices->links() }}</div>
</div>
@endsection
