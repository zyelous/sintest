<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model: Bidang (Unit Kerja)
 * 
 * Merepresentasikan bidang/unit kerja di Bappeda Provinsi Lampung.
 * Digunakan sebagai dasar pembatasan akses data per bidang.
 * 
 * @property int $id
 * @property string $nama_bidang
 * @property string $kode_bidang
 */
class Bidang extends Model
{
    /**
     * Nama tabel yang digunakan.
     */
    protected $table = 'bidang';

    /**
     * Kolom yang boleh diisi secara mass assignment.
     */
    protected $fillable = [
        'nama_bidang',
        'kode_bidang',
        'kepala_bidang',
        'deskripsi',
    ];

    /**
     * Relasi: Bidang memiliki banyak User (operator).
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Relasi: Operator utama di bidang ini.
     */
    public function operator(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(User::class)->where('role', 'operator');
    }

    /**
     * Relasi: Bidang memiliki banyak Arsip.
     */
    public function arsip(): HasMany
    {
        return $this->hasMany(Arsip::class);
    }
}