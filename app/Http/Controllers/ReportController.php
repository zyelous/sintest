<?php

namespace App\Http\Controllers;

use App\Models\Arsip;
use App\Models\Bidang;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $arsipQuery = Arsip::query();

        if ($user->isOperator()) {
            $arsipQuery->where('bidang_id', $user->bidang_id);
        } elseif ($request->filled('bidang_id')) {
            $arsipQuery->where('bidang_id', $request->bidang_id);
        }

        if ($request->filled('dari')) {
            $arsipQuery->whereDate('tanggal_diarsipkan', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $arsipQuery->whereDate('tanggal_diarsipkan', '<=', $request->sampai);
        }

        $totalArsip = (clone $arsipQuery)->count();
        $arsipAktif = (clone $arsipQuery)->where('status_retensi', 'aktif')->count();
        $arsipInaktif = (clone $arsipQuery)->where('status_retensi', 'inaktif')->count();

        $bidangList = $user->isAdmin() ? Bidang::orderBy('nama_bidang')->get() : collect([$user->bidang])->filter();

        $rekap = $bidangList->map(function ($b) {
            $totalBidang = Arsip::where('bidang_id', $b->id)->count();
            $aktif = Arsip::where('bidang_id', $b->id)->where('status_retensi', 'aktif')->count();
            $inaktif = Arsip::where('bidang_id', $b->id)->where('status_retensi', 'inaktif')->count();
            $dipinjam = Arsip::where('bidang_id', $b->id)->where('status_arsip', 'dipinjam')->count();
            $thisMonth = Arsip::where('bidang_id', $b->id)->whereYear('created_at', now()->year)->whereMonth('created_at', now()->month)->count();
            $lastMonth = Arsip::where('bidang_id', $b->id)->whereYear('created_at', now()->subMonth()->year)->whereMonth('created_at', now()->subMonth()->month)->count();
            $trend = $lastMonth > 0 ? round((($thisMonth - $lastMonth) / $lastMonth) * 100) : ($thisMonth > 0 ? 100 : 0);
            return [
                'bidang' => $b->nama_bidang,
                'total' => $totalBidang,
                'aktif' => $aktif,
                'inaktif' => $inaktif,
                'dipinjam' => $dipinjam,
                'trend' => $trend,
            ];
        });

        return view('reports.index', compact('totalArsip', 'arsipAktif', 'arsipInaktif', 'rekap', 'bidangList'));
    }

    public function exportExcel(Request $request)
    {
        $user = auth()->user();
        $query = Arsip::with('bidang');

        if ($user->isOperator()) {
            $query->where('bidang_id', $user->bidang_id);
        } elseif ($request->filled('bidang_id')) {
            $query->where('bidang_id', $request->bidang_id);
        }

        $arsipList = $query->orderBy('kode_klasifikasi')->get();
        $bidang = $request->filled('bidang_id') ? Bidang::find($request->bidang_id) : null;
        $bidangNama = $bidang ? $bidang->nama_bidang : ($user->isOperator() ? $user->bidang->nama_bidang : 'SEMUA BIDANG');
        $showBidangColumn = !$bidang && !$user->isOperator();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Daftar Arsip Aktif');

        $lastCol = $showBidangColumn ? 'U' : 'T';

        // Judul
        $sheet->setCellValue('A1', 'DAFTAR ARSIP AKTIF ' . strtoupper($bidangNama));
        $sheet->mergeCells("A1:{$lastCol}1");
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Info pencipta/unit
        $sheet->setCellValue('A2', 'Pencipta Arsip');
        $sheet->setCellValue('B2', ': Bappeda Provinsi Lampung');
        $sheet->setCellValue('A3', 'Unit Pengolah');
        $sheet->setCellValue('B3', ': ' . $bidangNama);
        $sheet->setCellValue('A4', 'Unit Kearsipan');
        $sheet->setCellValue('B4', ': Sekretariat');
        $sheet->getStyle('A2:A4')->getFont()->setBold(true);

        // Header tabel (baris 6-7, merge cell)
        $headerRow1 = 6;
        $headerRow2 = 7;

        $singleCols = [
            'A' => 'Kode Klasifikasi',
            'B' => 'No. Berkas',
            'C' => 'Uraian Informasi Berkas',
            'D' => 'Kurun Waktu',
            'E' => 'Jumlah Berkas',
            'F' => 'No. Item Arsip',
            'G' => 'Uraian Informasi Arsip',
            'H' => 'Tanggal Diarsipkan',
            'I' => 'Jumlah Halaman/ Map/ Bundle',
            'J' => 'Tingkat Perkembangan',
        ];
        foreach ($singleCols as $col => $label) {
            $sheet->setCellValue("{$col}{$headerRow1}", $label);
            $sheet->mergeCells("{$col}{$headerRow1}:{$col}{$headerRow2}");
        }

        $sheet->setCellValue("K{$headerRow1}", 'Keterangan Lokasi Simpan');
        $sheet->mergeCells("K{$headerRow1}:M{$headerRow1}");
        $sheet->setCellValue("K{$headerRow2}", 'No. Rak');
        $sheet->setCellValue("L{$headerRow2}", 'No. Boks');
        $sheet->setCellValue("M{$headerRow2}", 'No. Folder');

        $sheet->setCellValue("N{$headerRow1}", 'Kategori/Klasifikasi Keamanan');
        $sheet->mergeCells("N{$headerRow1}:Q{$headerRow1}");
        $sheet->setCellValue("N{$headerRow2}", 'Biasa');
        $sheet->setCellValue("O{$headerRow2}", 'Terbatas');
        $sheet->setCellValue("P{$headerRow2}", 'Rahasia');
        $sheet->setCellValue("Q{$headerRow2}", 'Sangat Rahasia');

        $sheet->setCellValue("R{$headerRow1}", 'Retensi');
        $sheet->mergeCells("R{$headerRow1}:T{$headerRow1}");
        $sheet->setCellValue("R{$headerRow2}", 'Aktif');
        $sheet->setCellValue("S{$headerRow2}", 'Inaktif');
        $sheet->setCellValue("T{$headerRow2}", 'Nasib Akhir');

        if ($showBidangColumn) {
            $sheet->setCellValue("U{$headerRow1}", 'Bidang');
            $sheet->mergeCells("U{$headerRow1}:U{$headerRow2}");
        }

        $headerRange = "A{$headerRow1}:{$lastCol}{$headerRow2}";
        $sheet->getStyle($headerRange)->getFont()->setBold(true);
        $sheet->getStyle($headerRange)->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
            ->setWrapText(true);
        $sheet->getStyle($headerRange)->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('D9E2F3');
        $sheet->getStyle($headerRange)->getBorders()->getAllBorders()
            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        // Data
        $row = $headerRow2 + 1;
        foreach ($arsipList as $arsip) {
            $sheet->setCellValue("A{$row}", $arsip->kode_klasifikasi);
            $sheet->setCellValue("B{$row}", $arsip->no_berkas);
            $sheet->setCellValue("C{$row}", $arsip->uraian_berkas);
            $sheet->setCellValue("D{$row}", $arsip->kurun_waktu);
            $sheet->setCellValue("E{$row}", $arsip->jumlah_berkas);
            $sheet->setCellValue("F{$row}", $arsip->no_item_arsip);
            $sheet->setCellValue("G{$row}", $arsip->uraian_arsip);
            $sheet->setCellValue("H{$row}", $arsip->tanggal_diarsipkan?->format('d F Y'));
            $sheet->setCellValue("I{$row}", $arsip->jumlah_halaman_bundle);
            $sheet->setCellValue("J{$row}", $arsip->tingkat_perkembangan);
            $sheet->setCellValue("K{$row}", $arsip->no_rak);
            $sheet->setCellValue("L{$row}", $arsip->no_boks);
            $sheet->setCellValue("M{$row}", $arsip->no_folder);
            $sheet->setCellValue("N{$row}", $arsip->klasifikasi_keamanan === 'biasa');
            $sheet->setCellValue("O{$row}", $arsip->klasifikasi_keamanan === 'terbatas');
            $sheet->setCellValue("P{$row}", $arsip->klasifikasi_keamanan === 'rahasia');
            $sheet->setCellValue("Q{$row}", $arsip->klasifikasi_keamanan === 'sangat_rahasia');
            $sheet->setCellValue("R{$row}", $arsip->status_retensi === 'aktif' && empty($arsip->nasib_akhir));
            $sheet->setCellValue("S{$row}", $arsip->status_retensi === 'inaktif' && empty($arsip->nasib_akhir));
            $sheet->setCellValue("T{$row}", !empty($arsip->nasib_akhir));
            if ($showBidangColumn) {
                $sheet->setCellValue("U{$row}", $arsip->bidang?->nama_bidang);
            }
            $row++;
        }

        $lastDataRow = $row - 1;
        if ($lastDataRow >= $headerRow2 + 1) {
            $dataRange = "A" . ($headerRow2 + 1) . ":{$lastCol}{$lastDataRow}";
            $sheet->getStyle($dataRange)->getBorders()->getAllBorders()
                ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle("A" . ($headerRow2 + 1) . ":C{$lastDataRow}")->getAlignment()->setWrapText(true);
            $sheet->getStyle("G" . ($headerRow2 + 1) . ":G{$lastDataRow}")->getAlignment()->setWrapText(true);
            $sheet->getStyle("N" . ($headerRow2 + 1) . ":T{$lastDataRow}")->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        }

        // Lebar kolom
        $widths = ['A' => 14, 'B' => 20, 'C' => 40, 'D' => 12, 'E' => 10, 'F' => 14, 'G' => 30, 'H' => 16, 'I' => 12, 'J' => 16, 'K' => 8, 'L' => 8, 'M' => 8, 'N' => 8, 'O' => 9, 'P' => 9, 'Q' => 10, 'R' => 8, 'S' => 8, 'T' => 10, 'U' => 18];
        foreach ($widths as $col => $w) {
            $sheet->getColumnDimension($col)->setWidth($w);
        }
        $sheet->freezePane("A" . ($headerRow2 + 1));

        $filename = 'daftar_arsip_aktif_' . strtolower(str_replace(' ', '_', $bidangNama)) . '_' . date('Ymd') . '.xlsx';
        $tempPath = storage_path('app/temp_' . uniqid() . '.xlsx');
        (new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet))->save($tempPath);

        return response()->download($tempPath, $filename)->deleteFileAfterSend(true);
    }

    public function exportPdf(Request $request)
    {
        $user = auth()->user();
        $query = Arsip::with('bidang');

        if ($user->isOperator()) {
            $query->where('bidang_id', $user->bidang_id);
        } elseif ($request->filled('bidang_id')) {
            $query->where('bidang_id', $request->bidang_id);
        }

        $arsipList = $query->orderBy('kode_klasifikasi')->get();
        $bidang = $request->filled('bidang_id') ? Bidang::find($request->bidang_id) : null;
        $bidangNama = $bidang ? $bidang->nama_bidang : ($user->isOperator() ? $user->bidang->nama_bidang : 'SEMUA BIDANG');

        return view('reports.arsip-pdf', compact('arsipList', 'bidangNama'));
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xls,xlsx|max:10240',
        ]);

        $file = $request->file('file');
        $handle = fopen($file->getRealPath(), 'r');
        $header = null;
        $imported = 0;
        $skipped = 0;
        $lineNum = 0;

        while (($row = fgetcsv($handle)) !== false) {
            $lineNum++;
            // Skip header rows (first 6 lines typically)
            if ($lineNum <= 6) continue;

            // Skip empty rows
            if (empty(array_filter($row))) continue;

            // Expected columns: No, Kode Klasifikasi, No. Berkas, Uraian Berkas, ...
            if (count($row) < 8) {
                $skipped++;
                continue;
            }

            try {
                Arsip::create([
                    'kode_klasifikasi' => $row[1] ?? '',
                    'no_berkas' => $row[2] ?? '',
                    'uraian_berkas' => $row[3] ?? '',
                    'kurun_waktu' => $row[4] ?? null,
                    'jumlah_berkas' => $row[5] ?? '1',
                    'no_item_arsip' => $row[6] ?? null,
                    'uraian_arsip' => $row[7] ?? null,
                    'tanggal_diarsipkan' => now(),
                    'klasifikasi_keamanan' => 'biasa',
                    'status_retensi' => 'aktif',
                    'status_arsip' => 'tersedia',
                    'bidang_id' => auth()->user()->isOperator() ? auth()->user()->bidang_id : (Bidang::first()->id ?? 1),
                    'user_id' => auth()->id(),
                ]);
                $imported++;
            } catch (\Exception $e) {
                $skipped++;
            }
        }

        fclose($handle);

        return redirect()->route('arsip.index')
            ->with('success', "Import selesai: {$imported} data berhasil, {$skipped} data dilewati.");
    }
}