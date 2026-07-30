<?php

namespace App\Helpers;

class KlasifikasiArsip
{
    /**
     * Daftar Kode Klasifikasi Kearsipan Pemda / Bappeda (Standar Permendagri & ANRI)
     */
    public static function getList(): array
    {
        return [
            // 000 - UMUM
            ['kode' => '000', 'nama' => 'UMUM', 'keterangan' => 'Urusan Umum, Ketatausahaan & Kebijakan Umum'],
            ['kode' => '005', 'nama' => 'Undangan', 'keterangan' => 'Undangan Acara Rapat, Peresmian, dan Kegiatan Resmi'],
            ['kode' => '010', 'nama' => 'Peralatan & Perlengkapan', 'keterangan' => 'Pengadaan, Pengelolaan & Pemeliharaan Aset'],
            ['kode' => '020', 'nama' => 'Pemeriksaan Kas/Audit', 'keterangan' => 'Pengawasan Internal & Laporan Audit'],
            ['kode' => '040', 'nama' => 'Perpustakaan & Dokumentasi', 'keterangan' => 'Pengelolaan Buku, Publikasi & Dokumentasi Daerah'],
            ['kode' => '050', 'nama' => 'PERENCANAAN', 'keterangan' => 'Perencanaan Pembangunan Daerah, Musrenbang, RKPD, RPJMD'],
            ['kode' => '050.1', 'nama' => 'Rencana Pembangunan Jangka Panjang (RPJP)', 'keterangan' => 'Dokumen Perencanaan 20 Tahunan Daerah'],
            ['kode' => '050.2', 'nama' => 'Rencana Pembangunan Jangka Menengah (RPJM)', 'keterangan' => 'Dokumen Perencanaan 5 Tahunan Daerah'],
            ['kode' => '050.3', 'nama' => 'Rencana Kerja Pemerintah Daerah (RKPD)', 'keterangan' => 'Dokumen Perencanaan Tahunan Daerah'],
            ['kode' => '050.4', 'nama' => 'Musrenbang Daerah', 'keterangan' => 'Musyawarah Perencanaan Pembangunan (Desa s/d Provinsi)'],
            ['kode' => '050.5', 'nama' => 'Evaluasi & Pelaporan Perencanaan', 'keterangan' => 'Monev Kinerja Pembangunan Daerah & Pokir DPRD'],

            // 100 - PEMERINTAHAN
            ['kode' => '100', 'nama' => 'PEMERINTAHAN', 'keterangan' => 'Urusan Pemerintah Umum & Otonomi Daerah'],
            ['kode' => '120', 'nama' => 'Organisasi & Kelembagaan', 'keterangan' => 'Penataan Struktur Organisasi & SOTK'],
            ['kode' => '130', 'nama' => 'Tatalaksana & Prosedur Kerja', 'keterangan' => 'SOP, Standar Pelayanan & Tata Naskah Dinas'],
            ['kode' => '180', 'nama' => 'Hukum & Perundang-undangan', 'keterangan' => 'Perda, Pergub, Keputusan Kepala Badan & MOU/Kerjasama'],

            // 200 - POLITIK / PEMBERDAYAAN
            ['kode' => '200', 'nama' => 'POLITIK / KESBANGPOL', 'keterangan' => 'Kesatuan Bangsa, Ormas & Kemasyarakatan'],

            // 300 - KEAMANAN & KETERTIBAN
            ['kode' => '300', 'nama' => 'KEAMANAN & KETERTIBAN', 'keterangan' => 'Ketertiban Umum & Satpol PP'],

            // 400 - KESEJAHTERAAN RAKYAT
            ['kode' => '400', 'nama' => 'KESEJAHTERAAN RAKYAT', 'keterangan' => 'Pendidikan, Kesehatan, Agama & Sosial'],
            ['kode' => '410', 'nama' => 'Pembangunan Desa & Pemberdayaan', 'keterangan' => 'Pemberdayaan Masyarakat Desa & Kawasan'],
            ['kode' => '420', 'nama' => 'Pendidikan & Kebudayaan', 'keterangan' => 'Program Pendidikan & Pengembangan SDM'],
            ['kode' => '440', 'nama' => 'Kesehatan & Gizi', 'keterangan' => 'Program Penanganan Stunting & Pelayanan Kesehatan'],

            // 500 - PEREKONOMIAN
            ['kode' => '500', 'nama' => 'PEREKONOMIAN', 'keterangan' => 'Perdagangan, Perindustrian, Koperasi & Penanaman Modal'],
            ['kode' => '510', 'nama' => 'Perdagangan & Pasar', 'keterangan' => 'Pengembangan Perdagangan & Distribusi Logistik'],
            ['kode' => '520', 'nama' => 'Pertanian, Perkebunan & Ketahanan Pangan', 'keterangan' => 'Program Pangan Daerah & Komoditas Unggulan'],
            ['kode' => '530', 'nama' => 'Kehutanan & Lingkungan Hidup', 'keterangan' => 'Pengelolaan Lingkungan & Konservasi'],

            // 600 - PEKERJAAN UMUM & PENATAAN RUANG
            ['kode' => '600', 'nama' => 'PEKERJAAN UMUM & PENATAAN RUANG', 'keterangan' => 'Infrastruktur, Penataan Ruang (RTRW) & Sumber Daya Air'],
            ['kode' => '601', 'nama' => 'Rencana Tata Ruang Wilayah (RTRW)', 'keterangan' => 'Dokumen Tata Ruang & Zonasi Daerah'],
            ['kode' => '620', 'nama' => 'Jalan & Jembatan', 'keterangan' => 'Perencanaan & Konektivitas Infrastruktur Jalan'],

            // 700 - PENGAWASAN
            ['kode' => '700', 'nama' => 'PENGAWASAN', 'keterangan' => 'Inspektorat, BPK, BPKP & Pengawasan Daerah'],

            // 800 - KEPEGAWAIAN
            ['kode' => '800', 'nama' => 'KEPEGAWAIAN', 'keterangan' => 'Manajemen ASN, SK Jabatan, Kenaikan Pangkat & Cuti'],
            ['kode' => '810', 'nama' => 'Pengadaan & Formasi Pegawai', 'keterangan' => 'Formasi CPNS/PPPK & Analisis Beban Kerja (ABK)'],
            ['kode' => '820', 'nama' => 'Mutasi, Promosi & Penugasan', 'keterangan' => 'SK Mutasi, Penugasan & Pelatihan Pegawai'],

            // 900 - KEUANGAN
            ['kode' => '900', 'nama' => 'KEUANGAN', 'keterangan' => 'Anggaran, APBD, DPA, SPM, SP2D & Laporan Keuangan'],
            ['kode' => '901', 'nama' => 'APBD & RKA/DPA', 'keterangan' => 'Rencana Kerja Anggaran & Dokumen Pelaksanaan Anggaran Bappeda'],
            ['kode' => '903', 'nama' => 'Verifikasi & Pertanggungjawaban (SPJ)', 'keterangan' => 'SPJ Keuangan, Nota Pencairan & Bukti Transaksi'],
            ['kode' => '910', 'nama' => 'KAS & Pembukuan', 'keterangan' => 'Laporan Kas Bendahara & Pembukuan Keuangan'],
        ];
    }

    /**
     * Cari kode berdasarkan substring (kode / nama / keterangan)
     */
    public static function search(string $query): array
    {
        $query = strtolower(trim($query));
        if ($query === '') {
            return self::getList();
        }

        return array_values(array_filter(self::getList(), function ($item) use ($query) {
            return str_contains(strtolower($item['kode']), $query)
                || str_contains(strtolower($item['nama']), $query)
                || str_contains(strtolower($item['keterangan']), $query);
        }));
    }
}
