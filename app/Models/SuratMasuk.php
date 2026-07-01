<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Model: Surat Masuk
 * 
 * Merepresentasikan surat masuk yang diterima Bappeda.
 * 
 * @property int $id
 * @property string $nomor_surat
 * @property string $pengirim
 * @property string $perihal
 * @property string $status
 */
class SuratMasuk extends Model
{
    use SoftDeletes;

    protected $table = 'surat_masuk';

    protected $fillable = [
        'nomor_surat',
        'tanggal_surat',
        'tanggal_diterima',
        'pengirim',
        'perihal',
        'sifat_surat',
        'lampiran',
        'lampiran_nama',
        'status',
        'bidang_id',
        'created_by',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_surat'    => 'date',
            'tanggal_diterima' => 'date',
        ];
    }

    /**
     * Relasi: Surat Masuk milik satu Bidang.
     */
    public function bidang(): BelongsTo
    {
        return $this->belongsTo(Bidang::class);
    }

    /**
     * Relasi: Surat Masuk dibuat oleh satu User.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
