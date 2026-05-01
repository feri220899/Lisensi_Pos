<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\TrialRequest;
use App\Services\TrialService;
use Illuminate\Http\JsonResponse;

class TrialController extends Controller
{
    public function __construct(private TrialService $trialService) {}

    public function request(TrialRequest $request): JsonResponse
    {
        if ($this->trialService->sudahTrialEmail($request->email)) {
            return response()->json([
                'success' => false,
                'pesan'   => 'Email ini sudah pernah menggunakan trial.',
            ], 422);
        }

        $lisensi = $this->trialService->buat($request->email);

        return response()->json([
            'success'     => true,
            'license_key' => $lisensi->license_key,
            'berlaku_hingga' => $lisensi->tanggal_berakhir->toDateString(),
            'pesan'       => 'Trial berhasil diaktifkan. Berlaku ' . TrialService::DURASI_HARI . ' hari.',
        ]);
    }
}
