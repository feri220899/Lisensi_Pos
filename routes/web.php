<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AkunController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DeviceController;
use App\Http\Controllers\Admin\LisensiController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Customer\OrderStatusController;
use App\Http\Controllers\Customer\PaketSayaController;
use App\Http\Controllers\Customer\RegisterController;
use App\Http\Controllers\Customer\WebhookController;
use Illuminate\Support\Facades\Route;

Route::get('/', [RegisterController::class, 'landing'])->name('landing');

Route::prefix('beli')->name('customer.')->group(function () {
    Route::get('daftar',                    [RegisterController::class, 'showRegister'])->name('register');
    Route::post('daftar',                   [RegisterController::class, 'register'])->name('register.post');
    Route::get('checkout',                  [CheckoutController::class, 'show'])->name('checkout');
    Route::get('order/{orderId}',              [OrderStatusController::class, 'show'])->name('order.status');
    Route::get('order/{orderId}/cek-status',   [OrderStatusController::class, 'cekStatus'])->name('order.cek-status');
    Route::post('order/{orderId}/token-baru',  [OrderStatusController::class, 'tokenBaru'])->name('order.token-baru');
});

Route::get('paket-saya',  [PaketSayaController::class, 'show'])->name('customer.paket-saya');
Route::post('paket-saya', [PaketSayaController::class, 'cek'])->name('customer.paket-saya.cek');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login',  [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');

    Route::middleware('admin.auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');

        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('akun', AkunController::class)->except(['show']);

        Route::resource('lisensi', LisensiController::class)->except(['edit', 'update', 'destroy']);
        Route::post('lisensi/{lisensi}/toggle-status', [LisensiController::class, 'toggleStatus'])->name('lisensi.toggle-status');
        Route::post('lisensi/{lisensi}/revoke-device/{deviceId}', [LisensiController::class, 'revokeDevice'])->name('lisensi.revoke-device');

        Route::get('device', [DeviceController::class, 'index'])->name('device.index');
    });
});
