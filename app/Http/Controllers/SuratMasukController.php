<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use App\Models\SuratMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
