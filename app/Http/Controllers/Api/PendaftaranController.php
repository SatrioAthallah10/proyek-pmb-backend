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

        // Memetakan 'nama_lengkap' dari request ke kolom 'name' di database
        $user->name = $request->input('nama_lengkap'); 
        
        // Memetakan sisa data dari request ke kolom database yang sesuai
        $user->no_ktp = $request->input('no_ktp');
        $user->no_ponsel = $request->input('no_ponsel');
        $user->alamat = $request->input('alamat');
        $user->tempat_lahir = $request->input('tempat_lahir');
        $user->tanggal_lahir = $request->input('tanggal_lahir');
        $user->asal_sekolah = $request->input('asal_sekolah');
        $user->nama_sekolah = $request->input('nama_sekolah');
        $user->jurusan = $request->input('jurusan');
        $user->status_sekolah = $request->input('status_sekolah');
        $user->alamat_sekolah = $request->input('alamat_sekolah');
        $user->kota_sekolah = $request->input('kota_sekolah');
        $user->nilai_rata_rata = $request->input('nilai_rata_rata');
        $user->prodi_pilihan = $request->input('prodi_pilihan');
        
        // --- [PERUBAHAN DIMULAI DI SINI] ---
        // Menambahkan pemetaan untuk 'kelas' dari request ke database
        $user->kelas = $request->input('kelas');
        // --- [PERUBAHAN SELESAI DI SINI] ---

        $user->jadwal_kuliah = $request->input('jadwal_kuliah');
        $user->tahun_ajaran = $request->input('tahun_ajaran');

        // Kode Anda untuk update status dipertahankan
        $user->formulir_pendaftaran_status = 'Sudah Mengisi Formulir';
        $user->formulir_pendaftaran_completed = true;
        $user->pembayaran_form_status = 'Belum Membayar';
        
        // Menyimpan semua perubahan ke database
        $user->save();

        return response()->json(['message' => 'Data pendaftaran awal berhasil disimpan.']);
    }

    // Fungsi-fungsi lain di bawah ini tidak diubah dan tetap sama seperti milik Anda
    public function submitKonfirmasiPembayaran(Request $request)
    {
        $request->validate([
            'buktiPembayaran' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $user = Auth::user();
        $path = $request->file('buktiPembayaran')->store('bukti_pembayaran', 'public');

        $user->bukti_pembayaran_path = $path;
        $user->pembayaran_form_status = 'Menunggu Konfirmasi';
        $user->payment_uploaded_at = now(); // Menambahkan waktu upload
        $user->save();

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
        $user->daful_uploaded_at = now(); // Menambahkan waktu upload
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
        $kodeProdi = '07'; // Sebaiknya kode prodi ini dinamis
        $nomorUrut = str_pad($user->id, 4, '0', STR_PAD_LEFT);
        $npm = "06.20{$tahun}.1.{$kodeProdi}{$nomorUrut}";

        $user->npm_status = $npm;
        $user->npm = $npm; // Menyimpan NPM juga di kolomnya sendiri
        $user->npm_completed = true;
        $user->save();

        return response()->json(['message' => 'Pembayaran dikonfirmasi dan NPM berhasil dibuat.', 'npm' => $npm]);
    }
}
