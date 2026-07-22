<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Arsip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArsipController extends Controller
{
    public function index(Request $request)
    {
        $bidangId = auth()->user()->bidang_id;
        $query = Arsip::with('bidang')->where('bidang_id', $bidangId);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('kode_klasifikasi', 'like', "%{$s}%")
                  ->orWhere('no_berkas', 'like', "%{$s}%")
                  ->orWhere('uraian_berkas', 'like', "%{$s}%")
                  ->orWhere('uraian_arsip', 'like', "%{$s}%");
            });
        }
        if ($request->filled('kode_klasifikasi')) {
            $query->where('kode_klasifikasi', 'like', "%{$request->kode_klasifikasi}%");
        }
        if ($request->filled('klasifikasi_keamanan')) {
            $query->where('klasifikasi_keamanan', $request->klasifikasi_keamanan);
        }
        if ($request->filled('status_retensi')) {
            $query->where('status_retensi', $request->status_retensi);
        }
        if ($request->filled('status_arsip')) {
            $query->where('status_arsip', $request->status_arsip);
        }
        if ($request->filled('no_rak')) {
            $query->where('no_rak', 'like', "%{$request->no_rak}%");
        }
        if ($request->filled('no_boks')) {
            $query->where('no_boks', 'like', "%{$request->no_boks}%");
        }
        if ($request->filled('dari')) {
            $query->whereDate('tanggal_diarsipkan', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('tanggal_diarsipkan', '<=', $request->sampai);
        }

        $arsipList = $query->latest()->paginate(10)->withQueryString();

        return view('operator.arsip.index', compact('arsipList'));
    }

    public function create()
    {
        return view('operator.arsip.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_klasifikasi' => 'required|string|max:50',
            'no_berkas' => 'required|string|max:50',
            'uraian_berkas' => 'required|string',
            'kurun_waktu' => 'nullable|string|max:100',
            'jumlah_berkas' => 'nullable|string|max:50',
            'no_item_arsip' => 'nullable|string|max:50',
            'uraian_arsip' => 'nullable|string',
            'tanggal_diarsipkan' => 'required|date',
            'jumlah_halaman_bundle' => 'nullable|string|max:100',
            'tingkat_perkembangan' => 'nullable|string|max:100',
            'lokasi_simpan' => 'nullable|string',
            'no_rak' => 'nullable|string|max:50',
            'no_boks' => 'nullable|string|max:50',
            'no_folder' => 'nullable|string|max:50',
            'klasifikasi_keamanan' => 'required|in:biasa,terbatas,rahasia,sangat_rahasia',
            'status_retensi' => 'required|in:aktif,inaktif',
            'nasib_akhir' => 'nullable|string|max:100',
            'file_arsip' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx|max:10240',
        ]);

        $data = $request->except('file_arsip');
        if (isset($data['jumlah_halaman_bundle'])) {
            $cleaned = preg_replace('/[^0-9]/', '', (string)$data['jumlah_halaman_bundle']);
            $data['jumlah_halaman_bundle'] = $cleaned !== '' ? (int)$cleaned : null;
        }
        $data['user_id'] = auth()->id();
        $data['bidang_id'] = auth()->user()->bidang_id;
        $data['status_arsip'] = 'tersedia';

        if ($request->hasFile('file_arsip')) {
            $file = $request->file('file_arsip');
            $data['file_arsip'] = $file->store('arsip', 'public');
        }

        Arsip::create($data);
        return redirect()->route('operator.arsip.index')->with('success', 'Arsip berhasil ditambahkan.');
    }

    public function show(Arsip $arsip)
    {
        $this->authorizeBidang($arsip);
        $arsip->load(['bidang', 'user', 'peminjaman' => fn($q) => $q->latest()]);
        return view('operator.arsip.show', compact('arsip'));
    }

    public function edit(Arsip $arsip)
    {
        $this->authorizeBidang($arsip);
        return view('operator.arsip.edit', compact('arsip'));
    }

    public function update(Request $request, Arsip $arsip)
    {
        $this->authorizeBidang($arsip);

        $request->validate([
            'kode_klasifikasi' => 'required|string|max:50',
            'no_berkas' => 'required|string|max:50',
            'uraian_berkas' => 'required|string',
            'kurun_waktu' => 'nullable|string|max:100',
            'jumlah_berkas' => 'nullable|string|max:50',
            'no_item_arsip' => 'nullable|string|max:50',
            'uraian_arsip' => 'nullable|string',
            'tanggal_diarsipkan' => 'required|date',
            'jumlah_halaman_bundle' => 'nullable|string|max:100',
            'tingkat_perkembangan' => 'nullable|string|max:100',
            'lokasi_simpan' => 'nullable|string',
            'no_rak' => 'nullable|string|max:50',
            'no_boks' => 'nullable|string|max:50',
            'no_folder' => 'nullable|string|max:50',
            'klasifikasi_keamanan' => 'required|in:biasa,terbatas,rahasia,sangat_rahasia',
            'status_retensi' => 'required|in:aktif,inaktif',
            'nasib_akhir' => 'nullable|string|max:100',
            'file_arsip' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx|max:10240',
        ]);

        $data = $request->except('file_arsip');
        if (isset($data['jumlah_halaman_bundle'])) {
            $cleaned = preg_replace('/[^0-9]/', '', (string)$data['jumlah_halaman_bundle']);
            $data['jumlah_halaman_bundle'] = $cleaned !== '' ? (int)$cleaned : null;
        }

        if ($request->hasFile('file_arsip')) {
            if ($arsip->file_arsip) {
                Storage::disk('public')->delete($arsip->file_arsip);
            }
            $file = $request->file('file_arsip');
            $data['file_arsip'] = $file->store('arsip', 'public');
        }

        $arsip->update($data);
        return redirect()->route('operator.arsip.index')->with('success', 'Arsip berhasil diperbarui.');
    }

    public function destroy(Arsip $arsip)
    {
        $this->authorizeBidang($arsip);
        if ($arsip->sedangDipinjam()) {
            return back()->with('error', 'Arsip sedang dipinjam dan tidak dapat dihapus.');
        }
        $arsip->delete();
        return redirect()->route('operator.arsip.index')->with('success', 'Arsip berhasil dihapus.');
    }

    public function download(Arsip $arsip)
    {
        $this->authorizeBidang($arsip);
        if (!$arsip->file_arsip || !Storage::disk('public')->exists($arsip->file_arsip)) {
            return back()->with('error', 'File tidak ditemukan.');
        }
        return Storage::disk('public')->download($arsip->file_arsip);
    }

    private function authorizeBidang(Arsip $arsip): void
    {
        if ($arsip->bidang_id !== auth()->user()->bidang_id) {
            abort(403);
        }
    }
}