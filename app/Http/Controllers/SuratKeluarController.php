<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use App\Models\SuratKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SuratKeluarController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = SuratKeluar::with(['bidang', 'creator']);

        if ($user->isOperator()) {
            $query->where('bidang_id', $user->bidang_id);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('nomor_surat', 'like', "%{$s}%")
                  ->orWhere('tujuan', 'like', "%{$s}%")
                  ->orWhere('perihal', 'like', "%{$s}%");
            });
        }
        if ($request->filled('bidang_id') && $user->isAdmin()) {
            $query->where('bidang_id', $request->bidang_id);
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

        $suratKeluar = $query->latest()->paginate(10)->withQueryString();
        $bidangList = Bidang::orderBy('nama_bidang')->get();

        return view('surat-keluar.index', compact('suratKeluar', 'bidangList'));
    }

    public function create()
    {
        $bidangList = Bidang::orderBy('nama_bidang')->get();
        return view('surat-keluar.create', compact('bidangList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomor_surat' => 'required|string|max:100',
            'tanggal_surat' => 'required|date',
            'tujuan' => 'required|string|max:255',
            'perihal' => 'required|string',
            'sifat_surat' => 'required|in:biasa,segera,sangat_segera,rahasia',
            'lampiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
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
            $data['lampiran'] = $file->store('surat-keluar', 'public');
            $data['lampiran_nama'] = $file->getClientOriginalName();
        }

        SuratKeluar::create($data);
        return redirect()->route('surat-keluar.index')->with('success', 'Surat keluar berhasil ditambahkan.');
    }

    public function show(SuratKeluar $surat_keluar)
    {
        $surat_keluar->load(['bidang', 'creator']);
        if (auth()->user()->isOperator() && $surat_keluar->bidang_id !== auth()->user()->bidang_id) {
            abort(403);
        }
        return view('surat-keluar.show', compact('surat_keluar'));
    }

    public function edit(SuratKeluar $surat_keluar)
    {
        if (auth()->user()->isOperator() && $surat_keluar->bidang_id !== auth()->user()->bidang_id) {
            abort(403);
        }
        $bidangList = Bidang::orderBy('nama_bidang')->get();
        return view('surat-keluar.edit', compact('surat_keluar', 'bidangList'));
    }

    public function update(Request $request, SuratKeluar $surat_keluar)
    {
        if (auth()->user()->isOperator() && $surat_keluar->bidang_id !== auth()->user()->bidang_id) {
            abort(403);
        }

        $request->validate([
            'nomor_surat' => 'required|string|max:100',
            'tanggal_surat' => 'required|date',
            'tujuan' => 'required|string|max:255',
            'perihal' => 'required|string',
            'sifat_surat' => 'required|in:biasa,segera,sangat_segera,rahasia',
            'lampiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'bidang_id' => 'required|exists:bidang,id',
            'catatan' => 'nullable|string',
        ]);

        $data = $request->except('lampiran');

        if ($request->hasFile('lampiran')) {
            if ($surat_keluar->lampiran) {
                Storage::disk('public')->delete($surat_keluar->lampiran);
            }
            $file = $request->file('lampiran');
            $data['lampiran'] = $file->store('surat-keluar', 'public');
            $data['lampiran_nama'] = $file->getClientOriginalName();
        }

        $surat_keluar->update($data);
        return redirect()->route('surat-keluar.index')->with('success', 'Surat keluar berhasil diperbarui.');
    }

    public function destroy(SuratKeluar $surat_keluar)
    {
        if (auth()->user()->isOperator() && $surat_keluar->bidang_id !== auth()->user()->bidang_id) {
            abort(403);
        }
        $surat_keluar->delete();
        return redirect()->route('surat-keluar.index')->with('success', 'Surat keluar berhasil dihapus.');
    }

    public function download(SuratKeluar $surat_keluar)
    {
        if (!$surat_keluar->lampiran || !Storage::disk('public')->exists($surat_keluar->lampiran)) {
            return back()->with('error', 'File tidak ditemukan.');
        }
        return Storage::disk('public')->download($surat_keluar->lampiran, $surat_keluar->lampiran_nama);
    }
}
