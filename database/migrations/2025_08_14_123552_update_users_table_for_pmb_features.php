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
            // General flags and roles
            $table->string('jalur_pendaftaran')->default('reguler')->after('email');

            // --- [PERUBAHAN DIMULAI DI SINI] ---
            // Mengganti is_admin dengan kolom 'role' untuk menyimpan peran yang berbeda.
            // 'kepala_bagian' = Admin(Kepala Bagian)
            // 'staff' = Admin(Staff)
            // 'owner' = Admin(Owner)
            // User biasa akan memiliki nilai NULL pada kolom ini.
            $table->enum('role', ['kepala_bagian', 'staff', 'owner'])->nullable()->after('password');
            // Menghapus kolom is_admin yang lama
            // $table->boolean('is_admin')->default(false)->after('password'); 
            // --- [PERUBAHAN SELESAI DI SINI] ---

            $table->boolean('pendaftaran_awal')->default(false)->after('name');
            $table->boolean('pembayaran')->default(false)->after('pendaftaran_awal');
            $table->boolean('daftar_ulang')->default(false)->after('pembayaran');

            // ... sisa file tetap sama ...
            
            // Registration step statuses
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

            // Personal and contact info
            $table->string('jenis_kelamin')->nullable();
            $table->string('sumber_pendaftaran')->nullable();
            $table->string('nomor_brosur')->nullable();
            $table->string('nama_pemberi_rekomendasi')->nullable();
            $table->string('nomor_wa_rekomendasi')->nullable();
            $table->string('no_ktp')->nullable();
            $table->string('no_ponsel')->nullable();
            $table->text('alamat')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
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

            // School info
            $table->string('asal_sekolah')->nullable();
            $table->string('nama_sekolah')->nullable();
            $table->string('jurusan')->nullable();
            $table->string('status_sekolah')->nullable();
            $table->text('alamat_sekolah')->nullable();
            $table->string('kota_sekolah')->nullable();
            $table->string('nilai_rata_rata')->nullable();
            
            // Academic choices
            $table->string('prodi_pilihan')->nullable();
            $table->string('kelas')->nullable(); // Pagi/Malam
            $table->string('jadwal_kuliah')->nullable();
            $table->string('tahun_ajaran')->nullable();

            // Parent/Guardian info
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

            // Initial Payment (Formulir)
            $table->string('bukti_pembayaran_path')->nullable();
            $table->timestamp('payment_uploaded_at')->nullable();
            $table->string('keterangan_pembayaran')->nullable();
            $table->string('nama_pengirim_transfer')->nullable();
            $table->decimal('nominal_transfer', 15, 2)->nullable();
            $table->date('tanggal_transfer')->nullable();
            $table->foreignId('payment_confirmed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('payment_confirmed_at')->nullable();

            // Re-registration Payment (Daftar Ulang)
            $table->string('bukti_daful_path')->nullable();
            $table->timestamp('daful_uploaded_at')->nullable();
            $table->string('keterangan_daful')->nullable();
            $table->string('nama_pengirim_daful')->nullable();
            $table->decimal('nominal_transfer_daful', 15, 2)->nullable();
            $table->date('tanggal_transfer_daful')->nullable();
            $table->foreignId('daful_confirmed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('daful_confirmed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop foreign keys first
            $table->dropForeign(['payment_confirmed_by']);
            $table->dropForeign(['daful_confirmed_by']);
            
            // --- [PERUBAHAN DIMULAI DI SINI] ---
            // Hapus kolom 'role' dan kembalikan 'is_admin' jika migrasi di-rollback
            $table->dropColumn('role');
            // $table->boolean('is_admin')->default(false)->after('password');
            // --- [PERUBAHAN SELESAI DI SINI] ---

            // Drop all added columns
            $table->dropColumn([
                'jalur_pendaftaran', 'pendaftaran_awal', 'pembayaran', 'daftar_ulang', // is_admin dihapus dari sini
                'formulir_pendaftaran_status', 'formulir_pendaftaran_completed', 'pembayaran_form_status',
                'pembayaran_form_completed', 'administrasi_status', 'administrasi_completed',
                'tes_seleksi_status', 'tes_seleksi_completed', 'pembayaran_daful_status',
                'pembayaran_daful_completed', 'pengisian_data_diri_status', 'pengisian_data_diri_completed',
                'npm_status', 'npm_completed', 'jenis_kelamin', 'sumber_pendaftaran', 'nomor_brosur',
                'nama_pemberi_rekomendasi', 'nomor_wa_rekomendasi', 'no_ktp', 'no_ponsel', 'alamat',
                'tempat_lahir', 'tanggal_lahir', 'nisn', 'kewarganegaraan', 'no_telp_rumah', 'dusun',
                'rt', 'rw', 'kelurahan', 'kode_pos', 'kecamatan', 'kota', 'provinsi', 'agama',
                'jenis_tinggal', 'alat_transportasi', 'asal_sekolah', 'nama_sekolah', 'jurusan',
                'status_sekolah', 'alamat_sekolah', 'kota_sekolah', 'nilai_rata_rata', 'prodi_pilihan',
                'kelas', 'jadwal_kuliah', 'tahun_ajaran', 'nama_ayah', 'nik_ayah', 'tanggal_lahir_ayah',
                'pendidikan_ayah', 'pekerjaan_ayah', 'penghasilan_ayah', 'nama_ibu', 'nik_ibu',
                'tanggal_lahir_ibu', 'pendidikan_ibu', 'pekerjaan_ibu', 'penghasilan_ibu',
                'nomor_orang_tua', 'bukti_pembayaran_path', 'payment_uploaded_at',
                'keterangan_pembayaran', 'nama_pengirim_transfer', 'nominal_transfer',
                'tanggal_transfer', 'payment_confirmed_by', 'payment_confirmed_at', 'bukti_daful_path',
                'daful_uploaded_at', 'keterangan_daful', 'nama_pengirim_daful', 'nominal_transfer_daful',
                'tanggal_transfer_daful', 'daful_confirmed_by', 'daful_confirmed_at'
            ]);
        });
    }
};