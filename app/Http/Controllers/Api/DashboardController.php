<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Pastikan ini di-import

class DashboardController extends Controller
{
    /**
     * Mengambil status pendaftaran pengguna yang sedang login secara dinamis.
     */
    public function getStatus(Request $request)
    {
        $user = Auth::user(); // Mengambil data pengguna yang terotentikasi

        // Menyusun array timeline berdasarkan data dari database
        $timeline = [
            [
                'title' => 'Formulir Pendaftaran', 
                'status' => $user->formulir_pendaftaran_status, 
                'completed' => $user->formulir_pendaftaran_completed
            ],
            [
                'title' => 'Pembayaran Form Daftar', 
                'status' => $user->pembayaran_form_status, 
                'completed' => $user->pembayaran_form_completed
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
                'completed' => $user->pembayaran_daful_completed
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

        return response()->json($timeline);
    }
}