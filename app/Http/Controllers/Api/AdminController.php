<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Throwable;

class AdminController extends Controller
{
    /**
     * Mengambil semua data user (non-admin) untuk ditampilkan di dashboard admin.
     */
    public function index()
    {
        // --- PERBAIKAN DI SINI ---
        // Menambahkan where('is_admin', false) untuk menyaring admin
        $users = User::where('is_admin', false)->latest()->get();
        return response()->json($users);
    }

    /**
     * --- FUNGSI BARU DITAMBAHKAN ---
     * Menghitung dan mengembalikan statistik pendaftaran.
     */
    public function getStats()
    {
        $stats = [
            'total_pendaftar' => User::where('is_admin', false)->count(),
            'pendaftaran_awal_selesai' => User::where('is_admin', false)->where('pendaftaran_awal', true)->count(),
            'pembayaran_selesai' => User::where('is_admin', false)->where('pembayaran', true)->count(),
            'daftar_ulang_selesai' => User::where('is_admin', false)->where('daftar_ulang', true)->count(),
        ];

        return response()->json($stats);
    }

    // Fungsi konfirmasi lainnya tidak diubah
    public function confirmInitialRegistration(User $user)
    {
        try {
            $user->pendaftaran_awal = true;
            $user->formulir_pendaftaran_completed = true;
            $user->formulir_pendaftaran_status = 'Sudah Mengisi Formulir';
            $user->save();
            return response()->json(['message' => 'Initial registration confirmed for ' . $user->name]);
        } catch (Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function confirmPayment(User $user)
    {
        try {
            $user->pembayaran = true;
            $user->pembayaran_form_completed = true;
            $user->pembayaran_form_status = 'Pembayaran Sudah Dikonfirmasi';
            $user->administrasi_status = 'Sudah Lolos Administrasi';
            $user->administrasi_completed = true;
            $user->tes_seleksi_status = 'Belum Mengikuti Tes';
            $user->save();
            return response()->json(['message' => 'Payment confirmed for ' . $user->name]);
        } catch (Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function confirmReRegistration(User $user)
    {
        try {
            $user->daftar_ulang = true;
            $user->pembayaran_daful_completed = true;
            $user->pembayaran_daful_status = 'Pembayaran Sudah Dikonfirmasi';
            $user->save();
            return response()->json(['message' => 'Re-registration confirmed for ' . $user->name]);
        } catch (Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
