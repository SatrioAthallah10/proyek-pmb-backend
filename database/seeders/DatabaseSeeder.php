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
        // Panggil seeder yang sudah ada
        $this->call(AdminUserSeeder::class);

        // --- [PENAMBAHAN] Panggil seeder baru untuk hak akses menu ---
        $this->call(MenuPermissionSeeder::class);
    }
}
