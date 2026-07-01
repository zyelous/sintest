<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Tabel Surat Masuk
 * 
 * Menyimpan data surat masuk yang diterima oleh Bappeda.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_masuk', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat');
            $table->date('tanggal_surat');
            $table->date('tanggal_diterima');
            $table->string('pengirim');
            $table->string('perihal');
            $table->enum('sifat_surat', ['biasa', 'segera', 'sangat_segera', 'rahasia']);
            $table->string('lampiran')->nullable();          // file path
            $table->string('lampiran_nama')->nullable();      // original filename
            $table->enum('status', ['belum_didisposisi', 'sudah_didisposisi', 'diproses', 'selesai'])
                  ->default('belum_didisposisi');
            $table->foreignId('bidang_id')->constrained('bidang')->cascadeOnDelete();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_masuk');
    }
};
