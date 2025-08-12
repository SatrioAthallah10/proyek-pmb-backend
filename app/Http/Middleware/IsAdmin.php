<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek jika user sudah login DAN merupakan admin
        if (Auth::check() && Auth::user()->is_admin) {
            // Lanjutkan request jika user adalah admin
            return $next($request);
        }

        // Jika bukan admin, kembalikan response error 'Unauthorized'
        return response()->json(['message' => 'Unauthorized. Anda bukan Kepala Bagian.'], 403); // <-- [PERUBAHAN]
    }
}
