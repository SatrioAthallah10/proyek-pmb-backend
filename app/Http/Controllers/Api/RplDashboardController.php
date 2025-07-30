<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RplDashboardController extends Controller
{
    /**
     * Mengambil data lengkap pengguna RPL yang sedang login.
     */
    public function getUserData(Request $request)
    {
        // Pastikan hanya user RPL yang bisa mengakses (Best Practice)
        if (Auth::user()->jalur_pendaftaran !== 'rpl') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json(Auth::user());
    }
}