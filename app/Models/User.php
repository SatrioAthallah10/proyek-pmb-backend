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
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

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
        // --- [PERBAIKAN] Menghapus 'is_admin' yang sudah tidak digunakan ---
        'role', // Menambahkan 'role' agar bisa diisi saat pendaftaran/seeding
        'pendaftaran_awal',
        'pembayaran',
        'daftar_ulang',
        'nama_lengkap', 'no_ktp', 'no_ponsel', 'alamat', 'tempat_lahir', 'tanggal_lahir',
        'asal_sekolah', 'nama_sekolah', 'jurusan', 'status_sekolah', 'alamat_sekolah',
        'kota_sekolah', 'nilai_rata_rata', 'prodi_pilihan',
        'kelas',
        'jadwal_kuliah', 'tahun_ajaran',
        'bukti_pembayaran_path',
        'jenis_kelamin',
        'sumber_pendaftaran',
        'nomor_brosur',
        'nama_pemberi_rekomendasi',
        'nomor_wa_rekomendasi',
        'formulir_pendaftaran_status', 'pembayaran_form_status', 'administrasi_status',
        'tes_seleksi_status', 'pembayaran_daful_status', 'pengisian_data_diri_status', 'npm_status',
        'formulir_pendaftaran_completed', 'pembayaran_form_completed', 'administrasi_completed',
        'tes_seleksi_completed', 'pembayaran_daful_completed', 'pengisian_data_diri_completed',
        'payment_uploaded_at',
        'payment_confirmed_by',
        'payment_confirmed_at',
        'bukti_daful_path',
        'daful_uploaded_at',
        'daful_confirmed_by',
        'daful_confirmed_at',
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
            // --- [PERBAIKAN] Menghapus 'is_admin' yang sudah tidak digunakan ---
            'pendaftaran_awal' => 'boolean',
            'pembayaran' => 'boolean',
            'daftar_ulang' => 'boolean',
            'formulir_pendaftaran_completed' => 'boolean',
            'pembayaran_form_completed' => 'boolean',
            'administrasi_completed' => 'boolean',
            'tes_seleksi_completed' => 'boolean',
            'pembayaran_daful_completed' => 'boolean',
            'pengisian_data_diri_completed' => 'boolean',
            'payment_uploaded_at' => 'datetime',
            'payment_confirmed_at' => 'datetime',
            'daful_uploaded_at' => 'datetime',
            'daful_confirmed_at' => 'datetime',
        ];
    }

    /**
     * Relasi untuk mengambil data admin yang mengonfirmasi
     */
    public function paymentConfirmedByAdmin()
    {
        return $this->belongsTo(User::class, 'payment_confirmed_by');
    }

    public function dafulConfirmedByAdmin()
    {
        return $this->belongsTo(User::class, 'daful_confirmed_by');
    }
}
