<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\PendaftaranController;
use App\Http\Controllers\Api\AdminController;

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

    // Rute submit form reguler
    Route::post('/submit-pendaftaran-awal', [PendaftaranController::class, 'submitPendaftaranAwal']);
    Route::post('/submit-konfirmasi-pembayaran', [PendaftaranController::class, 'submitKonfirmasiPembayaran']);
    Route::post('/submit-hasil-tes', [PendaftaranController::class, 'submitHasilTes']);
    Route::post('/submit-daftar-ulang', [PendaftaranController::class, 'submitDaftarUlang']);
    Route::post('/submit-konfirmasi-daful', [PendaftaranController::class, 'submitKonfirmasiDaful']);
    
    // --- PENAMBAHAN RUTE KHUSUS RPL ---
    Route::prefix('rpl')->group(function () {
        Route::post('/submit-pendaftaran-awal', [PendaftaranController::class, 'submitPendaftaranAwalRpl']);
        Route::post('/submit-konfirmasi-pembayaran', [PendaftaranController::class, 'submitKonfirmasiPembayaranRpl']);
        Route::post('/submit-hasil-tes', [PendaftaranController::class, 'submitHasilTesRpl']);
        Route::post('/submit-daftar-ulang', [PendaftaranController::class, 'submitDaftarUlangRpl']);
        Route::post('/submit-konfirmasi-daful', [PendaftaranController::class, 'submitKonfirmasiDafulRpl']);
    });
});


// Rute Admin (Tidak ada perubahan)
Route::prefix('admin')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/my-menu', [AdminController::class, 'getMyMenuPermissions']);
    Route::middleware('role:owner,kepala_bagian')->group(function () {
        Route::get('/stats', [AdminController::class, 'getStats']);
    });
    Route::middleware('role:staff,kepala_bagian')->group(function () {
        Route::get('/users', [AdminController::class, 'index']);
        Route::put('/users/{user}/confirm-payment', [AdminController::class, 'confirmPayment']);
    });
    Route::middleware('role:kepala_bagian')->group(function () {
        Route::get('/users/{user}', [AdminController::class, 'getUserDetails']);
        Route::put('/users/{user}/confirm-initial-registration', [AdminController::class, 'confirmInitialRegistration']);
        Route::put('/users/{user}/confirm-reregistration', [AdminController::class, 'confirmReRegistration']);
        Route::get('/active-students', [AdminController::class, 'getActiveStudents']);
        Route::put('/active-students/{user}/update-details', [AdminController::class, 'updateActiveStudentDetails']);
        Route::post('/register-staff', [AdminController::class, 'registerStaff']);
        Route::get('/menu-permissions', [AdminController::class, 'getMenuPermissions']);
        Route::put('/menu-permissions', [AdminController::class, 'updateMenuPermissions']);
    });
});
