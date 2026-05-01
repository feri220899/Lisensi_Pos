<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Akun;
use App\Services\TrialService;
use Illuminate\Http\Request;

class TrialWebController extends Controller
{
    public function __construct(private TrialService $trialService) {}

    public function show(Request $request)
    {
        $akun = $request->query('akun_id')
            ? Akun::findOrFail($request->query('akun_id'))
            : null;

        return view('customer.trial', compact('akun'));
    }

    public function store(Request $request)
    {
        if ($request->filled('akun_id')) {
            $akun = Akun::findOrFail($request->akun_id);

            if ($this->trialService->sudahTrialEmail($akun->email)) {
                return back()->withErrors(['email' => 'Akun ini sudah pernah menggunakan trial.']);
            }

            $lisensi = $this->trialService->buatDariAkun($akun);
        } else {
            $request->validate(['email' => 'required|email']);

            if ($this->trialService->sudahTrialEmail($request->email)) {
                return back()->withErrors(['email' => 'Email ini sudah pernah menggunakan trial.'])->withInput();
            }

            $lisensi = $this->trialService->buat($request->email);
        }

        return view('customer.trial-success', [
            'license_key'    => $lisensi->license_key,
            'berlaku_hingga' => $lisensi->tanggal_berakhir->format('d M Y'),
        ]);
    }
}
