<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjaman_arsip', function (Blueprint $table) {
            if (!Schema::hasColumn('peminjaman_arsip', 'paraf_pengembalian')) {
                $table->longText('paraf_pengembalian')->nullable()->after('paraf_petugas');
            }
        });
    }

    public function down(): void
    {
        Schema::table('peminjaman_arsip', function (Blueprint $table) {
            if (Schema::hasColumn('peminjaman_arsip', 'paraf_pengembalian')) {
                $table->dropColumn('paraf_pengembalian');
            }
        });
    }
};