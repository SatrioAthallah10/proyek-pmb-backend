<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class AdminController extends Controller
{
    /**
     * Mengambil semua data user (non-admin) dengan opsi pencarian.
     */
    public function index(Request $request)
    {
        $query = User::where('is_admin', false);

        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%");
            });
        }

        // Menggunakan 'id' untuk pengurutan karena 'created_at' tidak ada
        $users = $query->orderBy('id', 'desc')->get();
        return response()->json($users);
    }

    /**
     * Mengambil semua data mahasiswa aktif (daftar ulang selesai).
     */
    public function getActiveStudents(Request $request)
    {
        $query = User::where('is_admin', false)
                     ->where('daftar_ulang', true);

        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%");
            });
        }
        
        // Menghapus 'npm' dari daftar kolom karena tidak ada di tabel users
        $users = $query->orderBy('id', 'desc')->get([
            'id', 'name', 'email', 'jalur_pendaftaran'
        ]);

        return response()->json($users);
    }

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

    public function getUserDetails(User $user)
    {
        $user->load('paymentConfirmedByAdmin', 'dafulConfirmedByAdmin');
        return response()->json($user);
    }

    public function confirmInitialRegistration(User $user)
    {
        try {
            // Menggunakan metode update() untuk pembaruan yang lebih andal
            $user->update([
                'pendaftaran_awal' => true,
                'formulir_pendaftaran_completed' => true,
                'formulir_pendaftaran_status' => 'Sudah Mengisi Formulir',
            ]);
            return response()->json(['message' => 'Initial registration confirmed for ' . $user->name]);
        } catch (Throwable $e) {
            // Mengembalikan pesan error yang lebih spesifik untuk debugging
            return response()->json(['message' => 'Konfirmasi Pendaftaran Awal Gagal: ' . $e->getMessage()], 500);
        }
    }

    public function confirmPayment(User $user)
    {
        try {
            $user->update([
                'pembayaran' => true,
                'pembayaran_form_completed' => true,
                'pembayaran_form_status' => 'Pembayaran Sudah Dikonfirmasi',
                'administrasi_status' => 'Sudah Lolos Administrasi',
                'administrasi_completed' => true,
                'tes_seleksi_status' => 'Belum Mengikuti Tes',
                'payment_confirmed_by' => Auth::id(),
                'payment_confirmed_at' => now(),
            ]);
            return response()->json(['message' => 'Payment confirmed for ' . $user->name]);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Konfirmasi Pembayaran Gagal: ' . $e->getMessage()], 500);
        }
    }

    public function confirmReRegistration(User $user)
    {
        try {
            $user->update([
                'daftar_ulang' => true,
                'pembayaran_daful_completed' => true,
                'pembayaran_daful_status' => 'Pembayaran Sudah Dikonfirmasi',
                'daful_confirmed_by' => Auth::id(),
                'daful_confirmed_at' => now(),
            ]);
            return response()->json(['message' => 'Re-registration confirmed for ' . $user->name]);
        } catch (Throwable $e) {
            // --- [PERUBAHAN DIMULAI DI SINI] ---
            // Memperbaiki typo dari 'message's' menjadi 'message'
            return response()->json(['message' => 'Konfirmasi Daftar Ulang Gagal: ' . $e->getMessage()], 500);
            // --- [PERUBAHAN SELESAI DI SINI] ---
        }
    }
}
// --- [PERUBAHAN]: Menghapus kurung kurawal ekstra yang menyebabkan syntax error ---
