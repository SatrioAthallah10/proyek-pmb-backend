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

        $user->name = $request->input('nama_lengkap'); 
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
        $user->kelas = $request->input('kelas');
        $user->jadwal_kuliah = $request->input('jadwal_kuliah');
        $user->tahun_ajaran = $request->input('tahun_ajaran');

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

        $user->bukti_pembayaran_path = $path;
        $user->pembayaran_form_status = 'Menunggu Konfirmasi';
        $user->payment_uploaded_at = now();
        $user->save();

        return response()->json(['message' => 'Konfirmasi pembayaran berhasil dikirim.']);
    }

    public function submitDaftarUlang(Request $request)
    {
        $user = Auth::user();

        // --- [PERBAIKAN] ---
        // Memetakan setiap field secara manual untuk keamanan dan kejelasan
        $user->prodi_pilihan = $request->input('prodi_pilihan');
        $user->jadwal_kuliah = $request->input('jadwal_kuliah');
        $user->tahun_ajaran = $request->input('tahun_ajaran');
        $user->nisn = $request->input('nisn');
        $user->kewarganegaraan = $request->input('kewarganegaraan');
        $user->no_telp_rumah = $request->input('no_telp_rumah');
        $user->alamat = $request->input('alamat');
        $user->dusun = $request->input('dusun');
        $user->rt = $request->input('rt');
        $user->rw = $request->input('rw');
        $user->kelurahan = $request->input('kelurahan');
        $user->kode_pos = $request->input('kode_pos');
        $user->kecamatan = $request->input('kecamatan');
        $user->kota = $request->input('kota');
        $user->provinsi = $request->input('provinsi');
        $user->agama = $request->input('agama');
        $user->jenis_tinggal = $request->input('jenis_tinggal');
        $user->alat_transportasi = $request->input('alat_transportasi');
        $user->nama_sekolah = $request->input('nama_sekolah');
        $user->jurusan = $request->input('jurusan');
        $user->status_sekolah = $request->input('status_sekolah');
        $user->alamat_sekolah = $request->input('alamat_sekolah');
        $user->kota_sekolah = $request->input('kota_sekolah');
        $user->nilai_rata_rata = $request->input('nilai_rata_rata');
        $user->nama_ayah = $request->input('nama_ayah');
        $user->nik_ayah = $request->input('nik_ayah');
        $user->tanggal_lahir_ayah = $request->input('tanggal_lahir_ayah');
        $user->pendidikan_ayah = $request->input('pendidikan_ayah');
        $user->pekerjaan_ayah = $request->input('pekerjaan_ayah');
        $user->penghasilan_ayah = $request->input('penghasilan_ayah');
        $user->nama_ibu = $request->input('nama_ibu');
        $user->nik_ibu = $request->input('nik_ibu');
        $user->tanggal_lahir_ibu = $request->input('tanggal_lahir_ibu');
        $user->pendidikan_ibu = $request->input('pendidikan_ibu');
        $user->pekerjaan_ibu = $request->input('pekerjaan_ibu');
        $user->penghasilan_ibu = $request->input('penghasilan_ibu');
        $user->nomor_orang_tua = $request->input('nomor_orang_tua');
        
        // Memperbarui status
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
        $user->daful_uploaded_at = now();
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
        $user->npm = $npm;
        $user->npm_completed = true;
        $user->save();

        return response()->json(['message' => 'Pembayaran dikonfirmasi dan NPM berhasil dibuat.', 'npm' => $npm]);
    }
}