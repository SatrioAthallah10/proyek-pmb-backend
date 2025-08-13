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
            // Menambahkan kolom untuk menyimpan ID admin yang mengonfirmasi
            $table->foreignId('daful_confirmed_by')->nullable()->constrained('users')->onDelete('set null');
            
            // Menambahkan kolom untuk menyimpan waktu konfirmasi
            $table->timestamp('daful_confirmed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Menghapus foreign key constraint terlebih dahulu
            $table->dropForeign(['daful_confirmed_by']);

            // Menghapus kolom jika migrasi di-rollback
            $table->dropColumn('daful_confirmed_by');
            $table->dropColumn('daful_confirmed_at');
        });
    }
};
