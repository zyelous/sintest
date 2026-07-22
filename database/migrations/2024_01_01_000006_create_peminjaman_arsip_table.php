<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Tabel Peminjaman Arsip
 * 
 * Mencatat transaksi peminjaman dan pengembalian arsip.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peminjaman_arsip', function (Blueprint $table) {
            $table->id();
            $table->foreignId('arsip_id')->constrained('arsip')->cascadeOnDelete();
            $table->string('nama_peminjam');
            $table->string('bidang_peminjam');
            $table->date('tanggal_pinjam');
            $table->date('tanggal_kembali')->nullable();
            $table->enum('status', ['dipinjam','dikembalikan','terlambat'])->default('dipinjam');            $table->text('keterangan')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman_arsip');
    }
};