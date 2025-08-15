<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\PendaftaranController;
use App\Http\Controllers\Api\AdminController;
// Menghapus 'use' untuk IsAdmin karena sudah tidak digunakan
// use App\Http\Middleware\IsAdmin;

// --- (Rute Publik dan Rute User Biasa tidak berubah) ---
// Rute Publik
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

    // Rute submit form
    Route::post('/submit-pendaftaran-awal', [PendaftaranController::class, 'submitPendaftaranAwal']);
    Route::post('/submit-konfirmasi-pembayaran', [PendaftaranController::class, 'submitKonfirmasiPembayaran']);
    Route::post('/submit-hasil-tes', [PendaftaranController::class, 'submitHasilTes']);
    Route::post('/submit-daftar-ulang', [PendaftaranController::class, 'submitDaftarUlang']);
    Route::post('/submit-konfirmasi-daful', [PendaftaranController::class, 'submitKonfirmasiDaful']);
});


// --- [PERUBAHAN DIMULAI DI SINI] ---

// Mengganti grup rute admin yang lama dengan struktur berbasis peran yang baru.
Route::prefix('admin')->middleware(['auth:sanctum'])->group(function () {

    // Grup Rute untuk Admin (Owner)
    // Hanya bisa diakses oleh 'owner' dan 'kepala_bagian'
    Route::middleware('role:owner,kepala_bagian')->group(function () {
        Route::get('/stats', [AdminController::class, 'getStats']); // Endpoint untuk melihat statistik pendaftar
    });

    // Grup Rute untuk Admin (Staff)
    // Bisa diakses oleh 'staff' dan 'kepala_bagian'
    Route::middleware('role:staff,kepala_bagian')->group(function () {
        // Endpoint untuk melihat semua user (dibutuhkan untuk halaman konfirmasi)
        Route::get('/users-for-confirmation', [AdminController::class, 'getUsersForConfirmation']); 
        // Endpoint untuk konfirmasi pembayaran
        Route::put('/users/{user}/confirm-payment', [AdminController::class, 'confirmPayment']);
    });

    // Grup Rute untuk Admin (Kepala Bagian)
    // HANYA bisa diakses oleh 'kepala_bagian'
    Route::middleware('role:kepala_bagian')->group(function () {
        Route::get('/users', [AdminController::class, 'index']); // Melihat semua data user
        Route::get('/active-students', [AdminController::class, 'getActiveStudents']); // Melihat mahasiswa aktif
        Route::get('/users/{user}', [AdminController::class, 'getUserDetails']); // Melihat detail user
        
        // Rute konfirmasi lainnya
        Route::put('/users/{user}/confirm-initial-registration', [AdminController::class, 'confirmInitialRegistration']);
        Route::put('/users/{user}/confirm-reregistration', [AdminController::class, 'confirmReRegistration']);
        
        // Rute untuk mengelola admin (jika diperlukan di masa depan)
        // Route::apiResource('roles', RoleController::class);
    });
});

// --- [PERUBAHAN SELESAI DI SINI] ---