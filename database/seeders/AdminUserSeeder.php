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
        // --- [PERBAIKAN DIMULAI DI SINI] ---

        // Menggunakan updateOrCreate untuk menghindari error duplikasi.
        // Metode ini akan mencari user berdasarkan 'email'.
        // Jika tidak ada, user baru akan dibuat dengan semua data yang diberikan.
        // Jika sudah ada, datanya akan diperbarui (dalam kasus ini, tidak ada perubahan).

        $admins = [
            [
                'name' => 'Admin (Kepala Bagian)',
                'email' => 'kepala@pmb.com',
                'password' => Hash::make('password123'),
                'role' => 'kepala_bagian',
            ],
            [
                'name' => 'Admin (Staff)',
                'email' => 'staff@pmb.com',
                'password' => Hash::make('password123'),
                'role' => 'staff',
            ],
            [
                'name' => 'Admin (Owner)',
                'email' => 'owner@pmb.com',
                'password' => Hash::make('password123'),
                'role' => 'owner',
            ],
        ];

        foreach ($admins as $adminData) {
            User::updateOrCreate(
                ['email' => $adminData['email']], // Kunci untuk mencari
                $adminData // Data untuk dibuat atau diperbarui
            );
        }

        // --- [PERBAIKAN SELESAI DI SINI] ---
    }
}
