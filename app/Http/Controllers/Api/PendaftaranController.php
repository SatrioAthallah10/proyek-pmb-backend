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

        // Validasi data (bisa ditambahkan sesuai kebutuhan)
        $validatedData = $request->validate([
            'namaLengkap' => 'required|string',
            'noKtp' => 'required|string',
            // ... tambahkan validasi lain untuk setiap field
        ]);

        // Simpan data ke user
        $user->name = $request->namaLengkap;
        $user->no_ktp = $request->noKtp;
        $user->no_ponsel = $request->noPonsel;
        $user->alamat = $request->alamat;
        $user->tempat_lahir = $request->tempatLahir;
        $user->tanggal_lahir = $request->tanggalLahir;
        $user->asal_sekolah = $request->asalSekolah;
        $user->nama_sekolah = $request->namaSekolah;
        $user->jurusan = $request->jurusan;
        $user->status_sekolah = $request->statusSekolah;
        $user->alamat_sekolah = $request->alamatSekolah;
        $user->kota_sekolah = $request->kotaSekolah;
        $user->nilai_rata_rata = $request->nilaiRataRata;
        $user->prodi_pilihan = $request->prodi;
        $user->jadwal_kuliah = $request->jadwalKuliah;
        $user->tahun_ajaran = $request->tahunAjaran;

        // Update status pendaftaran
        $user->formulir_pendaftaran_status = 'Sudah Mengisi Formulir';
        $user->formulir_pendaftaran_completed = true;

        $user->save();

        return response()->json(['message' => 'Data pendaftaran berhasil disimpan!']);
    }

    public function submitKonfirmasiPembayaran(Request $request)
    {
        $user = Auth::user();

        // Validasi input
        $request->validate([
            'buktiPembayaran' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'keterangan' => 'required|string',
            'namaPengirim' => 'required|string',
            'nominalTransfer' => 'required|numeric',
            'tanggalTransfer' => 'required|date',
        ]);

        // Simpan file bukti pembayaran
        if ($request->hasFile('buktiPembayaran')) {
            $filePath = $request->file('buktiPembayaran')->store('bukti_pembayaran', 'public');
            $user->bukti_pembayaran_path = $filePath;
        }

        // Simpan data lain ke database
        $user->keterangan_pembayaran = $request->keterangan;
        $user->nama_pengirim_transfer = $request->namaPengirim;
        $user->nominal_transfer = $request->nominalTransfer;
        $user->tanggal_transfer = $request->tanggalTransfer;

        // Update status pendaftaran
        $user->pembayaran_form_status = 'Menunggu Konfirmasi';
        $user->pembayaran_form_completed = false; // Akan menjadi true setelah admin verifikasi

        $user->save();

        return response()->json(['message' => 'Konfirmasi pembayaran berhasil diunggah!']);
    }

    public function submitDaftarUlang(Request $request)
    {
        $user = Auth::user();

        // Simpan semua data dari request ke user
        // (Untuk singkatnya, saya tidak menambahkan validasi di sini,
        // tapi ini sangat disarankan untuk produksi)
        $user->update($request->all());

        // Update status pendaftaran
        $user->pengisian_data_diri_status = 'Sudah Mengisi Data Diri';
        $user->pengisian_data_diri_completed = true;
        $user->save();

        return response()->json(['message' => 'Data daftar ulang berhasil disimpan!']);
    }

    public function submitKonfirmasiDaful(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'buktiPembayaran' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'keterangan' => 'required|string',
            'namaPengirim' => 'required|string',
            'nominalTransfer' => 'required|numeric',
            'tanggalTransfer' => 'required|date',
        ]);

        if ($request->hasFile('buktiPembayaran')) {
            $filePath = $request->file('buktiPembayaran')->store('bukti_daftar_ulang', 'public');
            $user->bukti_daful_path = $filePath;
        }

        $user->keterangan_daful = $request->keterangan;
        $user->nama_pengirim_daful = $request->namaPengirim;
        $user->nominal_transfer_daful = $request->nominalTransfer;
        $user->tanggal_transfer_daful = $request->tanggalTransfer;

        // Update status pendaftaran
        $user->pembayaran_daful_status = 'Menunggu Konfirmasi';
        $user->pembayaran_daful_completed = false; // Akan jadi true setelah diverifikasi admin

        $user->save();

        return response()->json(['message' => 'Konfirmasi pembayaran daftar ulang berhasil diunggah!']);
    }

    public function adminConfirmDaful(User $user)
    {
        // Fungsi untuk menghasilkan NPM acak
        // Format: 06.[tahun].[kode_prodi].[nomor_urut]
        $tahun = date('Y');
        $kodeProdi = '1'; // Contoh kode prodi, bisa dibuat dinamis nanti
        $nomorUrut = str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
        $npm = "06.{$tahun}.{$kodeProdi}.{$nomorUrut}";

        // Update status pembayaran daftar ulang
        $user->pembayaran_daful_status = 'Pembayaran Sudah Dikonfirmasi';
        $user->pembayaran_daful_completed = true;

        // Update status NPM
        $user->npm_status = $npm;
        $user->npm_completed = true;

        $user->save();

        return response()->json([
            'message' => 'Pembayaran berhasil dikonfirmasi dan NPM telah diterbitkan!',
            'user' => $user
        ]);
    }
}

