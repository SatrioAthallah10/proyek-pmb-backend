<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Baris yang membuat 'Test User' sudah dihapus.
        
        // --- [PERUBAHAN DIMULAI DI SINI] ---
        // Memanggil seeder admin agar akun admin dibuat.
        $this->call([
            AdminUserSeeder::class,
        ]);
        // --- [PERUBAHAN SELESAI DI SINI] ---
    }
}