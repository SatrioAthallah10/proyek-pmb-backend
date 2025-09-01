<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // 1. Menambahkan aturan validasi untuk field baru
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'alamat' => 'nullable|string',
            'jenisKelamin' => 'nullable|string',
            'nomorTelepon' => 'nullable|string',
            'sumberPendaftaran' => ['nullable', 'string', Rule::in([
                'Brosur', 
                'Pameran', 
                'Sosialisasi/Kunjungan ITATS di Sekolah',
                'Instagram',
                'Facebook',
                'TikTok',
                'LinkedIn',
                'Youtube',
                'Whatsapp Blasting',
                'Website ITATS',
                'Guru BK',
                'Alumni ITATS',
                'Teman', 
                'Tetangga/Saudara',
                'Dosen ITATS',
                'Mahasiswa ITATS',
                'Brosur (DIGITAL)',
                'Guru',
                'Pengasuh Pondok Pesantren',
                'Program Afirmasi Keluarga Wisudawan',
                'Affiliate',
            ])],
            'nomorBrosur' => 'nullable|string',
            'namaPemberiRekomendasi' => 'nullable|string',
            'nomorWaRekomendasi' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // 2. Menambahkan data baru saat membuat user
        $user = User::create([
            'name' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'jalur_pendaftaran' => 'Sarjana Reguler',
            'alamat' => $request->alamat,
            'jenis_kelamin' => $request->jenisKelamin,
            'no_ponsel' => $request->nomorTelepon,
            'sumber_pendaftaran' => $request->sumberPendaftaran,
            'nomor_brosur' => $request->nomorBrosur,
            'nama_pemberi_rekomendasi' => $request->namaPemberiRekomendasi,
            'nomor_wa_rekomendasi' => $request->nomorWaRekomendasi,
        ]);

        return response()->json(['message' => 'Registrasi berhasil!', 'user' => $user], 201);
    }

    /**
     * [PERUBAHAN]
     * Mengubah method registerRpl agar sama dengan method register,
     * namun dengan 'jalur_pendaftaran' diatur sebagai 'Sarjana RPL'.
     */
    public function registerRpl(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'alamat' => 'nullable|string',
            'jenisKelamin' => 'nullable|string',
            'nomorTelepon' => 'nullable|string',
            'sumberPendaftaran' => ['nullable', 'string', Rule::in([
                'Brosur', 
                'Pameran', 
                'Sosialisasi/Kunjungan ITATS di Sekolah',
                'Instagram',
                'Facebook',
                'TikTok',
                'LinkedIn',
                'Youtube',
                'Whatsapp Blasting',
                'Website ITATS',
                'Guru BK',
                'Alumni ITATS',
                'Teman', 
                'Tetangga/Saudara',
                'Dosen ITATS',
                'Mahasiswa ITATS',
                'Brosur (DIGITAL)',
                'Guru',
                'Pengasuh Pondok Pesantren',
                'Program Afirmasi Keluarga Wisudawan',
                'Affiliate',
            ])],
            'nomorBrosur' => 'nullable|string',
            'namaPemberiRekomendasi' => 'nullable|string',
            'nomorWaRekomendasi' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'jalur_pendaftaran' => 'Sarjana RPL', // Mengatur jalur pendaftaran
            'alamat' => $request->alamat,
            'jenis_kelamin' => $request->jenisKelamin,
            'no_ponsel' => $request->nomorTelepon,
            'sumber_pendaftaran' => $request->sumberPendaftaran,
            'nomor_brosur' => $request->nomorBrosur,
            'nama_pemberi_rekomendasi' => $request->namaPemberiRekomendasi,
            'nomor_wa_rekomendasi' => $request->nomorWaRekomendasi,
        ]);

        return response()->json(['message' => 'Registrasi Sarjana RPL berhasil!', 'user' => $user], 201);
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
            'jalur_pendaftaran' => 'magister-rpl',
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'Registrasi Magister RPL berhasil!', 'user' => $user], 201);
    }

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
