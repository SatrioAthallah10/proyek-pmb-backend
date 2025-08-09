<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Throwable; // Import Throwable untuk menangkap semua jenis error

class AdminController extends Controller
{
    /**
     * Mengambil semua data user untuk ditampilkan di dashboard admin.
     */
    public function index()
    {
        $users = User::latest()->get();
        return response()->json($users);
    }

    /**
     * Mengonfirmasi status pendaftaran awal seorang user.
     */
    public function confirmInitialRegistration(User $user)
    {
        try {
            // --- PERBAIKAN DI SINI ---
            // Menggunakan metode save() manual, bukan update()
            $user->pendaftaran_awal = true;
            $user->save();

            return response()->json([
                'message' => 'Initial registration confirmed successfully for ' . $user->name,
                'user' => $user
            ]);
        } catch (Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Mengonfirmasi status pembayaran seorang user.
     */
    public function confirmPayment(User $user)
    {
        try {
            // --- PERBAIKAN DI SINI ---
            // Menggunakan metode save() manual, bukan update()
            $user->pembayaran = true;
            $user->save();

            return response()->json([
                'message' => 'Payment confirmed successfully for ' . $user->name,
                'user' => $user
            ]);
        } catch (Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Mengonfirmasi status daftar ulang seorang user.
     */
    public function confirmReRegistration(User $user)
    {
        try {
            // --- PERBAIKAN DI SINI ---
            // Menggunakan metode save() manual, bukan update()
            $user->daftar_ulang = true;
            $user->save();

            return response()->json([
                'message' => 'Re-registration confirmed successfully for ' . $user->name,
                'user' => $user
            ]);
        } catch (Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
