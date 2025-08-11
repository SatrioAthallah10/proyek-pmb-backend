<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\PendaftaranController;
use App\Http\Controllers\Api\AdminController;
// Import class IsAdmin secara langsung
use App\Http\Middleware\IsAdmin;

// Rute Publik (sudah benar)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/register-rpl', [AuthController::class, 'registerRpl']);
Route::post('/register-magister', [AuthController::class, 'registerMagister']);
Route::post('/register-magister-rpl', [AuthController::class, 'registerMagisterRpl']);
Route::post('/login', [AuthController::class, 'login']);

// Rute Terproteksi untuk User Biasa
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    Route::get('/registration-status', [DashboardController::class, 'getRegistrationStatus']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Rute submit form (sudah benar)
    Route::post('/submit-pendaftaran-awal', [PendaftaranController::class, 'submitPendaftaranAwal']);
    Route::post('/submit-konfirmasi-pembayaran', [PendaftaranController::class, 'submitKonfirmasiPembayaran']);
    Route::post('/submit-hasil-tes', [PendaftaranController::class, 'submitHasilTes']);
    Route::post('/submit-daftar-ulang', [PendaftaranController::class, 'submitDaftarUlang']);
    Route::post('/submit-konfirmasi-daful', [PendaftaranController::class, 'submitKonfirmasiDaful']);
});

// --- PERBAIKAN UTAMA DI SINI ---
// Kita ganti alias 'is.admin' dengan class IsAdmin::class secara langsung
Route::middleware(['auth:sanctum', IsAdmin::class])->prefix('admin')->group(function () {
    // Route untuk mendapatkan semua data user
    Route::get('/users', [AdminController::class, 'index']);

    // Route untuk konfirmasi pendaftaran awal
    Route::put('/users/{user}/confirm-initial-registration', [AdminController::class, 'confirmInitialRegistration']);
    
    // Route untuk konfirmasi pembayaran
    Route::put('/users/{user}/confirm-payment', [AdminController::class, 'confirmPayment']);
    
    // Route untuk konfirmasi daftar ulang
    Route::put('/users/{user}/confirm-reregistration', [AdminController::class, 'confirmReRegistration']);
});
