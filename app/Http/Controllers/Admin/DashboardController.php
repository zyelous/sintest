<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Arsip;
use App\Models\Bidang;
use App\Models\PeminjamanArsip;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            $totalArsip = Arsip::count();
            $arsipAktif = Arsip::where('status_retensi', 'aktif')->count();
            $arsipInaktif = Arsip::where('status_retensi', 'inaktif')->count();
            $arsipDipinjam = Arsip::where('status_arsip', 'dipinjam')->count();
            $totalPeminjaman = PeminjamanArsip::where('status', 'dipinjam')->count();
            $peminjamanTerlambat = PeminjamanArsip::where('status', 'dipinjam')
                ->where('tanggal_rencana_kembali', '<', now()->toDateString())
                ->count();
            $arsipPerBidang = Bidang::withCount(['arsip', 'users'])->get();
            $totalBoks = Arsip::whereNotNull('no_boks')->where('no_boks', '!=', '')->distinct('no_boks')->count('no_boks');
            $recentPeminjaman = PeminjamanArsip::with(['arsip.bidang', 'user'])
                ->latest()->take(5)->get();
            $recentArsip = Arsip::with('bidang')->latest()->take(5)->get();

            $monthlyGrowth = [];
            for ($i = 5; $i >= 0; $i--) {
                $month = now()->subMonths($i);
                $monthlyGrowth[] = [
                    'label' => $month->translatedFormat('M'),
                    'count' => Arsip::whereYear('created_at', $month->year)->whereMonth('created_at', $month->month)->count(),
                ];
            }

            // Statistik arsip per bidang di bulan ini (untuk chart admin)
            $thisMonth = now();
            $arsipBulanIniPerBidang = Bidang::withCount([
                'arsip as arsip_bulan_ini' => function ($q) use ($thisMonth) {
                    $q->whereYear('created_at', $thisMonth->year)
                      ->whereMonth('created_at', $thisMonth->month);
                },
                'arsip as arsip_total',
            ])->orderBy('nama_bidang')->get();
        } else {
            $bidangId = $user->bidang_id;
            if ($bidangId) {
                $totalArsip = Arsip::where('bidang_id', $bidangId)->count();
                $arsipAktif = Arsip::where('bidang_id', $bidangId)->where('status_retensi', 'aktif')->count();
                $arsipInaktif = Arsip::where('bidang_id', $bidangId)->where('status_retensi', 'inaktif')->count();
                $arsipDipinjam = Arsip::where('bidang_id', $bidangId)->where('status_arsip', 'dipinjam')->count();
                $totalPeminjaman = PeminjamanArsip::whereHas('arsip', fn($q) => $q->where('bidang_id', $bidangId))
                    ->where('status', 'dipinjam')->count();
                $peminjamanTerlambat = PeminjamanArsip::whereHas('arsip', fn($q) => $q->where('bidang_id', $bidangId))
                    ->where('status', 'dipinjam')
                    ->where('tanggal_rencana_kembali', '<', now()->toDateString())
                    ->count();
                $arsipPerBidang = null;
                $totalBoks = Arsip::where('bidang_id', $bidangId)->whereNotNull('no_boks')->where('no_boks', '!=', '')->distinct('no_boks')->count('no_boks');
                $recentPeminjaman = PeminjamanArsip::with(['arsip'])
                    ->whereHas('arsip', fn($q) => $q->where('bidang_id', $bidangId))
                    ->latest()->take(5)->get();
                $recentArsip = Arsip::where('bidang_id', $bidangId)->latest()->take(5)->get();
            } else {
                $totalArsip = 0;
                $arsipAktif = 0;
                $arsipInaktif = 0;
                $arsipDipinjam = 0;
                $totalPeminjaman = 0;
                $peminjamanTerlambat = 0;
                $arsipPerBidang = null;
                $totalBoks = 0;
                $recentPeminjaman = collect();
                $recentArsip = collect();
            }

            $monthlyGrowth = [];
            for ($i = 5; $i >= 0; $i--) {
                $month = now()->subMonths($i);
                $monthlyGrowth[] = [
                    'label' => $month->translatedFormat('M'),
                    'count' => $bidangId ? Arsip::where('bidang_id', $bidangId)->whereYear('created_at', $month->year)->whereMonth('created_at', $month->month)->count() : 0,
                ];
            }
            $arsipBulanIniPerBidang = null; // operator tidak perlu chart ini
        }

        return view('admin.dashboard.index', compact(
            'totalArsip', 'arsipAktif', 'arsipInaktif', 'arsipDipinjam',
            'totalPeminjaman', 'peminjamanTerlambat', 'arsipPerBidang', 'recentPeminjaman',
            'totalBoks', 'monthlyGrowth', 'recentArsip', 'arsipBulanIniPerBidang'
        ));
    }
}