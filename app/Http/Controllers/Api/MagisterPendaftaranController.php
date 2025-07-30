<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class MagisterPendaftaranController extends Controller
{
    public function submitPendaftaranAwal(Request $request)
    {
        $user = Auth::user();
        $user->fill($request->all());
        $user->formulir_pendaftaran_status = 'Sudah Mengisi Formulir';
        $user->formulir_pendaftaran_completed = true;
        $user->pembayaran_form_status = 'Belum Membayar';
        $user->save();
        return response()->json(['message' => 'Data pendaftaran awal Magister berhasil disimpan.']);
    }

    public function submitKonfirmasiPembayaran(Request $request)
    {
        $request->validate(['buktiPembayaran' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048', 'namaPengirim' => 'required|string', 'nominalTransfer' => 'required|numeric', 'tanggalTransfer' => 'required|date']);
        $user = Auth::user();
        if ($request->hasFile('buktiPembayaran')) {
            $path = $request->file('buktiPembayaran')->store('public/bukti_pembayaran');
            $user->bukti_pembayaran_path = Storage::url($path);
        }
        $user->pembayaran_form_status = 'Menunggu Konfirmasi';
        $user->save();
        return response()->json(['message' => 'Konfirmasi pembayaran formulir Magister berhasil dikirim.']);
    }

    public function submitHasilTes(Request $request)
    {
        try {
            $validatedData = $request->validate(['answers' => 'required|array']);
            $user = Auth::user();
            $answers = $validatedData['answers'];
            $score = 0;
            foreach ($answers as $answer) { if (rand(0, 1) === 1) { $score += 5; } }
            $isLulus = $score >= 70;
            $user->tes_seleksi_status = $isLulus ? 'Sudah Lulus Tes' : 'Tidak Lulus Tes';
            $user->tes_seleksi_completed = $isLulus;
            $user->save();
            return response()->json(['message' => 'Hasil tes berhasil disimpan.', 'user' => $user]);
        } catch (\Exception $e) {
            Log::error('Error saat submit hasil tes Magister: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan di server.', 'error' => $e->getMessage()], 500);
        }
    }

    public function submitKonfirmasiDaful(Request $request)
    {
        $request->validate(['buktiPembayaran' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048', 'namaPengirim' => 'required|string', 'nominalTransfer' => 'required|numeric', 'tanggalTransfer' => 'required|date']);
        $user = Auth::user();
        if ($request->hasFile('buktiPembayaran')) {
            $path = $request->file('buktiPembayaran')->store('public/bukti_daful');
            $user->bukti_daful_path = Storage::url($path);
        }
        $user->pembayaran_daful_status = 'Menunggu Konfirmasi';
        $user->save();
        return response()->json(['message' => 'Konfirmasi daftar ulang Magister berhasil dikirim.']);
    }

    public function submitDaftarUlang(Request $request)
    {
        $user = Auth::user();
        $user->fill($request->all());
        $user->pengisian_data_diri_status = 'Sudah Mengisi Data Diri';
        $user->pengisian_data_diri_completed = true;
        $user->save();
        return response()->json(['message' => 'Data diri lengkap Magister berhasil disimpan.']);
    }
}
