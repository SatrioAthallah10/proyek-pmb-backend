<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Mengambil status pendaftaran dan data pengguna yang sedang login.
     */
    public function getRegistrationStatus(Request $request)
    {
        $user = Auth::user();

        // Logika Anda untuk menyusun array timeline dipertahankan
        $timeline = [
            [
                'title' => 'Formulir Pendaftaran', 
                'status' => $user->formulir_pendaftaran_status, 
                'completed' => $user->formulir_pendaftaran_completed ?? $user->pendaftaran_awal
            ],
            [
                'title' => 'Pembayaran Form Daftar', 
                'status' => $user->pembayaran_form_status, 
                'completed' => $user->pembayaran_form_completed ?? $user->pembayaran
            ],
            [
                'title' => 'Status Administrasi', 
                'status' => $user->administrasi_status, 
                'completed' => $user->administrasi_completed
            ],
            [
                'title' => 'Tes Seleksi PMB ITATS', 
                'status' => $user->tes_seleksi_status, 
                'completed' => $user->tes_seleksi_completed
            ],
            [
                'title' => 'Pembayaran Daftar Ulang', 
                'status' => $user->pembayaran_daful_status, 
                'completed' => $user->pembayaran_daful_completed ?? $user->daftar_ulang
            ],
            [
                'title' => 'Pengisian Data Diri', 
                'status' => $user->pengisian_data_diri_status, 
                'completed' => $user->pengisian_data_diri_completed
            ],
            [
                'title' => 'Penerbitan NPM', 
                'status' => $user->npm_status, 
                'completed' => $user->npm_completed
            ],
        ];

        // --- GABUNGAN PERUBAHAN DI SINI ---
        // Mengembalikan satu objek yang berisi data user DAN data timeline
        return response()->json([
            'user' => $user,
            'timeline' => $timeline
        ]);
    }
}
