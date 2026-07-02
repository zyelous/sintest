<?php

namespace App\Http\Controllers;

use App\Models\Arsip;
use App\Models\Bidang;
use App\Models\SuratMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class SuratMasukController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = SuratMasuk::with(['bidang', 'creator']);

        if ($user->isOperator()) {
            $query->where('bidang_id', $user->bidang_id);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('nomor_surat', 'like', "%{$s}%")
                  ->orWhere('pengirim', 'like', "%{$s}%")
                  ->orWhere('perihal', 'like', "%{$s}%");
            });
        }
        if ($request->filled('bidang_id') && $user->isAdmin()) {
            $query->where('bidang_id', $request->bidang_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('sifat_surat')) {
            $query->where('sifat_surat', $request->sifat_surat);
        }
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_surat', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_surat', '<=', $request->tanggal_sampai);
        }

        $suratMasuk = $query->latest()->paginate(10)->withQueryString();
        $bidangList = Bidang::orderBy('nama_bidang')->get();

        return view('surat-masuk.index', compact('suratMasuk', 'bidangList'));
    }

    public function create()
    {
        $bidangList = Bidang::orderBy('nama_bidang')->get();
        return view('surat-masuk.create', compact('bidangList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomor_surat' => 'required|string|max:100',
            'tanggal_surat' => 'required|date',
            'tanggal_diterima' => 'required|date',
            'pengirim' => 'required|string|max:255',
            'perihal' => 'required|string',
            'sifat_surat' => 'required|in:biasa,segera,sangat_segera,rahasia',
            'lampiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'status' => 'required|in:diteruskan,diarsipkan',
            'bidang_id' => 'required|exists:bidang,id',
            'catatan' => 'nullable|string',
        ]);

        $data = $request->except('lampiran');
        $data['created_by'] = auth()->id();

        if (auth()->user()->isOperator()) {
            $data['bidang_id'] = auth()->user()->bidang_id;
        }

        if ($request->hasFile('lampiran')) {
            $file = $request->file('lampiran');
            $data['lampiran'] = $file->store('surat-masuk', 'public');
            $data['lampiran_nama'] = $file->getClientOriginalName();
        }

        SuratMasuk::create($data);
        return redirect()->route('surat-masuk.index')->with('success', 'Surat masuk berhasil ditambahkan.');
    }

    public function previewImport(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:20480'
        ]);

        $file = $request->file('file');
        if (!$this->isAllowedSpreadsheet($file)) {
            return response()->json(['status' => 'error', 'message' => 'Format file tidak didukung. Gunakan .xlsx, .xls, .xlsv, atau .csv.'], 422);
        }

        try {
            $sheet = $this->loadSpreadsheetRows($file);
            if (empty($sheet)) {
                return response()->json(['status' => 'error', 'message' => 'Tidak ada data yang dapat dibaca dari file Excel.'], 422);
            }

            $headerIndex = $this->findHeaderIndex($sheet);
            if ($headerIndex === null) {
                return response()->json(['status' => 'error', 'message' => 'Header tidak ditemukan. Pastikan file memiliki baris header yang jelas.'], 422);
            }

            $headerRow = $sheet[$headerIndex];
            $headers = array_map(fn($h) => $this->normalizeHeader((string) $h), $headerRow);
            $fileType = $this->detectFileType($headers);
            $preview = array_slice($sheet, $headerIndex + 1, 10);

            return response()->json([
                'status' => 'success',
                'preview' => $preview,
                'headers_found' => $headers,
                'header_row' => $headerIndex,
                'detected_type' => $fileType,
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 422);
        }
    }

    public function importFromExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:20480',
            'bidang_id' => 'nullable|exists:bidang,id',
        ]);

        try {
            $sheet = Excel::toArray([], $request->file('file'))[0] ?? [];
            if (empty($sheet)) {
                return response()->json(['status' => 'error', 'message' => 'Tidak ada data yang dapat dibaca dari file Excel.'], 422);
            }

            $headerIndex = $this->findHeaderIndex($sheet);
            if ($headerIndex === null) {
                return response()->json(['status' => 'error', 'message' => 'Baris header tidak ditemukan. Pastikan file memiliki baris header yang benar.'], 422);
            }

            $headerMap = $this->buildHeaderMap($sheet[$headerIndex]);
            $fileType = $this->detectFileType(array_keys($headerMap));
            if ($fileType === 'unknown') {
                return response()->json(['status' => 'error', 'message' => 'Format file tidak dikenali. Gunakan template Excel arsip atau surat masuk.'], 422);
            }

            $created = 0;
            $skipped = 0;
            $bidangId = auth()->user()->isOperator() ? auth()->user()->bidang_id : $request->bidang_id;

            $dataRows = array_slice($sheet, $headerIndex + 1);
            foreach ($dataRows as $row) {
                $hasValue = false;
                foreach ($row as $value) {
                    if ($value !== null && trim((string) $value) !== '') {
                        $hasValue = true;
                        break;
                    }
                }
                if (!$hasValue) {
                    continue;
                }

                if ($fileType === 'arsip') {
                    $data = $this->mapArsipRow($row, $headerMap, $bidangId);
                    if (!$data['no_berkas'] || !$data['uraian_berkas'] || !$data['tanggal_diarsipkan']) {
                        $skipped++;
                        continue;
                    }

                    try {
                        Arsip::create($data);
                        $created++;
                    } catch (\Exception $e) {
                        $skipped++;
                    }
                    continue;
                }

                $data = $this->mapSuratMasukRow($row, $headerMap);
                if (!$data['nomor_surat'] || !$data['tanggal_surat'] || !$data['tanggal_diterima'] || !$data['pengirim'] || !$data['perihal']) {
                    $skipped++;
                    continue;
                }

                try {
                    SuratMasuk::create($data);
                    $created++;
                } catch (\Exception $e) {
                    $skipped++;
                }
            }

            return response()->json([
                'status' => 'success',
                'created' => $created,
                'skipped' => $skipped,
                'type' => $fileType,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 422);
        }
    }

    private function buildHeaderMap(array $headerRow): array
    {
        $map = [];
        foreach ($headerRow as $index => $value) {
            if (!is_scalar($value)) {
                continue;
            }
            $map[$this->normalizeHeader((string) $value)] = $index;
        }
        return $map;
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
                if (!is_scalar($value)) {
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
            'nomor surat',
            'pengirim',
            'perihal',
            'tanggal diterima',
            'tanggal surat',
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

    private function isAllowedSpreadsheet($file): bool
    {
        $extension = strtolower($file->getClientOriginalExtension());
        return in_array($extension, ['xlsx', 'xls', 'xlsv', 'csv'], true);
    }

    private function loadSpreadsheetRows($file): array
    {
        $extension = strtolower($file->getClientOriginalExtension());
        if (in_array($extension, ['xlsv', 'csv'], true)) {
            $content = file_get_contents($file->getRealPath());
            $lines = preg_split('/\r\n|\r|\n/', trim($content));
            $rows = [];
            foreach ($lines as $line) {
                $rows[] = str_getcsv($line, "\t");
            }
            return $rows;
        }

        return Excel::toArray([], $file)[0] ?? [];
    }

    private function mapSuratMasukRow(array $row, ?array $headerMap): array
    {
        $get = fn(array $aliases) => $this->getValueFromRow($row, $headerMap, $aliases);

        $nomor_surat = $get(['nomor surat', 'nomor_surat', 'no surat', 'no_surat', 'nomor', 'no']);
        $tanggal_surat = $this->parseExcelDate($get(['tanggal surat', 'tanggal_surat', 'tgl surat', 'tgl_surat']));
        $tanggal_diterima = $this->parseExcelDate($get(['tanggal diterima', 'tanggal_diterima', 'tgl diterima', 'tgl_diterima']));
        $pengirim = $get(['pengirim', 'sender']);
        $perihal = $get(['perihal', 'subject']);
        $sifat_surat = $this->normalizeJenisSifat($get(['sifat surat', 'sifat_surat', 'jenis surat']));
        $status = $this->normalizeJenisStatus($get(['status', 'status tindak lanjut', 'status surat']));

        $bidangValue = $get(['bidang', 'bidang tujuan', 'bidang_tujuan']);
        $bidangId = auth()->user()->isOperator()
            ? auth()->user()->bidang_id
            : $this->resolveBidangId($bidangValue);

        return [
            'nomor_surat' => $nomor_surat,
            'tanggal_surat' => $tanggal_surat,
            'tanggal_diterima' => $tanggal_diterima,
            'pengirim' => $pengirim,
            'perihal' => $perihal,
            'sifat_surat' => $sifat_surat,
            'status' => $status,
            'bidang_id' => $bidangId,
            'created_by' => auth()->id(),
        ];
    }

    private function mapArsipRow(array $row, array $headerMap, ?int $bidangId): array
    {
        $get = fn(array $aliases) => $this->getValueFromRow($row, $headerMap, $aliases);

        return [
            'kode_klasifikasi' => $get(['kode klasifikasi', 'kode_klasifikasi']),
            'no_berkas' => $get(['no berkas', 'no_berkas']),
            'uraian_berkas' => $get(['uraian informasi berkas', 'uraian_berkas']),
            'kurun_waktu' => $get(['kurun waktu', 'kurun_waktu']),
            'jumlah_berkas' => $get(['jumlah berkas', 'jumlah_berkas']),
            'no_item_arsip' => $get(['no item arsip', 'no_item_arsip']),
            'uraian_arsip' => $get(['uraian informasi arsip', 'uraian_arsip']),
            'tanggal_diarsipkan' => $this->parseExcelDate($get(['tanggal diarsipkan', 'tanggal_diarsipkan'])),
            'jumlah_halaman_bundle' => $get(['jumlah halaman', 'jumlah halaman map bundle', 'jumlah halaman/ map/ bundle', 'jumlah_halaman_bundle']),
            'tingkat_perkembangan' => $get(['tingkat perkembangan', 'tingkat_perkembangan']),
            'lokasi_simpan' => $get(['keterangan lokasi simpan', 'lokasi_simpan', 'lokasi simpan']),
            'no_rak' => $get(['no rak', 'no_rak']),
            'no_boks' => $get(['no boks', 'no_boks']),
            'no_folder' => $get(['no folder', 'no_folder']),
            'klasifikasi_keamanan' => $this->normalizeArsipKeamanan($row, $headerMap),
            'status_retensi' => $this->normalizeArsipRetensi($row, $headerMap),
            'nasib_akhir' => $get(['nasib akhir', 'nasib_akhir']),
            'bidang_id' => $bidangId,
            'user_id' => auth()->id(),
            'status_arsip' => 'tersedia',
        ];
    }

    private function detectFileType(array $headers): string
    {
        $hasArsip = false;
        $hasSurat = false;

        foreach ($headers as $header) {
            $value = $this->normalizeHeader((string) $header);
            if (str_contains($value, 'kode klasifikasi') || str_contains($value, 'no berkas') || str_contains($value, 'uraian informasi berkas') || str_contains($value, 'tanggal diarsipkan') || str_contains($value, 'keterangan lokasi simpan')) {
                $hasArsip = true;
            }
            if (str_contains($value, 'nomor surat') || str_contains($value, 'pengirim') || str_contains($value, 'perihal') || str_contains($value, 'tanggal diterima')) {
                $hasSurat = true;
            }
        }

        if ($hasArsip && !$hasSurat) {
            return 'arsip';
        }
        if ($hasSurat && !$hasArsip) {
            return 'surat_masuk';
        }
        if ($hasArsip && $hasSurat) {
            return 'mixed';
        }

        return 'unknown';
    }

    private function normalizeArsipKeamanan(array $row, array $headerMap): ?string
    {
        $options = [
            'biasa' => ['biasa'],
            'terbatas' => ['terbatas'],
            'rahasia' => ['rahasia'],
            'sangat rahasia' => ['sangat rahasia'],
        ];

        foreach ($options as $key => $aliases) {
            if ($this->getValueFromRow($row, $headerMap, $aliases)) {
                return $key;
            }
        }

        return null;
    }

    private function normalizeArsipRetensi(array $row, array $headerMap): ?string
    {
        if ($this->getValueFromRow($row, $headerMap, ['aktif'])) {
            return 'aktif';
        }
        if ($this->getValueFromRow($row, $headerMap, ['inaktif'])) {
            return 'inaktif';
        }

        return null;
    }

    private function getValueFromRow(array $row, ?array $headerMap, array $aliases)
    {
        if ($headerMap !== null) {
            foreach ($aliases as $alias) {
                $key = $this->normalizeHeader($alias);
                if (isset($headerMap[$key]) && array_key_exists($headerMap[$key], $row)) {
                    return trim((string) $row[$headerMap[$key]]);
                }
            }
        }

        foreach ($aliases as $alias) {
            if (array_key_exists($alias, $row)) {
                return trim((string) $row[$alias]);
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

    private function normalizeJenisSifat($value): string
    {
        $normalized = strtolower(trim((string) $value));
        if (str_contains($normalized, 'sangat')) {
            return 'sangat_segera';
        }
        if (str_contains($normalized, 'segera')) {
            return 'segera';
        }
        if (str_contains($normalized, 'rahasia')) {
            return 'rahasia';
        }

        return 'biasa';
    }

    private function normalizeJenisStatus($value): string
    {
        $normalized = strtolower(trim((string) $value));
        if (str_contains($normalized, 'selesai')) {
            return 'selesai';
        }
        if (str_contains($normalized, 'diproses')) {
            return 'diproses';
        }
        if (str_contains($normalized, 'disposisi') || str_contains($normalized, 'diteruskan')) {
            return 'belum_didisposisi';
        }

        return 'belum_didisposisi';
    }

    private function resolveBidangId($value)
    {
        if (empty($value)) {
            return Bidang::first()?->id;
        }

        if (is_numeric($value) && Bidang::where('id', $value)->exists()) {
            return (int) $value;
        }

        $normalized = strtolower(trim((string) $value));
        $bidang = Bidang::whereRaw('LOWER(nama_bidang) = ?', [$normalized])->first();
        if (!$bidang) {
            $bidang = Bidang::where('nama_bidang', 'like', "%{$normalized}%")->first();
        }

        return $bidang?->id ?: Bidang::first()?->id;
    }

    public function show(SuratMasuk $surat_masuk)
    {
        $surat_masuk->load(['bidang', 'creator']);
        if (auth()->user()->isOperator() && $surat_masuk->bidang_id !== auth()->user()->bidang_id) {
            abort(403);
        }
        return view('surat-masuk.show', compact('surat_masuk'));
    }

    public function edit(SuratMasuk $surat_masuk)
    {
        if (auth()->user()->isOperator() && $surat_masuk->bidang_id !== auth()->user()->bidang_id) {
            abort(403);
        }
        $bidangList = Bidang::orderBy('nama_bidang')->get();
        return view('surat-masuk.edit', compact('surat_masuk', 'bidangList'));
    }

    public function update(Request $request, SuratMasuk $surat_masuk)
    {
        if (auth()->user()->isOperator() && $surat_masuk->bidang_id !== auth()->user()->bidang_id) {
            abort(403);
        }

        $request->validate([
            'nomor_surat' => 'required|string|max:100',
            'tanggal_surat' => 'required|date',
            'tanggal_diterima' => 'required|date',
            'pengirim' => 'required|string|max:255',
            'perihal' => 'required|string',
            'sifat_surat' => 'required|in:biasa,segera,sangat_segera,rahasia',
            'lampiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'status' => 'required|in:diteruskan,diarsipkan',
            'bidang_id' => 'required|exists:bidang,id',
            'catatan' => 'nullable|string',
        ]);

        $data = $request->except('lampiran');

        if ($request->hasFile('lampiran')) {
            if ($surat_masuk->lampiran) {
                Storage::disk('public')->delete($surat_masuk->lampiran);
            }
            $file = $request->file('lampiran');
            $data['lampiran'] = $file->store('surat-masuk', 'public');
            $data['lampiran_nama'] = $file->getClientOriginalName();
        }

        $surat_masuk->update($data);
        return redirect()->route('surat-masuk.index')->with('success', 'Surat masuk berhasil diperbarui.');
    }

    public function destroy(SuratMasuk $surat_masuk)
    {
        if (auth()->user()->isOperator() && $surat_masuk->bidang_id !== auth()->user()->bidang_id) {
            abort(403);
        }
        $surat_masuk->delete();
        return redirect()->route('surat-masuk.index')->with('success', 'Surat masuk berhasil dihapus.');
    }

    public function download(SuratMasuk $surat_masuk)
    {
        if (!$surat_masuk->lampiran || !Storage::disk('public')->exists($surat_masuk->lampiran)) {
            return back()->with('error', 'File tidak ditemukan.');
        }
        return Storage::disk('public')->download($surat_masuk->lampiran, $surat_masuk->lampiran_nama);
    }
}
