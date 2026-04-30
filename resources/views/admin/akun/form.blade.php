@extends('layouts.admin')
@section('title', $akun->exists ? 'Edit Akun' : 'Tambah Akun')

@section('content')
<div class="max-w-lg">
    <form method="POST" action="{{ $akun->exists ? route('admin.akun.update', $akun) : route('admin.akun.store') }}">
        @csrf
        @if($akun->exists) @method('PUT') @endif

        <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                <input type="text" name="nama" value="{{ old('nama', $akun->nama) }}" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('nama')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email', $akun->email) }}" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Telepon</label>
                <input type="text" name="telepon" value="{{ old('telepon', $akun->telepon) }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Toko</label>
                <input type="text" name="nama_toko" value="{{ old('nama_toko', $akun->nama_toko) }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Password {{ $akun->exists ? '(kosongkan jika tidak diubah)' : '' }}
                </label>
                <input type="password" name="password"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" name="aktif" id="aktif" value="1"
                       {{ old('aktif', $akun->aktif ?? true) ? 'checked' : '' }}
                       class="rounded border-gray-300">
                <label for="aktif" class="text-sm text-gray-700">Akun Aktif</label>
            </div>
        </div>

        <div class="mt-4 flex gap-3">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-5 py-2 rounded-lg">
                {{ $akun->exists ? 'Simpan Perubahan' : 'Buat Akun' }}
            </button>
            <a href="{{ route('admin.akun.index') }}" class="text-sm text-gray-500 hover:underline py-2">Batal</a>
        </div>
    </form>
</div>
@endsection
