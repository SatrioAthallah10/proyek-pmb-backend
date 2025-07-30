<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'jalur_pendaftaran',

        // --- TAMBAHAN PENTING ADA DI SINI ---
        // Semua kolom yang diisi melalui form perlu didaftarkan
        'no_ktp',
        'no_ponsel',
        'alamat',
        'tempat_lahir',
        'tanggal_lahir',
        'asal_sekolah',
        'nama_sekolah',
        'jurusan',
        'status_sekolah',
        'alamat_sekolah',
        'kota_sekolah',
        'nilai_rata_rata',
        'prodi_pilihan',
        'jadwal_kuliah',
        'tahun_ajaran',

        'bukti_pembayaran_path',
        'pembayaran_form_status',
        
        'bukti_daful_path',
        'pembayaran_daful_status',

        'nisn', 'kewarganegaraan', 'no_telp_rumah', 'dusun', 'rt', 'rw',
        'kelurahan', 'kode_pos', 'kecamatan', 'kota', 'provinsi', 'agama',
        'jenis_tinggal', 'alat_transportasi',
        'nama_ayah', 'nik_ayah', 'tanggal_lahir_ayah', 'pendidikan_ayah',
        'pekerjaan_ayah', 'penghasilan_ayah', 'nama_ibu', 'nik_ibu',
        'tanggal_lahir_ibu', 'pendidikan_ibu', 'pekerjaan_ibu',
        'penghasilan_ibu', 'nomor_orang_tua',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}