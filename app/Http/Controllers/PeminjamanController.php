<?php

namespace App\Http\Controllers;

use App\Models\Arsip;
use App\Models\PeminjamanArsip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = PeminjamanArsip::with(['arsip.bidang', 'creator']);

        if ($user->isOperator()) {
            $query->whereHas('arsip', fn($q) => $q->where('bidang_id', $user->bidang_id));
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('nama_peminjam', 'like', "%{$s}%")
                  ->orWhere('bidang_peminjam', 'like', "%{$s}%")
                  ->orWhereHas('arsip', fn($aq) => $aq->where('no_berkas', 'like', "%{$s}%")
                      ->orWhere('kode_klasifikasi', 'like', "%{$s}%"));
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_pinjam', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_pinjam', '<=', $request->tanggal_sampai);
        }

        $peminjamanList = $query->latest()->paginate(10)->withQueryString();

        return view('peminjaman.index', compact('peminjamanList'));
    }

    public function create()
    {
        $user = auth()->user();
        $query = Arsip::where('status_arsip', 'tersedia');

        if ($user->isOperator()) {
            $query->where('bidang_id', $user->bidang_id);
        }

        $arsipTersedia = $query->orderBy('kode_klasifikasi')->get();
        return view('peminjaman.create', compact('arsipTersedia'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'arsip_id' => 'required|exists:arsip,id',
            'nama_peminjam' => 'required|string|max:255',
            'bidang_peminjam' => 'required|string|max:255',
            'tanggal_pinjam' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        $arsip = Arsip::findOrFail($request->arsip_id);

        if (auth()->user()->isOperator() && $arsip->bidang_id !== auth()->user()->bidang_id) {
            abort(403);
        }

        if ($arsip->sedangDipinjam()) {
            return back()->with('error', 'Arsip sedang dipinjam oleh pihak lain.')->withInput();
        }

        DB::transaction(function () use ($request, $arsip) {
            PeminjamanArsip::create([
                'arsip_id' => $arsip->id,
                'nama_peminjam' => $request->nama_peminjam,
                'bidang_peminjam' => $request->bidang_peminjam,
                'tanggal_pinjam' => $request->tanggal_pinjam,
                'keterangan' => $request->keterangan,
                'status' => 'dipinjam',
                'created_by' => auth()->id(),
            ]);
            $arsip->update(['status_arsip' => 'dipinjam']);
        });

        return redirect()->route('peminjaman.index')->with('success', 'Peminjaman arsip berhasil dicatat.');
    }

    public function show(PeminjamanArsip $peminjaman)
    {
        $peminjaman->load(['arsip.bidang', 'creator']);
        if (auth()->user()->isOperator() && $peminjaman->arsip->bidang_id !== auth()->user()->bidang_id) {
            abort(403);
        }
        return view('peminjaman.show', compact('peminjaman'));
    }

    public function kembalikan(PeminjamanArsip $peminjaman)
    {
        if ($peminjaman->status === 'dikembalikan') {
            return back()->with('error', 'Arsip sudah dikembalikan.');
        }

        DB::transaction(function () use ($peminjaman) {
            $peminjaman->update([
                'tanggal_kembali' => now()->toDateString(),
                'status' => 'dikembalikan',
            ]);
            $peminjaman->arsip->update(['status_arsip' => 'tersedia']);
        });

        return redirect()->route('peminjaman.index')->with('success', 'Arsip berhasil dikembalikan.');
    }

    public function destroy(PeminjamanArsip $peminjaman)
    {
        if ($peminjaman->status === 'dipinjam') {
            return back()->with('error', 'Tidak dapat menghapus peminjaman yang masih aktif.');
        }
        $peminjaman->delete();
        return redirect()->route('peminjaman.index')->with('success', 'Data peminjaman berhasil dihapus.');
    }
}
