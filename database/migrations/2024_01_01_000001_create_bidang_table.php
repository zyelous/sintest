<?php

/**
 * SINTARA - Sistem Informasi Tata Naskah dan Arsip
 * Bappeda Provinsi Lampung
 *
 * Migration: Tabel Bidang
 * Menyimpan data bidang/unit kerja di Bappeda Provinsi Lampung.
 * Tabel ini menjadi referensi utama untuk pembagian akses operator.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Membuat tabel bidang untuk menyimpan data unit kerja.
     */
    public function up(): void
    {
        Schema::create('bidang', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bidang', 100);
            $table->string('kode_bidang', 20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bidang');
    }
};
