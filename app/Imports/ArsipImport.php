<?php

namespace App\Imports;

use App\Models\Arsip;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ArsipImport implements ToModel, WithHeadingRow
{
    private $rowCount = 0;

    public function model(array $row)
    {
        if (empty($row['No. Berkas ']) && empty($row['no_berkas'])) {
            return null;
        }

        $this->rowCount++;

        return new Arsip([
            'kode_klasifikasi'   => $row['Kode Klasifikasi '] ?? null,
            'no_berkas'          => $row['No. Berkas '] ?? $row['no_berkas'],
            'uraian_berkas'      => $row['Uraian Informasi Berkas '] ?? '',
            'tanggal_diarsipkan' => $row['Tanggal Diarsipkan'],
            'jumlah_halaman'     => $row['Jumlah Halaman/ Map/ Bundle'],
            'lokasi_simpan'      => $row['Keterangan Lokasi Simpan'],
            'no_rak'             => $row['No. Rak'],
            'no_boks'            => $row['No. Boks'],
            'no_folder'          => $row['No. Folder'],
            'aktif'              => true,
        ]);
    }

    public function getRowCount()
    {
        return $this->rowCount;
    }
}