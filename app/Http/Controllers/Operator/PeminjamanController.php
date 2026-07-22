<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Arsip;
use App\Models\PeminjamanArsip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = PeminjamanArsip::with(['arsip.bidang', 'creator'])
            ->whereHas('arsip', fn($q) => $q->where('bidang_id', $user->bidang_id));

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

        $summaryBase = PeminjamanArsip::whereHas('arsip', fn($q) => $q->where('bidang_id', $user->bidang_id));
        $totalMenunggu = (clone $summaryBase)->where('status', 'menunggu_persetujuan')->count();
        $totalDipinjam = (clone $summaryBase)->where('status', 'dipinjam')->count();
        $totalTerlambat = (clone $summaryBase)->where('status', 'dipinjam')
            ->whereNotNull('tanggal_rencana_kembali')
            ->whereDate('tanggal_rencana_kembali', '<', now())->count();
        $totalDikembalikan = (clone $summaryBase)->where('status', 'dikembalikan')->count();

        $bidangList = collect();

        $peminjamanList = $query->latest()->paginate(10)->withQueryString();

        return view('operator.peminjaman.index', compact(
            'peminjamanList', 'bidangList', 'totalMenunggu', 'totalDipinjam', 'totalTerlambat', 'totalDikembalikan'
        ));
    }

    public function create()
    {
        $user = auth()->user();
        $arsipTersedia = Arsip::where('status_arsip', 'tersedia')
            ->where('bidang_id', $user->bidang_id)
            ->orderBy('kode_klasifikasi')->get();

        return view('operator.peminjaman.create', compact('arsipTersedia'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'arsip_id' => 'required|exists:arsip,id',
            'nama_peminjam' => 'required|string|max:255',
            'bidang_peminjam' => 'required|string|max:255',
            'tanggal_pinjam' => 'required|date',
            'tanggal_rencana_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
            'keterangan' => 'nullable|string',
        ]);

        $arsip = Arsip::findOrFail($request->arsip_id);

        if ($arsip->bidang_id !== auth()->user()->bidang_id) {
            abort(403);
        }
        if ($arsip->sedangDipinjam()) {
            return back()->with('error', 'Arsip sedang dipinjam oleh pihak lain.')->withInput();
        }

        PeminjamanArsip::create([
            'arsip_id' => $arsip->id,
            'nama_peminjam' => $request->nama_peminjam,
            'bidang_peminjam' => $request->bidang_peminjam,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_rencana_kembali' => $request->tanggal_rencana_kembali,
            'keterangan' => $request->keterangan,
            'status' => 'menunggu_persetujuan',
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('operator.peminjaman.index')->with('success', 'Peminjaman arsip berhasil diajukan, menunggu persetujuan.');
    }

    public function show(PeminjamanArsip $peminjaman)
    {
        $peminjaman->load(['arsip.bidang', 'creator']);
        $this->authorizeBidang($peminjaman);
        return view('operator.peminjaman.show', compact('peminjaman'));
    }

    public function edit(PeminjamanArsip $peminjaman)
    {
        $this->authorizeBidang($peminjaman);
        if (!in_array($peminjaman->status, ['menunggu_persetujuan', 'dipinjam'])) {
            return back()->with('error', 'Peminjaman yang sudah final tidak dapat diedit.');
        }
        $arsipTersedia = Arsip::where('status_arsip', 'tersedia')
            ->where('bidang_id', auth()->user()->bidang_id)
            ->orWhere('id', $peminjaman->arsip_id)
            ->orderBy('kode_klasifikasi')->get();

        return view('operator.peminjaman.edit', compact('peminjaman', 'arsipTersedia'));
    }

    public function update(Request $request, PeminjamanArsip $peminjaman)
    {
        $this->authorizeBidang($peminjaman);
        if (!in_array($peminjaman->status, ['menunggu_persetujuan', 'dipinjam'])) {
            return back()->with('error', 'Peminjaman yang sudah final tidak dapat diedit.');
        }

        $request->validate([
            'nama_peminjam' => 'required|string|max:255',
            'bidang_peminjam' => 'required|string|max:255',
            'tanggal_pinjam' => 'required|date',
            'tanggal_rencana_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
            'keterangan' => 'nullable|string',
        ]);

        $peminjaman->update($request->only([
            'nama_peminjam', 'bidang_peminjam', 'tanggal_pinjam', 'tanggal_rencana_kembali', 'keterangan',
        ]));

        return redirect()->route('operator.peminjaman.index')->with('success', 'Data peminjaman berhasil diperbarui.');
    }

    public function approve(PeminjamanArsip $peminjaman)
    {
        $this->authorizeBidang($peminjaman);
        if ($peminjaman->status !== 'menunggu_persetujuan') {
            return back()->with('error', 'Peminjaman ini sudah diproses sebelumnya.');
        }
        if ($peminjaman->arsip->sedangDipinjam()) {
            return back()->with('error', 'Arsip ini sudah terlanjur dipinjam pihak lain.');
        }

        DB::transaction(function () use ($peminjaman) {
            $peminjaman->update(['status' => 'dipinjam']);
            $peminjaman->arsip->update(['status_arsip' => 'dipinjam']);
        });

        return back()->with('success', 'Peminjaman disetujui.');
    }

    public function reject(PeminjamanArsip $peminjaman)
    {
        $this->authorizeBidang($peminjaman);
        if ($peminjaman->status !== 'menunggu_persetujuan') {
            return back()->with('error', 'Peminjaman ini sudah diproses sebelumnya.');
        }

        $peminjaman->update(['status' => 'ditolak']);

        return back()->with('success', 'Peminjaman ditolak.');
    }

    public function kembalikan(PeminjamanArsip $peminjaman)
    {
        $this->authorizeBidang($peminjaman);
        if ($peminjaman->status !== 'dipinjam') {
            return back()->with('error', 'Hanya peminjaman berstatus Dipinjam yang bisa dikembalikan.');
        }

        DB::transaction(function () use ($peminjaman) {
            $peminjaman->update([
                'tanggal_kembali' => now()->toDateString(),
                'status' => 'dikembalikan',
            ]);
            $peminjaman->arsip->update(['status_arsip' => 'tersedia']);
        });

        return redirect()->route('operator.peminjaman.index')->with('success', 'Arsip berhasil dikembalikan.');
    }

    public function destroy(PeminjamanArsip $peminjaman)
    {
        $this->authorizeBidang($peminjaman);
        if ($peminjaman->status === 'dipinjam') {
            return back()->with('error', 'Tidak dapat menghapus peminjaman yang masih aktif. Kembalikan dulu arsipnya.');
        }
        $peminjaman->delete();
        return redirect()->route('operator.peminjaman.index')->with('success', 'Data peminjaman berhasil dihapus.');
    }

    private function authorizeBidang(PeminjamanArsip $peminjaman): void
    {
        if ($peminjaman->arsip->bidang_id !== auth()->user()->bidang_id) {
            abort(403);
        }
    }
}