<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

// Rute untuk registrasi reguler
Route::post('/register', [AuthController::class, 'register']);

// Rute khusus untuk registrasi RPL
Route::post('/register-rpl', [AuthController::class, 'registerRpl']);

// Rute khusus untuk registrasi Magister Reguler
Route::post('/register-magister', [AuthController::class, 'registerMagister']);

// Rute baru khusus untuk registrasi Magister RPL
Route::post('/register-magister-rpl', [AuthController::class, 'registerMagisterRpl']);

// Rute untuk login
Route::post('/login', [AuthController::class, 'login']);

// Rute yang memerlukan autentikasi
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);
});
