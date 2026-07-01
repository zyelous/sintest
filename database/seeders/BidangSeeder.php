<?php

namespace Database\Seeders;

use App\Models\Bidang;
use Illuminate\Database\Seeder;

class BidangSeeder extends Seeder
{
    public function run(): void
    {
        $bidangList = [
            ['nama_bidang' => 'EKONOMI', 'kode_bidang' => 'EKO'],
            ['nama_bidang' => 'P3M', 'kode_bidang' => 'P3M'],
            ['nama_bidang' => 'PIK', 'kode_bidang' => 'PIK'],
            ['nama_bidang' => 'PMPEP', 'kode_bidang' => 'PMP'],
            ['nama_bidang' => 'SEKRETARIAT', 'kode_bidang' => 'SEK'],
            ['nama_bidang' => 'UPTD', 'kode_bidang' => 'UPT'],
        ];

        foreach ($bidangList as $bidang) {
            Bidang::firstOrCreate(['kode_bidang' => $bidang['kode_bidang']], $bidang);
        }
    }
}
