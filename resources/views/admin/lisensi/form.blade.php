@extends('layouts.admin')
@section('title', 'Buat Lisensi')

@section('content')
<div class="max-w-lg" x-data="{ tipe: '{{ old('tipe', 'subscription') }}' }">
    <form method="POST" action="{{ route('admin.lisensi.store') }}">
        @csrf
        <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Akun Customer</label>
                <select name="akun_id" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Pilih Akun --</option>
                    @foreach($akun as $a)
                        <option value="{{ $a->id }}" {{ old('akun_id') == $a->id ? 'selected' : '' }}>
                            {{ $a->nama }} ({{ $a->email }})
                        </option>
                    @endforeach
                </select>
                @error('akun_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Paket</label>
                <select name="paket_id" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Pilih Paket --</option>
                    @foreach($paket as $p)
                        <option value="{{ $p->id }}" {{ old('paket_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->nama }} (maks {{ $p->max_device == -1 ? 'unlimited' : $p->max_device }} device)
                        </option>
                    @endforeach
                </select>
                @error('paket_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Lisensi</label>
                <select name="tipe" x-model="tipe" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="subscription">Subscription</option>
                    <option value="lifetime">Lifetime</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai', date('Y-m-d')) }}" required
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div x-show="tipe === 'subscription'" x-transition>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Berakhir</label>
                <input type="date" name="tanggal_berakhir" value="{{ old('tanggal_berakhir') }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('tanggal_berakhir')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Catatan (opsional)</label>
                <textarea name="catatan" rows="2"
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('catatan') }}</textarea>
            </div>
        </div>

        <div class="mt-4 flex gap-3">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-5 py-2 rounded-lg">
                Generate Lisensi
            </button>
            <a href="{{ route('admin.lisensi.index') }}" class="text-sm text-gray-500 hover:underline py-2">Batal</a>
        </div>
    </form>
</div>
@endsection
