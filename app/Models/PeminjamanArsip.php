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
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pinjam'   => 'date',
            'tanggal_kembali'  => 'date',
        ];
    }

    /**
     * Accessor: Durasi peminjaman dalam format human-readable (minggu, hari, jam, menit).
     */
    public function getDurasiPinjamAttribute(): string
    {
        $end = $this->tanggal_kembali ?? now();
        $diff = $this->tanggal_pinjam->diff($end);

        $totalDays = $diff->days;
        $weeks = floor($totalDays / 7);
        $days = $totalDays % 7;
        $hours = $diff->h;
        $minutes = $diff->i;

        $parts = [];
        if ($weeks > 0) {
            $parts[] = $weeks . ' minggu';
        }
        if ($days > 0) {
            $parts[] = $days . ' hari';
        }
        if ($hours > 0) {
            $parts[] = $hours . ' jam';
        }
        if ($weeks == 0 && $days == 0 && $minutes > 0) {
            $parts[] = $minutes . ' menit';
        }

        if (empty($parts)) {
            return 'kurang dari 1 menit';
        }

        return implode(', ', $parts);
    }

    /**
     * Relasi: Peminjaman milik satu Arsip.
     */
    public function arsip(): BelongsTo
    {
        return $this->belongsTo(Arsip::class);
    }

    /**
     * Relasi: Peminjaman dibuat oleh satu User.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
