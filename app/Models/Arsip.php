<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Model: Arsip
 * 
 * Menyimpan data arsip dengan klasifikasi lengkap sesuai
 * standar kearsipan pemerintah daerah.
 * 
 * @property int $id
 * @property string $kode_klasifikasi
 * @property string $no_berkas
 * @property string $uraian_berkas
 * @property string $status_retensi
 * @property string $status_arsip
 */
class Arsip extends Model
{
    use SoftDeletes;

    protected $table = 'arsip';

    protected $fillable = [
        'kode_klasifikasi',
        'no_berkas',
        'uraian_berkas',
        'kurun_waktu',
        'jumlah_berkas',
        'no_item_arsip',
        'uraian_arsip',
        'tanggal_diarsipkan',
        'jumlah_halaman_bundle',
        'tingkat_perkembangan',
        'lokasi_simpan',
        'no_rak',
        'no_boks',
        'no_folder',
        'klasifikasi_keamanan',
        'status_retensi',
        'status_arsip',
        'nasib_akhir',
        'file_arsip',
        'bidang_id',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_diarsipkan' => 'date',
        ];
    }

    /**
     * Relasi: Arsip milik satu Bidang.
     */
    public function bidang(): BelongsTo
    {
        return $this->belongsTo(Bidang::class);
    }

    /**
     * Relasi: Arsip dibuat oleh satu User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: Arsip memiliki banyak Peminjaman.
     */
    public function peminjaman(): HasMany
    {
        return $this->hasMany(PeminjamanArsip::class);
    }

    /**
     * Accessor: Hitung umur arsip day-to-day dari tanggal_diarsipkan.
     * Mengembalikan string seperti '2 tahun 3 bulan 15 hari'
     */
    public function getUmurArsipAttribute(): string
    {
        if (!$this->tanggal_diarsipkan) return '-';

        $start = \Carbon\Carbon::parse($this->tanggal_diarsipkan);
        $now = \Carbon\Carbon::now();
        $diff = $start->diff($now);

        $parts = [];
        if ($diff->y > 0) $parts[] = $diff->y . ' tahun';
        if ($diff->m > 0) $parts[] = $diff->m . ' bulan';
        $parts[] = $diff->d . ' hari';

        return implode(' ', $parts);
    }

    /**
     * Accessor: Total hari umur arsip (untuk color coding badge).
     */
    public function getUmurHariAttribute(): int
    {
        if (!$this->tanggal_diarsipkan) return 0;
        return \Carbon\Carbon::parse($this->tanggal_diarsipkan)->diffInDays(now());
    }

    /**
     * Cek apakah arsip sedang dipinjam.
     */
    public function sedangDipinjam(): bool
    {
        return $this->status_arsip === 'dipinjam';
    }
}
