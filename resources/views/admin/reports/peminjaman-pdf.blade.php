<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Peminjaman Arsip - Bappeda</title>
    <style>
        @page { size: A4 landscape; margin: 15mm; }
        body { font-family: Arial, Helvetica, sans-serif; font-size: 11px; color: #1e293b; line-height: 1.4; }
        .header { text-align: center; border-bottom: 2px solid #1b3a5c; padding-bottom: 10px; margin-bottom: 15px; }
        .header h1 { font-size: 16px; margin: 0; color: #1b3a5c; text-transform: uppercase; font-weight: 800; }
        .header h2 { font-size: 13px; margin: 3px 0 0 0; color: #334155; font-weight: 700; }
        .header p { font-size: 10px; margin: 2px 0 0 0; color: #64748b; }
        .meta { margin-bottom: 12px; font-size: 10px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        th, td { border: 1px solid #cbd5e1; padding: 6px 8px; text-align: left; }
        th { background-color: #f1f5f9; color: #1b3a5c; font-weight: bold; text-transform: uppercase; font-size: 9px; text-align: center; }
        .text-center { text-align: center; }
        .badge { display: inline-block; padding: 2px 6px; border-radius: 4px; font-size: 9px; font-weight: bold; text-transform: uppercase; }
        .badge-dipinjam { background-color: #fef3c7; color: #92400e; }
        .badge-dikembalikan { background-color: #d1fae5; color: #065f46; }
        .badge-menunggu { background-color: #dbeafe; color: #1e40af; }
        .badge-ditolak { background-color: #fee2e2; color: #991b1b; }
        .footer { margin-top: 30px; display: flex; justify-content: space-between; }
        .signature-box { width: 220px; text-align: center; float: right; margin-top: 20px; }
    </style>
</head>
<body onload="window.print()">

    <div class="header">
        <h1>PEMERINTAH PROVINSI LAMPUNG</h1>
        <h2>BADAN PERENCANAAN PEMBANGUNAN DAERAH (BAPPEDA)</h2>
        <p>Jl. Wolter Monginsidi No. 224, Teluk Betung, Bandar Lampung | Telp: (0721) 482536</p>
        <hr style="border: 0; border-top: 1px solid #1b3a5c; margin-top: 8px;">
        <h3 style="margin: 8px 0 0 0; color: #1b3a5c;">LAPORAN REKAPITULASI PEMINJAMAN ARSIP</h3>
    </div>

    <div class="meta">
        Unit Kerja / Bidang: {{ strtoupper($bidangNama) }} | Tanggal Cetak: {{ date('d F Y') }}
    </div>

    <table>
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="14%">No. Berkas</th>
                <th width="28%">Judul / Uraian Berkas Arsip</th>
                <th width="14%">Nama Peminjam</th>
                <th width="14%">Bidang Peminjam</th>
                <th width="10%">Tgl Pinjam</th>
                <th width="10%">Batas Kembali</th>
                <th width="6%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($peminjamanList as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td><strong>{{ $item->arsip?->no_berkas ?? '-' }}</strong></td>
                <td>{{ $item->arsip?->uraian_berkas ?? '-' }}</td>
                <td>{{ $item->nama_peminjam }}</td>
                <td>{{ $item->bidang_peminjam }}</td>
                <td class="text-center">{{ $item->tanggal_pinjam?->format('d/m/Y') }}</td>
                <td class="text-center">{{ $item->tanggal_rencana_kembali?->format('d/m/Y') ?? '-' }}</td>
                <td class="text-center">
                    @php
                        $badgeClass = match($item->status) {
                            'dipinjam' => 'badge-dipinjam',
                            'dikembalikan' => 'badge-dikembalikan',
                            'ditolak' => 'badge-ditolak',
                            default => 'badge-menunggu',
                        };
                    @endphp
                    <span class="badge {{ $badgeClass }}">{{ str_replace('_', ' ', $item->status) }}</span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center" style="padding: 20px; color: #94a3b8;">Belum ada data peminjaman arsip.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="signature-box">
        <p>Bandar Lampung, {{ date('d F Y') }}</p>
        <p style="margin-bottom: 60px;">Pengelola Kearsipan Bappeda</p>
        <p style="font-weight: bold; text-decoration: underline;">( .................................................. )</p>
        <p style="font-size: 9px; color: #64748b;">NIP. ..................................................</p>
    </div>

</body>
</html>
