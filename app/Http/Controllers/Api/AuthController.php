<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Kredensial yang diberikan tidak cocok dengan catatan kami.'],
            ]);
        }

        return response()->json([
            'user' => $user,
            'access_token' => $user->createToken('api_token')->plainTextToken,
        ]);
    }

    /**
     * PERBAIKAN: Method ini sekarang dinamis dan menerima jalur pendaftaran dari request.
     */
    public function register(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'jalur' => 'required|string', // Validasi baru untuk memastikan 'jalur' dikirim
        ]);

        $user = User::create([
            'name' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            // Menggunakan nilai 'jalur' dari request, bukan hardcode 'Reguler'
            'jalur_pendaftaran' => $request->jalur,
        ]);

        return response()->json(['message' => 'Registrasi berhasil!']);
    }

    public function registerMagister(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'jalur_pendaftaran' => 'magister',
        ]);

        return response()->json(['message' => 'Registrasi Magister berhasil!']);
    }

    public function registerMagisterRpl(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'jalur_pendaftaran' => 'magister-rpl',
        ]);

        return response()->json(['message' => 'Registrasi Magister RPL berhasil!']);
    }
}
