<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Kolom untuk melacak status setiap langkah pendaftaran
            $table->string('formulir_pendaftaran_status')->default('Belum Mengisi Formulir');
            $table->boolean('formulir_pendaftaran_completed')->default(false);

            $table->string('pembayaran_form_status')->default('Belum Membayar');
            $table->boolean('pembayaran_form_completed')->default(false);

            $table->string('administrasi_status')->default('Menunggu Pembayaran');
            $table->boolean('administrasi_completed')->default(false);

            $table->string('tes_seleksi_status')->default('Belum Mengikuti Tes');
            $table->boolean('tes_seleksi_completed')->default(false);
            
            $table->string('pembayaran_daful_status')->default('Menunggu Hasil Tes');
            $table->boolean('pembayaran_daful_completed')->default(false);

            $table->string('pengisian_data_diri_status')->default('Belum Mengisi Data Diri');
            $table->boolean('pengisian_data_diri_completed')->default(false);

            $table->string('npm_status')->default('Belum Diproses');
            $table->boolean('npm_completed')->default(false);

            $table->string('no_ktp')->nullable();
            $table->string('no_ponsel')->nullable();
            $table->text('alamat')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('asal_sekolah')->nullable();
            $table->string('nama_sekolah')->nullable();
            $table->string('jurusan')->nullable();
            $table->string('status_sekolah')->nullable();
            $table->text('alamat_sekolah')->nullable();
            $table->string('kota_sekolah')->nullable();
            $table->string('nilai_rata_rata')->nullable();
            $table->string('prodi_pilihan')->nullable();
            $table->string('jadwal_kuliah')->nullable();
            $table->string('tahun_ajaran')->nullable();

            $table->string('bukti_pembayaran_path')->nullable();
            $table->string('keterangan_pembayaran')->nullable();
            $table->string('nama_pengirim_transfer')->nullable();
            $table->decimal('nominal_transfer', 15, 2)->nullable();
            $table->date('tanggal_transfer')->nullable();

            $table->string('nisn')->nullable();
            $table->string('kewarganegaraan')->nullable();
            $table->string('no_telp_rumah')->nullable();
            $table->string('dusun')->nullable();
            $table->string('rt')->nullable();
            $table->string('rw')->nullable();
            $table->string('kelurahan')->nullable();
            $table->string('kode_pos')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kota')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('agama')->nullable();
            $table->string('jenis_tinggal')->nullable();
            $table->string('alat_transportasi')->nullable();
            // Data Wali
            $table->string('nama_ayah')->nullable();
            $table->string('nik_ayah')->nullable();
            $table->date('tanggal_lahir_ayah')->nullable();
            $table->string('pendidikan_ayah')->nullable();
            $table->string('pekerjaan_ayah')->nullable();
            $table->string('penghasilan_ayah')->nullable();
            $table->string('nama_ibu')->nullable();
            $table->string('nik_ibu')->nullable();
            $table->date('tanggal_lahir_ibu')->nullable();
            $table->string('pendidikan_ibu')->nullable();
            $table->string('pekerjaan_ibu')->nullable();
            $table->string('penghasilan_ibu')->nullable();
            $table->string('nomor_orang_tua')->nullable();

            $table->string('bukti_daful_path')->nullable(); // daful = daftar ulang
            $table->string('keterangan_daful')->nullable();
            $table->string('nama_pengirim_daful')->nullable();
            $table->decimal('nominal_transfer_daful', 15, 2)->nullable();
            $table->date('tanggal_transfer_daful')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'formulir_pendaftaran_status',
                'formulir_pendaftaran_completed',
                'pembayaran_form_status',
                'pembayaran_form_completed',
                'administrasi_status',
                'administrasi_completed',
                'tes_seleksi_status',
                'tes_seleksi_completed',
                'pembayaran_daful_status',
                'pembayaran_daful_completed',
                'pengisian_data_diri_status',
                'pengisian_data_diri_completed',
                'npm_status',
                'npm_completed','no_ktp', 'no_ponsel', 'alamat', 'tempat_lahir', 'tanggal_lahir',
                'asal_sekolah', 'nama_sekolah', 'jurusan', 'status_sekolah',
                'alamat_sekolah', 'kota_sekolah', 'nilai_rata_rata', 'prodi_pilihan',
                'jadwal_kuliah', 'tahun_ajaran', 'bukti_pembayaran_path', 'keterangan_pembayaran', 'nama_pengirim_transfer',
                'nominal_transfer', 'tanggal_transfer', 'nisn', 'kewarganegaraan', 'no_telp_rumah', 'dusun', 'rt', 'rw',
                'kelurahan', 'kode_pos', 'kecamatan', 'kota', 'provinsi', 'agama',
                'jenis_tinggal', 'alat_transportasi',
                'nama_ayah', 'nik_ayah', 'tanggal_lahir_ayah', 'pendidikan_ayah',
                'pekerjaan_ayah', 'penghasilan_ayah', 'nama_ibu', 'nik_ibu',
                'tanggal_lahir_ibu', 'pendidikan_ibu', 'pekerjaan_ibu',
                'penghasilan_ibu', 'nomor_orang_tua', 'bukti_daful_path', 'keterangan_daful', 'nama_pengirim_daful',
                'nominal_transfer_daful', 'tanggal_transfer_daful'
            ]);
        });
    }
};