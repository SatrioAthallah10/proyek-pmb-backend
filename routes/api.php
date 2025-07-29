<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController; // Tambahkan ini
use App\Http\Controllers\Api\PendaftaranController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/register-rpl', [AuthController::class, 'registerRpl']);
Route::post('/register-magister', [AuthController::class, 'registerMagister']);
Route::post('/register-magister-rpl', [AuthController::class, 'registerMagisterRpl']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // ENDPOINT BARU UNTUK STATUS PENDAFTARAN
    Route::get('/registration-status', [DashboardController::class, 'getStatus']);

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/submit-pendaftaran-awal', [PendaftaranController::class, 'submitPendaftaranAwal']);

    Route::post('/submit-konfirmasi-pembayaran', [PendaftaranController::class, 'submitKonfirmasiPembayaran']);

    Route::post('/submit-daftar-ulang', [PendaftaranController::class, 'submitDaftarUlang']);

    Route::post('/submit-konfirmasi-daful', [PendaftaranController::class, 'submitKonfirmasiDaful']);

    Route::post('/admin/confirm-daful/{user}', [PendaftaranController::class, 'adminConfirmDaful']);
});