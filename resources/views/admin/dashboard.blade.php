@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @foreach([
        ['label' => 'Total Akun', 'value' => $stats['total_akun'], 'color' => 'blue'],
        ['label' => 'Total Lisensi', 'value' => $stats['total_lisensi'], 'color' => 'green'],
        ['label' => 'Lisensi Aktif', 'value' => $stats['lisensi_aktif'], 'color' => 'emerald'],
        ['label' => 'Device Aktif', 'value' => $stats['total_device'], 'color' => 'purple'],
    ] as $stat)
    <div class="bg-white rounded-xl shadow-sm p-5">
        <p class="text-sm text-gray-500">{{ $stat['label'] }}</p>
        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stat['value'] }}</p>
    </div>
    @endforeach
</div>

<div class="bg-white rounded-xl shadow-sm p-5">
    <h2 class="text-sm font-semibold text-gray-700 mb-4">Lisensi Terbaru</h2>
    <table class="w-full text-sm">
        <thead>
            <tr class="text-left text-gray-500 border-b">
                <th class="pb-2">License Key</th>
                <th class="pb-2">Akun</th>
                <th class="pb-2">Paket</th>
                <th class="pb-2">Tipe</th>
                <th class="pb-2">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($lisensi_terbaru as $l)
            <tr>
                <td class="py-2 font-mono text-xs">{{ $l->license_key }}</td>
                <td class="py-2">{{ $l->akun->nama }}</td>
                <td class="py-2">{{ $l->paket->nama }}</td>
                <td class="py-2 capitalize">{{ $l->tipe }}</td>
                <td class="py-2">
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium
                        {{ $l->status === 'aktif' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $l->status }}
                    </span>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="py-4 text-center text-gray-400">Belum ada lisensi</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
