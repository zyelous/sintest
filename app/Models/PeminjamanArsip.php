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
        'tanggal_rencana_kembali',
        'tanggal_kembali',
        'jumlah',
        'paraf_peminjam',
        'paraf_petugas',
        'status',
        'keterangan',
        'created_by',
        'paraf_pengembalian',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pinjam'          => 'date',
            'tanggal_rencana_kembali' => 'date',
            'tanggal_kembali'         => 'date',
        ];
    }

    /**
     * Accessor: Durasi peminjaman dalam format human-readable (minggu, hari, jam, menit).
     */
    public function getDurasiPinjamAttribute(): string
    {
        // 1. Jika sudah dikembalikan
        if ($this->tanggal_kembali) {
            $start = $this->tanggal_pinjam->startOfDay();
            $end = $this->tanggal_kembali->startOfDay();
            $diffDays = (int) $start->diffInDays($end);
            return $diffDays === 0 ? '1 hari' : $diffDays . ' hari';
        }

        // 2. Jika masih aktif dipinjam
        $tanggalPinjamStr = $this->tanggal_pinjam ? \Carbon\Carbon::parse($this->tanggal_pinjam)->format('Y-m-d') : null;
        $createdAtStr = $this->created_at ? \Carbon\Carbon::parse($this->created_at)->format('Y-m-d') : null;

        // Jika tanggal_pinjam sama dengan tanggal created_at, gunakan created_at agar hitungan jam & menit presisi dari waktu pengajuan/pembuatan
        if ($createdAtStr && $tanggalPinjamStr === $createdAtStr) {
            $start = \Carbon\Carbon::parse($this->created_at);
        } else {
            $start = \Carbon\Carbon::parse($this->tanggal_pinjam)->startOfDay();
        }

        $end = now();

        if ($start->isFuture()) {
            return 'belum dimulai';
        }

        $diff = $start->diff($end);

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
     * Accessor: Cek apakah peminjaman ini terlambat dikembalikan.
     */
    public function getTerlambatAttribute(): bool
    {
        return $this->status === 'dipinjam'
            && $this->tanggal_rencana_kembali
            && $this->tanggal_rencana_kembali->endOfDay()->isPast();
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

    /**
     * Alias Relasi: User pembuat transaksi peminjaman.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}