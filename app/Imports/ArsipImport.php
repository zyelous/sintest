<?php

namespace App\Imports;

use App\Models\Arsip;
use App\Models\Bidang;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ArsipImport implements ToModel, WithHeadingRow
{
    private $rowCount = 0;
    private $bidangId;
    private $userId;

    public function __construct($bidangId = null, $userId = null)
    {
        $this->bidangId = $bidangId;
        $this->userId = $userId ?? auth()->id();
    }

    public function model(array $row)
    {
        // Skip empty rows
        $noBerkas = $row['no_berkas'] ?? $row['no_berkas_'] ?? $row['No. Berkas '] ?? null;
        $uraianBerkas = $row['uraian_informasi_berkas'] ?? $row['uraian_berkas'] ?? $row['Uraian Informasi Berkas '] ?? null;
        
        if (empty($noBerkas) && empty($uraianBerkas)) {
            return null;
        }

        $this->rowCount++;

        // Determine keamanan
        $keamanan = 'biasa';
        if (!empty($row['sangat_rahasia']) || !empty($row['sangat_rahasia_'])) {
            $keamanan = 'sangat_rahasia';
        } elseif (!empty($row['rahasia']) || !empty($row['rahasia_'])) {
            $keamanan = 'rahasia';
        } elseif (!empty($row['terbatas']) || !empty($row['terbatas_'])) {
            $keamanan = 'terbatas';
        }

        // Determine retensi
        $retensi = 'aktif';
        if (!empty($row['inaktif']) || !empty($row['inaktif_'])) {
            $retensi = 'inaktif';
        }

        // Parse date
        $tanggalRaw = $row['tanggal_diarsipkan'] ?? $row['tanggal_arsip'] ?? null;
        $tanggal = $this->parseDate($tanggalRaw);

        // Resolve Bidang
        $resolvedBidangId = $this->bidangId;
        if (!$resolvedBidangId) {
            $bidangRaw = $row['bidang'] ?? $row['nama_bidang'] ?? null;
            $resolvedBidangId = $this->resolveBidangId($bidangRaw);
        }

        return new Arsip([
            'kode_klasifikasi'      => $row['kode_klasifikasi'] ?? $row['kode_klasifikasi_'] ?? null,
            'no_berkas'             => $noBerkas,
            'uraian_berkas'         => $uraianBerkas ?? '',
            'kurun_waktu'           => $row['kurun_waktu'] ?? null,
            'jumlah_berkas'         => (int)($row['jumlah_berkas'] ?? 1),
            'no_item_arsip'         => $row['no_item_arsip'] ?? null,
            'uraian_arsip'          => $row['uraian_informasi_arsip'] ?? $row['uraian_arsip'] ?? null,
            'tanggal_diarsipkan'    => $tanggal ?? now()->format('Y-m-d'),
            'jumlah_halaman_bundle' => $this->cleanInteger($row['jumlah_halaman_map_bundle'] ?? $row['jumlah_halaman'] ?? null),
            'tingkat_perkembangan'  => $row['tingkat_perkembangan'] ?? null,
            'lokasi_simpan'         => $row['keterangan_lokasi_simpan'] ?? $row['lokasi_simpan'] ?? null,
            'no_rak'                => $row['no_rak'] ?? null,
            'no_boks'               => $row['no_boks'] ?? null,
            'no_folder'             => $row['no_folder'] ?? null,
            'klasifikasi_keamanan'  => $keamanan,
            'status_retensi'        => $retensi,
            'status_arsip'          => 'tersedia',
            'nasib_akhir'           => $row['nasib_akhir'] ?? null,
            'bidang_id'             => $resolvedBidangId,
            'user_id'               => $this->userId,
        ]);
    }

    private function cleanInteger($value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }
        $cleaned = preg_replace('/[^0-9]/', '', (string)$value);
        return $cleaned !== '' ? (int)$cleaned : null;
    }

    private function parseDate($value): ?string
    {
        if (empty($value)) {
            return null;
        }

        if ($value instanceof \DateTime) {
            return Carbon::instance($value)->format('Y-m-d');
        }

        // If Excel date serial number
        if (is_numeric($value)) {
            try {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format('Y-m-d');
            } catch (\Exception $e) {
                // Ignore
            }
        }

        $value = trim((string) $value);

        if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $value)) {
            return Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
        }

        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            return $value;
        }

        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    private function resolveBidangId($value)
    {
        if (empty($value)) {
            return Bidang::first()?->id ?? 1;
        }

        if (is_numeric($value) && Bidang::where('id', $value)->exists()) {
            return (int) $value;
        }

        $normalized = strtolower(trim((string) $value));
        $bidang = Bidang::whereRaw('LOWER(nama_bidang) = ?', [$normalized])->first();
        if (!$bidang) {
            $bidang = Bidang::where('nama_bidang', 'like', "%{$normalized}%")->first();
        }

        return $bidang?->id ?: (Bidang::first()?->id ?? 1);
    }

    public function getRowCount()
    {
        return $this->rowCount;
    }
}