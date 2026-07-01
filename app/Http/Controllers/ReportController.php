<?php

namespace App\Http\Controllers;

use App\Models\Arsip;
use App\Models\Bidang;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $arsipQuery = Arsip::query();
        $suratMasukQuery = SuratMasuk::query();
        $suratKeluarQuery = SuratKeluar::query();

        if ($user->isOperator()) {
            $arsipQuery->where('bidang_id', $user->bidang_id);
            $suratMasukQuery->where('bidang_id', $user->bidang_id);
            $suratKeluarQuery->where('bidang_id', $user->bidang_id);
        } elseif ($request->filled('bidang_id')) {
            $arsipQuery->where('bidang_id', $request->bidang_id);
            $suratMasukQuery->where('bidang_id', $request->bidang_id);
            $suratKeluarQuery->where('bidang_id', $request->bidang_id);
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

        $rekap = $bidangList->map(function ($b) use ($user) {
            $masuk = SuratMasuk::where('bidang_id', $b->id)->count();
            $keluar = SuratKeluar::where('bidang_id', $b->id)->count();
            $thisMonth = Arsip::where('bidang_id', $b->id)->whereYear('created_at', now()->year)->whereMonth('created_at', now()->month)->count();
            $lastMonth = Arsip::where('bidang_id', $b->id)->whereYear('created_at', now()->subMonth()->year)->whereMonth('created_at', now()->subMonth()->month)->count();
            $trend = $lastMonth > 0 ? round((($thisMonth - $lastMonth) / $lastMonth) * 100) : ($thisMonth > 0 ? 100 : 0);
            return [
                'bidang' => $b->nama_bidang,
                'masuk' => $masuk,
                'keluar' => $keluar,
                'total' => $masuk + $keluar,
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

        // Generate CSV as simple Excel-compatible export
        $filename = 'daftar_arsip_aktif_' . strtolower(str_replace(' ', '_', $bidangNama)) . '_' . date('Ymd') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($arsipList, $bidangNama) {
            $file = fopen('php://output', 'w');
            // BOM for Excel UTF-8
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header info
            fputcsv($file, ['DAFTAR ARSIP AKTIF SEKRETARIAT']);
            fputcsv($file, ['Pencipta Arsip: Bappeda Provinsi Lampung']);
            fputcsv($file, ['Unit Pengolah: ' . $bidangNama]);
            fputcsv($file, ['Tanggal Export: ' . date('d/m/Y')]);
            fputcsv($file, []);

            // Column headers
            fputcsv($file, [
                'No', 'Kode Klasifikasi', 'No. Berkas', 'Uraian Berkas',
                'Kurun Waktu', 'Jumlah Berkas', 'No. Item Arsip', 'Uraian Arsip',
                'Tgl Diarsipkan', 'Jml Halaman/Bundle', 'Tingkat Perkembangan',
                'Lokasi Simpan', 'No. Rak', 'No. Boks', 'No. Folder',
                'Klasifikasi Keamanan', 'Status Retensi', 'Nasib Akhir',
                'Umur Arsip', 'Bidang'
            ]);

            foreach ($arsipList as $i => $arsip) {
                fputcsv($file, [
                    $i + 1,
                    $arsip->kode_klasifikasi,
                    $arsip->no_berkas,
                    $arsip->uraian_berkas,
                    $arsip->kurun_waktu,
                    $arsip->jumlah_berkas,
                    $arsip->no_item_arsip,
                    $arsip->uraian_arsip,
                    $arsip->tanggal_diarsipkan?->format('d/m/Y'),
                    $arsip->jumlah_halaman_bundle,
                    $arsip->tingkat_perkembangan,
                    $arsip->lokasi_simpan,
                    $arsip->no_rak,
                    $arsip->no_boks,
                    $arsip->no_folder,
                    strtoupper(str_replace('_', ' ', $arsip->klasifikasi_keamanan)),
                    ucfirst($arsip->status_retensi),
                    $arsip->nasib_akhir,
                    $arsip->umur_arsip,
                    $arsip->bidang?->nama_bidang,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
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
