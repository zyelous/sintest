<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjaman_arsip', function (Blueprint $table) {
            if (!Schema::hasColumn('peminjaman_arsip', 'jumlah')) {
                $table->unsignedInteger('jumlah')->default(1)->after('tanggal_rencana_kembali');
            }
            if (!Schema::hasColumn('peminjaman_arsip', 'paraf_peminjam')) {
                $table->longText('paraf_peminjam')->nullable()->after('keterangan');
            }
            if (!Schema::hasColumn('peminjaman_arsip', 'paraf_petugas')) {
                $table->longText('paraf_petugas')->nullable()->after('paraf_peminjam');
            }
        });
    }

    public function down(): void
    {
        Schema::table('peminjaman_arsip', function (Blueprint $table) {
            $table->dropColumn(['jumlah', 'paraf_peminjam', 'paraf_petugas']);
        });
    }
};