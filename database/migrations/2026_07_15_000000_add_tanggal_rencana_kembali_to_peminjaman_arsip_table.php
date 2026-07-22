<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Nambah kolom tanggal_rencana_kembali (tanggal janji balik yang diisi
 * pas awal peminjaman), sesuai Buku Peminjaman Arsip fisik.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('peminjaman_arsip', 'tanggal_rencana_kembali')) {
            Schema::table('peminjaman_arsip', function (Blueprint $table) {
                $table->date('tanggal_rencana_kembali')->nullable()->after('tanggal_pinjam');
            });
        }
    }

    public function down(): void
    {
        Schema::table('peminjaman_arsip', function (Blueprint $table) {
            $table->dropColumn('tanggal_rencana_kembali');
        });
    }
};