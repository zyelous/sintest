<?php

/**
 * SINTARA - Sistem Informasi Tata Naskah dan Arsip
 * Bappeda Provinsi Lampung
 *
 * Migration: Tabel Users (Custom)
 * Menggantikan migration users bawaan Laravel.
 * Menambahkan kolom username, role (admin/operator),
 * relasi bidang_id, dan flag is_active.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Membuat tabel users dengan kolom-kolom khusus SINTARA.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'operator'])->default('operator');
            $table->foreignId('bidang_id')->nullable()->constrained('bidang')->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
