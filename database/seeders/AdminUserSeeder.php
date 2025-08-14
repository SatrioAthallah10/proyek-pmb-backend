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
        User::create([
            'name' => 'Admin(Kepala Bagian)',
            'email' => 'admin@email.com',
            'password' => Hash::make('password123'), // Ganti 'password' dengan password yang Anda inginkan
            'is_admin' => true,
        ]);
    }
}