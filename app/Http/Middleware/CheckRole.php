<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles  // Menerima satu atau lebih peran sebagai argumen
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Cek apakah pengguna sudah login
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized. Silakan login terlebih dahulu.'], 401);
        }

        // 2. Ambil data pengguna yang sedang login
        $user = Auth::user();

        // 3. Loop melalui setiap peran yang diizinkan yang dikirim dari file rute
        foreach ($roles as $role) {
            // Cek apakah kolom 'role' pengguna cocok dengan salah satu peran yang diizinkan
            if ($user->role == $role) {
                // Jika cocok, izinkan permintaan untuk melanjutkan
                return $next($request);
            }
        }

        // 4. Jika setelah dicek semua tidak ada peran yang cocok, tolak akses
        return response()->json(['message' => 'Forbidden. Anda tidak memiliki hak akses untuk halaman ini.'], 403);
    }
}