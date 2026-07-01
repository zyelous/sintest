<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Tabel Surat Keluar
 * 
 * Menyimpan data surat keluar yang dikirim oleh Bappeda.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_keluar', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat');
            $table->date('tanggal_surat');
            $table->string('tujuan');
            $table->string('perihal');
            $table->enum('sifat_surat', ['biasa', 'segera', 'sangat_segera', 'rahasia']);
            $table->string('lampiran')->nullable();
            $table->string('lampiran_nama')->nullable();
            $table->foreignId('bidang_id')->constrained('bidang')->cascadeOnDelete();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_keluar');
    }
};
