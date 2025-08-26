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
        Schema::create('menu_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('role'); // Contoh: 'owner', 'staff'
            $table->string('menu_key'); // Contoh: 'dashboard', 'konfirmasi-pembayaran'
            $table->boolean('is_visible')->default(false);
            $table->timestamps();

            // Menambahkan unique constraint untuk memastikan tidak ada duplikasi
            $table->unique(['role', 'menu_key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_permissions');
    }
};
