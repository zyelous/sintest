<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bidang', function (Blueprint $table) {
            $table->string('kepala_bidang', 150)->nullable()->after('kode_bidang');
            $table->text('deskripsi')->nullable()->after('kepala_bidang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bidang', function (Blueprint $table) {
            $table->dropColumn(['kepala_bidang', 'deskripsi']);
        });
    }
};
