<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Nambah alur persetujuan ke Peminjaman Arsip.
 * Status lama: dipinjam, dikembalikan
 * Status baru: menunggu_persetujuan, dipinjam, ditolak, dikembalikan
 *
 * Data lama yang statusnya 'dipinjam' dianggap sudah disetujui (tetap 'dipinjam').
 * Data lama yang statusnya 'dikembalikan' tetap 'dikembalikan'.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE peminjaman_arsip MODIFY status ENUM('menunggu_persetujuan', 'dipinjam', 'ditolak', 'dikembalikan') NOT NULL DEFAULT 'menunggu_persetujuan'");
    }

    public function down(): void
    {
        DB::statement("UPDATE peminjaman_arsip SET status = 'dipinjam' WHERE status IN ('menunggu_persetujuan', 'ditolak')");
        DB::statement("ALTER TABLE peminjaman_arsip MODIFY status ENUM('dipinjam', 'dikembalikan') NOT NULL DEFAULT 'dipinjam'");
    }
};