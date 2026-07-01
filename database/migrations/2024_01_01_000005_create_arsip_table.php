<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Tabel Arsip
 * 
 * Menyimpan data arsip dengan klasifikasi lengkap sesuai
 * standar kearsipan pemerintah daerah.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('arsip', function (Blueprint $table) {
            $table->id();
            $table->string('kode_klasifikasi');
            $table->string('no_berkas');
            $table->text('uraian_berkas');
            $table->string('kurun_waktu')->nullable();
            $table->integer('jumlah_berkas')->default(1);
            $table->string('no_item_arsip')->nullable();
            $table->text('uraian_arsip')->nullable();
            $table->date('tanggal_diarsipkan');
            $table->integer('jumlah_halaman_bundle')->nullable();
            $table->string('tingkat_perkembangan')->nullable();
            $table->text('lokasi_simpan')->nullable();
            $table->string('no_rak')->nullable();
            $table->string('no_boks')->nullable();
            $table->string('no_folder')->nullable();
            $table->enum('klasifikasi_keamanan', ['biasa', 'terbatas', 'rahasia', 'sangat_rahasia'])
                  ->default('biasa');
            $table->enum('status_retensi', ['aktif', 'inaktif'])->default('aktif');
            $table->enum('status_arsip', ['tersedia', 'dipinjam'])->default('tersedia');
            $table->string('nasib_akhir')->nullable();
            $table->string('file_arsip')->nullable();
            $table->foreignId('bidang_id')->constrained('bidang')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('arsip');
    }
};
