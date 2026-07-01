<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Model: Surat Keluar
 * 
 * Merepresentasikan surat keluar yang dikirim Bappeda.
 * 
 * @property int $id
 * @property string $nomor_surat
 * @property string $tujuan
 * @property string $perihal
 */
class SuratKeluar extends Model
{
    use SoftDeletes;

    protected $table = 'surat_keluar';

    protected $fillable = [
        'nomor_surat',
        'tanggal_surat',
        'tujuan',
        'perihal',
        'sifat_surat',
        'lampiran',
        'lampiran_nama',
        'bidang_id',
        'created_by',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_surat' => 'date',
        ];
    }

    /**
     * Relasi: Surat Keluar milik satu Bidang.
     */
    public function bidang(): BelongsTo
    {
        return $this->belongsTo(Bidang::class);
    }

    /**
     * Relasi: Surat Keluar dibuat oleh satu User.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
