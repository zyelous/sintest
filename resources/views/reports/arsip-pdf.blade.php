<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Arsip Aktif - {{ $bidangNama }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Times New Roman', serif; font-size: 11pt; color: #333; padding: 20px; }
        .header { text-align: center; margin-bottom: 24px; border-bottom: 3px double #333; padding-bottom: 16px; }
        .header h1 { font-size: 14pt; margin-bottom: 4px; }
        .header h2 { font-size: 12pt; font-weight: normal; margin-bottom: 4px; }
        .header p { font-size: 10pt; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; font-size: 9pt; }
        th, td { border: 1px solid #999; padding: 4px 6px; text-align: left; }
        th { background: #1B3A5C; color: white; font-weight: 600; }
        tr:nth-child(even) { background: #f9f9f9; }
        .footer { margin-top: 24px; text-align: right; font-size: 10pt; }
        @media print { body { padding: 0; } @page { size: landscape; margin: 1cm; } }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <h1>DAFTAR ARSIP AKTIF SEKRETARIAT</h1>
        <h2>BAPPEDA PROVINSI LAMPUNG</h2>
        <p>Unit Pengolah: {{ $bidangNama }} | Tanggal Cetak: {{ date('d/m/Y') }}</p>
    </div>
    <table>
        <thead>
            <tr>
                <th>No</th><th>Kode Klasifikasi</th><th>No. Berkas</th><th>Uraian Berkas</th>
                <th>Kurun Waktu</th><th>Jml</th><th>No. Item</th><th>Uraian Arsip</th>
                <th>Tgl Arsip</th><th>Halaman/Bundle</th><th>Tk. Perkembangan</th>
                <th>Lokasi</th><th>Rak</th><th>Boks</th><th>Folder</th>
                <th>Keamanan</th><th>Retensi</th><th>Nasib Akhir</th><th>Umur</th>
            </tr>
        </thead>
        <tbody>
            @foreach($arsipList as $i => $a)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $a->kode_klasifikasi }}</td>
                <td>{{ $a->no_berkas }}</td>
                <td>{{ Str::limit($a->uraian_berkas, 40) }}</td>
                <td>{{ $a->kurun_waktu }}</td>
                <td>{{ $a->jumlah_berkas }}</td>
                <td>{{ $a->no_item_arsip }}</td>
                <td>{{ Str::limit($a->uraian_arsip, 30) }}</td>
                <td>{{ $a->tanggal_diarsipkan?->format('d/m/Y') }}</td>
                <td>{{ $a->jumlah_halaman_bundle }}</td>
                <td>{{ $a->tingkat_perkembangan }}</td>
                <td>{{ $a->lokasi_simpan }}</td>
                <td>{{ $a->no_rak }}</td>
                <td>{{ $a->no_boks }}</td>
                <td>{{ $a->no_folder }}</td>
                <td>{{ ucfirst(str_replace('_',' ',$a->klasifikasi_keamanan)) }}</td>
                <td>{{ ucfirst($a->status_retensi) }}</td>
                <td>{{ $a->nasib_akhir }}</td>
                <td>{{ $a->umur_arsip }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="footer">
        <p>Lampung, {{ date('d F Y') }}</p><br><br><br>
        <p>(_________________________)</p>
        <p>NIP.</p>
    </div>
</body>
</html>
