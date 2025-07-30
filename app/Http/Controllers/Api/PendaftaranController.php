<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class PendaftaranController extends Controller
{
    public function submitPendaftaranAwal(Request $request)
    {
        $user = Auth::user();
        $user->update($request->all());

        $user->formulir_pendaftaran_status = 'Sudah Mengisi Formulir';
        $user->formulir_pendaftaran_completed = true;
        $user->pembayaran_form_status = 'Belum Membayar';
        $user->save();

        return response()->json(['message' => 'Data pendaftaran awal berhasil disimpan.']);
    }

    public function submitKonfirmasiPembayaran(Request $request)
    {
        $request->validate([
            'buktiPembayaran' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $user = Auth::user();
        $path = $request->file('buktiPembayaran')->store('bukti_pembayaran', 'public');

        // --- PERBAIKAN DI SINI ---
        // Mengubah dari metode update() ke penyimpanan properti secara langsung
        // untuk memastikan perubahan tersimpan sebelum response dikirim.
        $user->bukti_pembayaran_path = $path;
        $user->pembayaran_form_status = 'Menunggu Konfirmasi';
        $user->save(); // Simpan perubahan secara eksplisit

        return response()->json(['message' => 'Konfirmasi pembayaran berhasil dikirim.']);
    }

    public function submitDaftarUlang(Request $request)
    {
        $user = Auth::user();
        $user->update($request->all());

        $user->pengisian_data_diri_status = 'Sudah Mengisi Data Diri';
        $user->pengisian_data_diri_completed = true;
        $user->npm_status = 'Menunggu Penerbitan NPM';
        $user->save();

        return response()->json(['message' => 'Data daftar ulang berhasil disimpan.']);
    }

    public function submitKonfirmasiDaful(Request $request)
    {
        $request->validate([
            'buktiPembayaran' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $user = Auth::user();
        $path = $request->file('buktiPembayaran')->store('bukti_daful', 'public');
        
        $user->bukti_daful_path = $path;
        $user->pembayaran_daful_status = 'Menunggu Konfirmasi';
        $user->save();

        return response()->json(['message' => 'Konfirmasi daftar ulang berhasil dikirim.']);
    }

    public function submitHasilTes(Request $request)
    {
        $user = Auth::user();
        
        $user->tes_seleksi_status = 'Sudah Lulus Tes';
        $user->tes_seleksi_completed = true;
        $user->pembayaran_daful_status = 'Belum Membayar';
        $user->save();

        return response()->json(['message' => 'Hasil tes berhasil disimpan!']);
    }

    public function adminConfirmDafulAndGenerateNpm(User $user)
    {
        $user->pembayaran_daful_status = 'Pembayaran Sudah Dikonfirmasi';
        $user->pembayaran_daful_completed = true;
        
        $tahun = substr(date('Y'), -2);
        $kodeProdi = '07';
        $nomorUrut = str_pad($user->id, 4, '0', STR_PAD_LEFT);
        $npm = "06.20{$tahun}.1.{$kodeProdi}{$nomorUrut}";

        $user->npm_status = $npm;
        $user->npm_completed = true;
        $user->save();

        return response()->json(['message' => 'Pembayaran dikonfirmasi dan NPM berhasil dibuat.', 'npm' => $npm]);
    }
}