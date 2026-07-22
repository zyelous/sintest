<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Arsip;
use App\Models\Bidang;
use App\Imports\ArsipImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class ArsipImportController extends Controller
{
    public function preview(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:20480'
        ]);

        $file = $request->file('file');
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, ['xlsx', 'xls', 'csv', 'xlsv'], true)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Format file tidak didukung. Gunakan .xlsx, .xls, .xlsv, atau .csv.'
            ], 422);
        }

        try {
            $sheet = $this->loadRows($file);
            if (empty($sheet)) {
                return response()->json(['status' => 'error', 'message' => 'Tidak ada data yang dapat dibaca dari file.'], 422);
            }

            $headerIndex = $this->findHeaderIndex($sheet);
            if ($headerIndex === null) {
                return response()->json(['status' => 'error', 'message' => 'Header tidak ditemukan. Pastikan file memiliki baris header yang jelas.'], 422);
            }

            $headerRow = $sheet[$headerIndex];
            
            // Build preview data as associative arrays mapping raw header strings
            $preview = [];
            $dataRows = array_slice($sheet, $headerIndex + 1);
            
            foreach ($dataRows as $row) {
                if (empty(array_filter($row))) {
                    continue;
                }
                
                $assocRow = [];
                foreach ($headerRow as $idx => $key) {
                    if ($key === null || $key === '') continue;
                    $assocRow[$key] = $row[$idx] ?? '';
                }
                $preview[] = $assocRow;
            }

            return response()->json([
                'status' => 'success',
                'data' => $preview
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:20480',
            'bidang_id' => 'nullable|exists:bidang,id',
        ]);

        $file = $request->file('file');
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, ['xlsx', 'xls', 'csv', 'xlsv'], true)) {
            return redirect()->back()->with('error', 'Format file tidak didukung. Gunakan .xlsx, .xls, .xlsv, atau .csv.');
        }

        $user = auth()->user();
        $bidangId = $user->isOperator() ? $user->bidang_id : $request->bidang_id;

        try {
            if (in_array($extension, ['xlsx', 'xls'], true)) {
                $import = new ArsipImport($bidangId, $user->id);
                Excel::import($import, $file);
                $count = $import->getRowCount();
            } else {
                // Manual parser for TSV/CSV
                $sheet = $this->loadRows($file);
                if (empty($sheet)) {
                    return redirect()->back()->with('error', 'Tidak ada data yang dapat dibaca.');
                }

                $headerIndex = $this->findHeaderIndex($sheet);
                if ($headerIndex === null) {
                    return redirect()->back()->with('error', 'Baris header tidak ditemukan.');
                }

                $headerRow = $sheet[$headerIndex];
                $headerMap = [];
                foreach ($headerRow as $idx => $val) {
                    if ($val === null || $val === '') continue;
                    $headerMap[$this->normalizeHeader((string) $val)] = $idx;
                }

                $count = 0;
                $dataRows = array_slice($sheet, $headerIndex + 1);

                foreach ($dataRows as $row) {
                    if (empty(array_filter($row))) {
                        continue;
                    }

                    $get = fn(array $aliases) => $this->getValueFromRow($row, $headerMap, $aliases);

                    $noBerkas = $get(['no berkas', 'no_berkas']);
                    $uraianBerkas = $get(['uraian informasi berkas', 'uraian_berkas']);

                    if (empty($noBerkas) && empty($uraianBerkas)) {
                        continue;
                    }

                    // Keamanan
                    $keamanan = 'biasa';
                    if ($get(['sangat rahasia', 'sangat_rahasia'])) {
                        $keamanan = 'sangat_rahasia';
                    } elseif ($get(['rahasia'])) {
                        $keamanan = 'rahasia';
                    } elseif ($get(['terbatas'])) {
                        $keamanan = 'terbatas';
                    }

                    // Retensi
                    $retensi = 'aktif';
                    if ($get(['inaktif'])) {
                        $retensi = 'inaktif';
                    }

                    // Bidang
                    $rowBidangId = $bidangId;
                    if (!$rowBidangId) {
                        $bidangVal = $get(['bidang', 'nama bidang', 'nama_bidang']);
                        $rowBidangId = $this->resolveBidangId($bidangVal);
                    }

                    Arsip::create([
                        'kode_klasifikasi'      => $get(['kode klasifikasi', 'kode_klasifikasi']),
                        'no_berkas'             => $noBerkas,
                        'uraian_berkas'         => $uraianBerkas ?? '',
                        'kurun_waktu'           => $get(['kurun waktu', 'kurun_waktu']),
                        'jumlah_berkas'         => (int)($get(['jumlah berkas', 'jumlah_berkas']) ?: 1),
                        'no_item_arsip'         => $get(['no item arsip', 'no_item_arsip']),
                        'uraian_arsip'          => $get(['uraian informasi arsip', 'uraian_arsip']),
                        'tanggal_diarsipkan'    => $this->parseExcelDate($get(['tanggal diarsipkan', 'tanggal_diarsipkan'])) ?? now()->format('Y-m-d'),
                        'jumlah_halaman_bundle' => $this->cleanInteger($get(['jumlah halaman', 'jumlah halaman map bundle', 'jumlah halaman/ map/ bundle', 'jumlah_halaman_bundle'])),
                        'tingkat_perkembangan'  => $get(['tingkat perkembangan', 'tingkat_perkembangan']),
                        'lokasi_simpan'         => $get(['keterangan lokasi simpan', 'lokasi_simpan']),
                        'no_rak'                => $get(['no rak', 'no_rak']),
                        'no_boks'               => $get(['no boks', 'no_boks']),
                        'no_folder'             => $get(['no folder', 'no_folder']),
                        'klasifikasi_keamanan'  => $keamanan,
                        'status_retensi'        => $retensi,
                        'status_arsip'          => 'tersedia',
                        'nasib_akhir'           => $get(['nasib akhir', 'nasib_akhir']),
                        'bidang_id'             => $rowBidangId,
                        'user_id'               => $user->id,
                    ]);

                    $count++;
                }
            }

            return redirect()->back()
                ->with('success', 'Berhasil mengimport ' . $count . ' data arsip!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }

    private function loadRows($file): array
    {
        $extension = strtolower($file->getClientOriginalExtension());
        if (in_array($extension, ['xlsv', 'csv'], true)) {
            $content = file_get_contents($file->getRealPath());
            $lines = preg_split('/\r\n|\r|\n/', trim($content));
            $rows = [];
            foreach ($lines as $line) {
                // Support tab delimiter for xlsv/csv
                $rows[] = str_getcsv($line, "\t");
            }
            return $rows;
        }

        return Excel::toArray([], $file)[0] ?? [];
    }

    private function findHeaderIndex(array $sheet): ?int
    {
        $bestIndex = null;
        $bestScore = -1;
        $limit = min(20, count($sheet));

        for ($i = 0; $i < $limit; $i++) {
            $row = $sheet[$i];
            if (!is_array($row)) {
                continue;
            }

            $score = 0;
            foreach ($row as $value) {
                if ($value === null || $value === '') {
                    continue;
                }
                if ($this->isKnownHeader($this->normalizeHeader((string) $value))) {
                    $score++;
                }
            }

            if ($score > $bestScore) {
                $bestScore = $score;
                $bestIndex = $i;
            }
        }

        return $bestScore > 0 ? $bestIndex : null;
    }

    private function isKnownHeader(string $header): bool
    {
        $known = [
            'kode klasifikasi',
            'no berkas',
            'uraian informasi berkas',
            'kurun waktu',
            'jumlah berkas',
            'no item arsip',
            'uraian informasi arsip',
            'tanggal diarsipkan',
            'jumlah halaman map bundle',
            'tingkat perkembangan',
            'keterangan lokasi simpan',
            'no rak',
            'no boks',
            'no folder',
            'biasa',
            'terbatas',
            'rahasia',
            'sangat rahasia',
            'aktif',
            'inaktif',
            'nasib akhir',
        ];

        foreach ($known as $key) {
            if ($header === $key || str_contains($header, $key)) {
                return true;
            }
        }

        return false;
    }

    private function normalizeHeader(string $value): string
    {
        return strtolower(trim(preg_replace('/[^a-z0-9]+/i', ' ', $value)));
    }

    private function getValueFromRow(array $row, array $headerMap, array $aliases)
    {
        foreach ($aliases as $alias) {
            $key = $this->normalizeHeader($alias);
            if (isset($headerMap[$key]) && array_key_exists($headerMap[$key], $row)) {
                return trim((string) $row[$headerMap[$key]]);
            }
        }
        return null;
    }

    private function parseExcelDate($value): ?string
    {
        if (empty($value)) {
            return null;
        }

        if ($value instanceof \DateTime) {
            return Carbon::instance($value)->format('Y-m-d');
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

    private function cleanInteger($value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }
        $cleaned = preg_replace('/[^0-9]/', '', (string)$value);
        return $cleaned !== '' ? (int)$cleaned : null;
    }
}