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
        Schema::table('obat', function (Blueprint $table) {
            $table->integer('stok')->default(0)->after('harga');
        });

        // Tambah kolom jumlah di tabel detail_periksa
        Schema::table('detail_periksa', function (Blueprint $table) {
            $table->integer('jumlah')->default(1)->after('id_obat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Hapus kolom stok dari tabel obat
        Schema::table('obat', function (Blueprint $table) {
            $table->dropColumn('stok');
        });

        // Hapus kolom jumlah dari tabel detail_periksa
        Schema::table('detail_periksa', function (Blueprint $table) {
            $table->dropColumn('jumlah');
        });
    }
};
