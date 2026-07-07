<?php

namespace App\Http\Controllers;

use App\Models\Arsip;
use App\Models\Bidang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArsipController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Arsip::with('bidang');

        if ($user->isOperator()) {
            $query->where('bidang_id', $user->bidang_id);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('kode_klasifikasi', 'like', "%{$s}%")
                  ->orWhere('no_berkas', 'like', "%{$s}%")
                  ->orWhere('uraian_berkas', 'like', "%{$s}%")
                  ->orWhere('uraian_arsip', 'like', "%{$s}%");
            });
        }
        if ($request->filled('bidang_id') && $user->isAdmin()) {
            $query->where('bidang_id', $request->bidang_id);
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

        $arsipList = $query->latest()->paginate(10)->withQueryString();
        $bidangList = Bidang::orderBy('nama_bidang')->get();

        // Statistics for operator stat cards
        $totalArsip = 0;
        $arsipAktif = 0;
        $arsipDipinjam = 0;
        if ($user->isOperator()) {
            $totalArsip = Arsip::where('bidang_id', $user->bidang_id)->count();
            $arsipAktif = Arsip::where('bidang_id', $user->bidang_id)->where('status_retensi', 'aktif')->count();
            $arsipDipinjam = Arsip::where('bidang_id', $user->bidang_id)->where('status_arsip', 'dipinjam')->count();
        } else {
            $totalArsip = Arsip::count();
            $arsipAktif = Arsip::where('status_retensi', 'aktif')->count();
            $arsipDipinjam = Arsip::where('status_arsip', 'dipinjam')->count();
        }

        return view('arsip.index', compact('arsipList', 'bidangList', 'totalArsip', 'arsipAktif', 'arsipDipinjam'));
    }

    public function search(Request $request)
    {
        $user = auth()->user();
        $query = Arsip::with('bidang');

        if ($user->isOperator()) {
            $query->where('bidang_id', $user->bidang_id);
        } elseif ($request->filled('bidang_id')) {
            $query->where('bidang_id', $request->bidang_id);
        }

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
        if ($request->filled('dari')) {
            $query->whereDate('tanggal_diarsipkan', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('tanggal_diarsipkan', '<=', $request->sampai);
        }
        if ($request->filled('no_boks')) {
            $query->where('no_boks', 'like', "%{$request->no_boks}%");
        }
        if ($request->filled('no_rak')) {
            $query->where('no_rak', 'like', "%{$request->no_rak}%");
        }

        $totalArsip = (clone $query)->count();
        $arsipList = $query->latest()->paginate(10)->withQueryString();
        $bidangList = Bidang::orderBy('nama_bidang')->get();
        $totalSuratMasuk = \App\Models\SuratMasuk::when($user->isOperator(), fn($q) => $q->where('bidang_id', $user->bidang_id))->whereYear('tanggal_diterima', now()->year)->count();

        return view('arsip.search', compact('arsipList', 'bidangList', 'totalSuratMasuk', 'totalArsip'));
    }

    public function create()
    {
        $bidangList = Bidang::orderBy('nama_bidang')->get();
        return view('arsip.create', compact('bidangList'));
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
            'bidang_id' => 'required|exists:bidang,id',
        ]);

        $data = $request->except('file_arsip');
        if (isset($data['jumlah_halaman_bundle'])) {
            $cleaned = preg_replace('/[^0-9]/', '', (string)$data['jumlah_halaman_bundle']);
            $data['jumlah_halaman_bundle'] = $cleaned !== '' ? (int)$cleaned : null;
        }
        $data['user_id'] = auth()->id();
        $data['status_arsip'] = 'tersedia';

        if (auth()->user()->isOperator()) {
            $data['bidang_id'] = auth()->user()->bidang_id;
        }

        if ($request->hasFile('file_arsip')) {
            $file = $request->file('file_arsip');
            $data['file_arsip'] = $file->store('arsip', 'public');
        }

        Arsip::create($data);
        return redirect()->route('arsip.index')->with('success', 'Arsip berhasil ditambahkan.');
    }

    public function show(Arsip $arsip)
    {
        if (auth()->user()->isOperator() && $arsip->bidang_id !== auth()->user()->bidang_id) {
            abort(403);
        }
        $arsip->load(['bidang', 'user', 'peminjaman' => fn($q) => $q->latest()]);
        return view('arsip.show', compact('arsip'));
    }

    public function edit(Arsip $arsip)
    {
        if (auth()->user()->isOperator() && $arsip->bidang_id !== auth()->user()->bidang_id) {
            abort(403);
        }
        $bidangList = Bidang::orderBy('nama_bidang')->get();
        return view('arsip.edit', compact('arsip', 'bidangList'));
    }

    public function update(Request $request, Arsip $arsip)
    {
        if (auth()->user()->isOperator() && $arsip->bidang_id !== auth()->user()->bidang_id) {
            abort(403);
        }

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
            'bidang_id' => 'required|exists:bidang,id',
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
        return redirect()->route('arsip.index')->with('success', 'Arsip berhasil diperbarui.');
    }

    public function destroy(Arsip $arsip)
    {
        if (auth()->user()->isOperator() && $arsip->bidang_id !== auth()->user()->bidang_id) {
            abort(403);
        }
        if ($arsip->sedangDipinjam()) {
            return back()->with('error', 'Arsip sedang dipinjam dan tidak dapat dihapus.');
        }
        $arsip->delete();
        return redirect()->route('arsip.index')->with('success', 'Arsip berhasil dihapus.');
    }

    public function download(Arsip $arsip)
    {
        if (!$arsip->file_arsip || !Storage::disk('public')->exists($arsip->file_arsip)) {
            return back()->with('error', 'File tidak ditemukan.');
        }
        return Storage::disk('public')->download($arsip->file_arsip);
    }
}
