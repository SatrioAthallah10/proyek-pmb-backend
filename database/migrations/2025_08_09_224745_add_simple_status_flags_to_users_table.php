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
                $table->boolean('pendaftaran_awal')->default(false)->after('name');
                $table->boolean('pembayaran')->default(false)->after('pendaftaran_awal');
                $table->boolean('daftar_ulang')->default(false)->after('pembayaran');
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn(['pendaftaran_awal', 'pembayaran', 'daftar_ulang']);
            });
        }
    };
    