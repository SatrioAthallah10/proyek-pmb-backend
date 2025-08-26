<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MenuPermission;
use Illuminate\Support\Facades\DB;

class MenuPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kosongkan tabel terlebih dahulu untuk menghindari duplikasi
        DB::table('menu_permissions')->delete();

        $permissions = [
            // Pengaturan untuk 'owner'
            ['role' => 'owner', 'menu_key' => 'dashboard', 'is_visible' => true],

            // Pengaturan untuk 'staff'
            ['role' => 'staff', 'menu_key' => 'konfirmasi-pembayaran', 'is_visible' => true],
        ];

        // Masukkan data ke database
        foreach ($permissions as $permission) {
            MenuPermission::create($permission);
        }
    }
}
