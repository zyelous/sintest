<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjaman_arsip', function (Blueprint $table) {
            $table->unsignedInteger('jumlah')->default(1)->after('tanggal_rencana_kembali');
            $table->longText('paraf_peminjam')->nullable()->after('keterangan');
            $table->longText('paraf_petugas')->nullable()->after('paraf_peminjam');
        });
    }

    public function down(): void
    {
        Schema::table('peminjaman_arsip', function (Blueprint $table) {
            $table->dropColumn(['jumlah', 'paraf_peminjam', 'paraf_petugas']);
        });
    }
};