<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

/**
 * Modul Surat Masuk/Surat Keluar dikeluarkan dari scope Modul Pengelolaan Arsip
 * (tidak ada di ERD & Use Case final). Migration ini drop tabelnya, tapi file
 * migration lama (create_surat_masuk_table, create_surat_keluar_table) TIDAK
 * dihapus agar riwayat migration tetap konsisten.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('surat_keluar');
    }

    public function down(): void
    {
        // Tidak ada rollback otomatis — jalankan ulang migration lama
        // (2024_01_01_000003_create_surat_masuk_table dan
        // 2024_01_01_000004_create_surat_keluar_table) jika perlu mengembalikan tabel.
    }
};