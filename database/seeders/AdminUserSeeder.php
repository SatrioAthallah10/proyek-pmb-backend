<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // --- [PERUBAHAN DIMULAI DI SINI] ---

        // 1. Membuat Admin Kepala Bagian (role paling tinggi)
        User::create([
            'name' => 'Admin (Kepala Bagian)',
            'email' => 'kepala@pmb.com',
            'password' => Hash::make('password123'),
            'role' => 'kepala_bagian', // Menggunakan kolom 'role' yang baru
        ]);

        // 2. Membuat Admin Staff (hanya untuk konfirmasi pembayaran)
        User::create([
            'name' => 'Admin (Staff)',
            'email' => 'staff@pmb.com',
            'password' => Hash::make('password123'),
            'role' => 'staff', // Menggunakan kolom 'role' yang baru
        ]);

        // 3. Membuat Admin Owner (hanya untuk melihat jumlah pendaftar)
        User::create([
            'name' => 'Admin (Owner)',
            'email' => 'owner@pmb.com',
            'password' => Hash::make('password123'),
            'role' => 'owner', // Menggunakan kolom 'role' yang baru
        ]);

        // --- [PERUBAHAN SELESAI DI SINI] ---
    }
}