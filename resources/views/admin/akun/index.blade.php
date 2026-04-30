@extends('layouts.admin')
@section('title', 'Akun Customer')

@section('content')
<div class="flex justify-between items-center mb-4">
    <h2 class="text-sm font-semibold text-gray-700">Semua Akun</h2>
    <a href="{{ route('admin.akun.create') }}"
       class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-lg">+ Tambah Akun</a>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
            <tr>
                <th class="px-4 py-3 text-left">Nama</th>
                <th class="px-4 py-3 text-left">Email</th>
                <th class="px-4 py-3 text-left">Nama Toko</th>
                <th class="px-4 py-3 text-center">Lisensi</th>
                <th class="px-4 py-3 text-center">Status</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($akun as $a)
            <tr>
                <td class="px-4 py-3">{{ $a->nama }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $a->email }}</td>
                <td class="px-4 py-3">{{ $a->nama_toko ?? '-' }}</td>
                <td class="px-4 py-3 text-center">{{ $a->lisensi_count }}</td>
                <td class="px-4 py-3 text-center">
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium
                        {{ $a->aktif ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                        {{ $a->aktif ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                <td class="px-4 py-3 text-right space-x-2">
                    <a href="{{ route('admin.akun.edit', $a) }}" class="text-blue-600 hover:underline text-xs">Edit</a>
                    <form method="POST" action="{{ route('admin.akun.destroy', $a) }}" class="inline"
                          onsubmit="return confirm('Hapus akun ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-500 hover:underline text-xs">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-4 py-6 text-center text-gray-400">Belum ada akun</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3 border-t">{{ $akun->links() }}</div>
</div>
@endsection
