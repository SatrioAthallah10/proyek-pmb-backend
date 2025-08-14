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
            // Menambahkan kolom 'kelas' setelah kolom 'prodi_pilihan'
            // Kolom ini untuk menyimpan pilihan kelas (Pagi/Malam)
            $table->string('kelas')->nullable()->after('prodi_pilihan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('kelas');
        });
    }
};

