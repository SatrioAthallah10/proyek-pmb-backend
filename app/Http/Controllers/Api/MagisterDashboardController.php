<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MagisterDashboardController extends Controller
{
    /**
     * Mengambil data lengkap pengguna Magister yang sedang login.
     */
    public function getUserData(Request $request)
    {
        return response()->json(Auth::user());
    }
}
