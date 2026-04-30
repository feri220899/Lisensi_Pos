@extends('layouts.admin')
@section('title', 'Lisensi')

@section('content')
<div class="flex justify-between items-center mb-4">
    <h2 class="text-sm font-semibold text-gray-700">Semua Lisensi</h2>
    <a href="{{ route('admin.lisensi.create') }}"
       class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-lg">+ Buat Lisensi</a>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
            <tr>
                <th class="px-4 py-3 text-left">License Key</th>
                <th class="px-4 py-3 text-left">Akun</th>
                <th class="px-4 py-3 text-left">Paket</th>
                <th class="px-4 py-3 text-center">Tipe</th>
                <th class="px-4 py-3 text-center">Berakhir</th>
                <th class="px-4 py-3 text-center">Status</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($lisensi as $l)
            <tr>
                <td class="px-4 py-3 font-mono text-xs">{{ $l->license_key }}</td>
                <td class="px-4 py-3">{{ $l->akun->nama }}</td>
                <td class="px-4 py-3">{{ $l->paket->nama }}</td>
                <td class="px-4 py-3 text-center capitalize">{{ $l->tipe }}</td>
                <td class="px-4 py-3 text-center text-gray-500">
                    {{ $l->tanggal_berakhir ? $l->tanggal_berakhir->format('d/m/Y') : 'Lifetime' }}
                </td>
                <td class="px-4 py-3 text-center">
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium
                        {{ $l->status === 'aktif' ? 'bg-green-100 text-green-700' : ($l->status === 'expired' ? 'bg-orange-100 text-orange-700' : 'bg-red-100 text-red-700') }}">
                        {{ $l->status }}
                    </span>
                </td>
                <td class="px-4 py-3 text-right space-x-2">
                    <a href="{{ route('admin.lisensi.show', $l) }}" class="text-blue-600 hover:underline text-xs">Detail</a>
                    <form method="POST" action="{{ route('admin.lisensi.toggle-status', $l) }}" class="inline">
                        @csrf
                        <button type="submit" class="text-yellow-600 hover:underline text-xs">
                            {{ $l->status === 'aktif' ? 'Suspend' : 'Aktifkan' }}
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="px-4 py-6 text-center text-gray-400">Belum ada lisensi</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3 border-t">{{ $lisensi->links() }}</div>
</div>
@endsection
