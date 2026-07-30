<?php

namespace Tests\Unit;

use App\Models\PeminjamanArsip;
use Carbon\Carbon;
use Tests\TestCase;

class PeminjamanArsipTest extends TestCase
{
    public function test_durasi_pinjam_baru_saja_dipinjam_menampilkan_kurang_dari_1_menit()
    {
        $now = Carbon::parse('2026-07-29 09:15:00');
        Carbon::setTestNow($now);

        $peminjaman = new PeminjamanArsip([
            'tanggal_pinjam' => '2026-07-29',
            'status' => 'dipinjam',
        ]);
        $peminjaman->created_at = $now;

        $this->assertEquals('kurang dari 1 menit', $peminjaman->durasi_pinjam);
    }

    public function test_durasi_pinjam_setelah_2_jam_10_menit()
    {
        $created = Carbon::parse('2026-07-29 09:15:00');
        $now = Carbon::parse('2026-07-29 11:25:00');
        Carbon::setTestNow($now);

        $peminjaman = new PeminjamanArsip([
            'tanggal_pinjam' => '2026-07-29',
            'status' => 'dipinjam',
        ]);
        $peminjaman->created_at = $created;

        $this->assertEquals('2 jam, 10 menit', $peminjaman->durasi_pinjam);
    }

    public function test_terlambat_hanya_jika_sudah_melewati_hari_rencana_kembali()
    {
        $now = Carbon::parse('2026-07-29 14:00:00');
        Carbon::setTestNow($now);

        // Hari ini adalah rencana kembali -> belum terlambat
        $peminjamanHariIni = new PeminjamanArsip([
            'tanggal_pinjam' => '2026-07-25',
            'tanggal_rencana_kembali' => '2026-07-29',
            'status' => 'dipinjam',
        ]);
        $this->assertFalse($peminjamanHariIni->terlambat);

        // Rencana kembali kemarin -> terlambat
        $peminjamanKemarin = new PeminjamanArsip([
            'tanggal_pinjam' => '2026-07-25',
            'tanggal_rencana_kembali' => '2026-07-28',
            'status' => 'dipinjam',
        ]);
        $this->assertTrue($peminjamanKemarin->terlambat);
    }
}
