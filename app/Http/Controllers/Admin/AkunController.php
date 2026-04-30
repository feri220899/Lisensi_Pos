<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AkunRequest;
use App\Models\Akun;
use Illuminate\Support\Facades\Hash;

class AkunController extends Controller
{
    public function index()
    {
        $akun = Akun::withCount('lisensi')->latest()->paginate(20);
        return view('admin.akun.index', compact('akun'));
    }

    public function create()
    {
        return view('admin.akun.form', ['akun' => new Akun()]);
    }

    public function store(AkunRequest $request)
    {
        Akun::create([
            ...$request->except('password'),
            'password' => Hash::make($request->password),
            'aktif'    => $request->boolean('aktif', true),
        ]);
        return redirect()->route('admin.akun.index')->with('sukses', 'Akun berhasil dibuat.');
    }

    public function edit(Akun $akun)
    {
        return view('admin.akun.form', compact('akun'));
    }

    public function update(AkunRequest $request, Akun $akun)
    {
        $data = $request->except('password');
        $data['aktif'] = $request->boolean('aktif', true);
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        $akun->update($data);
        return redirect()->route('admin.akun.index')->with('sukses', 'Akun berhasil diperbarui.');
    }

    public function destroy(Akun $akun)
    {
        $akun->delete();
        return redirect()->route('admin.akun.index')->with('sukses', 'Akun berhasil dihapus.');
    }
}
