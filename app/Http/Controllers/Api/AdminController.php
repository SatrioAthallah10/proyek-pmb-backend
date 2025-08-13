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

        $users = $query->latest()->get();
        return response()->json($users);
    }

    /**
     * [FUNGSI BARU] Mengambil semua data mahasiswa aktif (daftar ulang selesai).
     */
    public function getActiveStudents(Request $request)
    {
        $query = User::where('is_admin', false)
                     ->where('daftar_ulang', true); // Filter hanya yang sudah daftar ulang

        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                // --- [PERBAIKAN] Hapus pencarian berdasarkan 'npm' dari 'where clause' ---
                $q->where('name', 'like', "%{$searchTerm}%");
            });
        }
        
        $users = $query->orderBy('created_at', 'desc')->get([
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
            
            $user->payment_confirmed_by = Auth::id();
            $user->payment_confirmed_at = now();

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

            $user->daful_confirmed_by = Auth::id();
            $user->daful_confirmed_at = now();

            $user->save();
            return response()->json(['message' => 'Re-registration confirmed for ' . $user->name]);
        } catch (Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
