<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model: Peminjaman Arsip
 * 
 * Mencatat transaksi peminjaman dan pengembalian arsip.
 * 
 * @property int $id
 * @property string $nama_peminjam
 * @property string $status
 */
class PeminjamanArsip extends Model
{
    protected $table = 'peminjaman_arsip';

    protected $fillable = [
        'arsip_id',
        'nama_peminjam',
        'bidang_peminjam',
        'tanggal_pinjam',
        'tanggal_kembali',
        'status',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pinjam'   => 'date',
            'tanggal_kembali'  => 'date',
        ];
    }

    /**
     * Relasi: Peminjaman milik satu Arsip.
     */
    public function arsip(): BelongsTo
    {
        return $this->belongsTo(Arsip::class);
    }
}
