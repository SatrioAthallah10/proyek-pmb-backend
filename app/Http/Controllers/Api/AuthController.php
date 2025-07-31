<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // ... (method register, registerRpl, dan registerMagister tetap sama)
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->nama,
            'email' => $request->email,
            'jalur_pendaftaran' => 'reguler',
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'Registrasi berhasil!', 'user' => $user], 201);
    }

    public function registerRpl(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->nama,
            'email' => $request->email,
            'jalur_pendaftaran' => 'rpl',
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'Registrasi RPL berhasil!', 'user' => $user], 201);
    }

    public function registerMagister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->nama,
            'email' => $request->email,
            'jalur_pendaftaran' => 'magister-reguler',
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'Registrasi Magister berhasil!', 'user' => $user], 201);
    }

    /**
     * Method baru khusus untuk registrasi Magister RPL.
     */
    public function registerMagisterRpl(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->nama,
            'email' => $request->email,
            'jalur_pendaftaran' => 'magister-rpl', // Selalu diatur sebagai 'magister-rpl'
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'Registrasi Magister RPL berhasil!', 'user' => $user], 201);
    }

    // ... (method login dan logout tetap sama)
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

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Successfully logged out']);
    }
}
