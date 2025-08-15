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
        $query = User::whereNull('role');

        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%");
            });
        }

        $users = $query->orderBy('id', 'desc')->get();
        return response()->json($users);
    }

    /**
     * Mengambil statistik pendaftaran.
     */
    public function getStats()
    {
        $stats = [
            'total_pendaftar' => User::whereNull('role')->count(),
            'pendaftaran_awal_selesai' => User::whereNull('role')->where('pendaftaran_awal', true)->count(),
            'pembayaran_selesai' => User::whereNull('role')->where('pembayaran', true)->count(),
            'daftar_ulang_selesai' => User::whereNull('role')->where('daftar_ulang', true)->count(),
        ];
        return response()->json($stats);
    }

    /**
     * Mengambil detail spesifik dari seorang user.
     */
    public function getUserDetails(User $user)
    {
        $user->load('paymentConfirmedByAdmin', 'dafulConfirmedByAdmin');
        return response()->json($user);
    }

    // --- [FUNGSI BARU UNTUK MAHASISWA AKTIF] ---
    /**
     * Mengambil data mahasiswa yang sudah menyelesaikan daftar ulang.
     */
    public function getActiveStudents(Request $request)
    {
        // Memulai query untuk user yang sudah daftar ulang dan bukan admin
        $query = User::where('daftar_ulang', true)->whereNull('role');

        // Menambahkan fungsionalitas pencarian berdasarkan nama atau NPM
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('npm', 'like', "%{$searchTerm}%");
            });
        }

        $activeStudents = $query->orderBy('name', 'asc')->get();
        return response()->json($activeStudents);
    }
    // --- [AKHIR DARI FUNGSI BARU] ---

    /**
     * Mengonfirmasi pendaftaran awal seorang user.
     */
    public function confirmInitialRegistration(User $user)
    {
        try {
            $user->update([
                'pendaftaran_awal' => true,
                'formulir_pendaftaran_completed' => true,
                'formulir_pendaftaran_status' => 'Sudah Mengisi Formulir',
            ]);
            return response()->json(['message' => 'Pendaftaran awal berhasil dikonfirmasi untuk ' . $user->name]);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Konfirmasi Pendaftaran Awal Gagal: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Mengonfirmasi pembayaran form pendaftaran seorang user.
     */
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
            return response()->json(['message' => 'Pembayaran berhasil dikonfirmasi untuk ' . $user->name]);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Konfirmasi Pembayaran Gagal: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Mengonfirmasi pembayaran daftar ulang seorang user.
     */
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
            return response()->json(['message' => 'Daftar ulang berhasil dikonfirmasi untuk ' . $user->name]);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Konfirmasi Daftar Ulang Gagal: ' . $e->getMessage()], 500);
        }
    }
}
