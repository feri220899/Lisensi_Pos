<?php

use App\Http\Controllers\Api\LisensiController;
use App\Http\Controllers\Api\TrialController;
use App\Http\Controllers\Customer\WebhookController;
use Illuminate\Support\Facades\Route;

Route::prefix('lisensi')->group(function () {
    Route::post('/aktivasi',   [LisensiController::class, 'aktivasi']);
    Route::post('/validasi',   [LisensiController::class, 'validasi']);
    Route::post('/deaktivasi', [LisensiController::class, 'deaktivasi']);
});

Route::post('/trial/request', [TrialController::class, 'request']);

Route::post('/midtrans/webhook', [WebhookController::class, 'handle'])->name('midtrans.webhook');
