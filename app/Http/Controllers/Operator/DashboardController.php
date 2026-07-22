<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Arsip;
use App\Models\PeminjamanArsip;

class DashboardController extends Controller
{
    public function index()
    {
        $bidangId = auth()->user()->bidang_id;

        $totalArsip = Arsip::where('bidang_id', $bidangId)->count();
        $arsipAktif = Arsip::where('bidang_id', $bidangId)->where('status_retensi', 'aktif')->count();
        $arsipDipinjam = Arsip::where('bidang_id', $bidangId)->where('status_arsip', 'dipinjam')->count();
        $totalBoks = Arsip::where('bidang_id', $bidangId)
            ->whereNotNull('no_boks')->where('no_boks', '!=', '')
            ->distinct('no_boks')->count('no_boks');

        $peminjamanBase = PeminjamanArsip::whereHas('arsip', fn($q) => $q->where('bidang_id', $bidangId));
        $totalMenunggu = (clone $peminjamanBase)->where('status', 'menunggu_persetujuan')->count();
        $totalDipinjamP = (clone $peminjamanBase)->where('status', 'dipinjam')->count();
        $totalTerlambat = (clone $peminjamanBase)->where('status', 'dipinjam')
            ->whereNotNull('tanggal_rencana_kembali')
            ->whereDate('tanggal_rencana_kembali', '<', now())->count();

        $recentPeminjaman = (clone $peminjamanBase)->with('arsip')->latest()->take(5)->get();

        $monthlyGrowth = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthlyGrowth[] = [
                'label' => $month->translatedFormat('M'),
                'count' => Arsip::where('bidang_id', $bidangId)
                    ->whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)->count(),
            ];
        }

        return view('operator.dashboard.index', compact(
            'totalArsip', 'arsipAktif', 'arsipDipinjam', 'totalBoks',
            'totalMenunggu', 'totalDipinjamP', 'totalTerlambat',
            'recentPeminjaman', 'monthlyGrowth'
        ));
    }
}