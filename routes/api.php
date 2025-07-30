<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\PendaftaranController;

// Rute Publik
Route::post('/register', [AuthController::class, 'register']);
Route::post('/register-rpl', [AuthController::class, 'registerRpl']);
Route::post('/register-magister', [AuthController::class, 'registerMagister']);
Route::post('/register-magister-rpl', [AuthController::class, 'registerMagisterRpl']);
Route::post('/login', [AuthController::class, 'login']);

// Rute Terproteksi
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // --- PERUBAHAN DI SINI ---
    // Pastikan rute ini memanggil 'getRegistrationStatus'
    Route::get('/registration-status', [DashboardController::class, 'getRegistrationStatus']);

    Route::post('/logout', [AuthController::class, 'logout']);

    // Rute untuk submit form
    Route::post('/submit-pendaftaran-awal', [PendaftaranController::class, 'submitPendaftaranAwal']);
    Route::post('/submit-konfirmasi-pembayaran', [PendaftaranController::class, 'submitKonfirmasiPembayaran']);
    Route::post('/submit-hasil-tes', [PendaftaranController::class, 'submitHasilTes']);
    Route::post('/submit-daftar-ulang', [PendaftaranController::class, 'submitDaftarUlang']);
    Route::post('/submit-konfirmasi-daful', [PendaftaranController::class, 'submitKonfirmasiDaful']);

    // Rute Admin
    Route::post('/admin/confirm-daful/{user}', [PendaftaranController::class, 'adminConfirmDafulAndGenerateNpm']);
});

Route::middleware('auth:sanctum')->prefix('rpl')->group(function () {
    // Middleware tambahan untuk memastikan hanya user RPL yang bisa akses
    // Route::middleware('auth.rpl')->group(function () { // Opsional, tapi sangat direkomendasikan

        // Mengambil data progres pendaftaran RPL
        Route::get('/user', [App\Http\Controllers\Api\RplDashboardController::class, 'getUserData']);

        // Submit formulir-formulir pendaftaran RPL
        Route::post('/submit-pendaftaran-awal', [App\Http\Controllers\Api\RplPendaftaranController::class, 'submitPendaftaranAwal']);
        Route::post('/submit-konfirmasi-pembayaran', [App\Http\Controllers\Api\RplPendaftaranController::class, 'submitKonfirmasiPembayaran']);
        Route::post('/submit-hasil-tes', [App\Http\Controllers\Api\RplPendaftaranController::class, 'submitHasilTes']);
        Route::post('/submit-konfirmasi-daful', [App\Http\Controllers\Api\RplPendaftaranController::class, 'submitKonfirmasiDaful']);
        Route::post('/submit-daftar-ulang', [App\Http\Controllers\Api\RplPendaftaranController::class, 'submitDaftarUlang']);

    // }); // Akhir dari grup middleware auth.rpl
    });

    Route::middleware('auth:sanctum')->prefix('magister')->group(function () {
        // Mengambil data progres pendaftaran Magister
        Route::get('/user', [App\Http\Controllers\Api\MagisterDashboardController::class, 'getUserData']);

        // Submit formulir-formulir pendaftaran Magister
        Route::post('/submit-pendaftaran-awal', [App\Http\Controllers\Api\MagisterPendaftaranController::class, 'submitPendaftaranAwal']);
        Route::post('/submit-konfirmasi-pembayaran', [App\Http\Controllers\Api\MagisterPendaftaranController::class, 'submitKonfirmasiPembayaran']);
        Route::post('/submit-hasil-tes', [App\Http\Controllers\Api\MagisterPendaftaranController::class, 'submitHasilTes']);
        Route::post('/submit-konfirmasi-daful', [App\Http\Controllers\Api\MagisterPendaftaranController::class, 'submitKonfirmasiDaful']);
        Route::post('/submit-daftar-ulang', [App\Http\Controllers\Api\MagisterPendaftaranController::class, 'submitDaftarUlang']);
    });
