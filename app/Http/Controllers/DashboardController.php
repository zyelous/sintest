<?php

namespace App\Http\Controllers;

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
            $arsipDipinjam = Arsip::where('status_arsip', 'dipinjam')->count();
            $totalPeminjaman = PeminjamanArsip::where('status', 'dipinjam')->count();
            $arsipPerBidang = Bidang::withCount('arsip')->get();
            $totalBoks = Arsip::whereNotNull('no_boks')->where('no_boks', '!=', '')->distinct('no_boks')->count('no_boks');
            $recentPeminjaman = PeminjamanArsip::with(['arsip.bidang'])
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
        } else {
            $bidangId = $user->bidang_id;
            $totalArsip = Arsip::where('bidang_id', $bidangId)->count();
            $arsipAktif = Arsip::where('bidang_id', $bidangId)->where('status_retensi', 'aktif')->count();
            $arsipDipinjam = Arsip::where('bidang_id', $bidangId)->where('status_arsip', 'dipinjam')->count();
            $totalPeminjaman = PeminjamanArsip::whereHas('arsip', fn($q) => $q->where('bidang_id', $bidangId))
                ->where('status', 'dipinjam')->count();
            $arsipPerBidang = null;
            $totalBoks = Arsip::where('bidang_id', $bidangId)->whereNotNull('no_boks')->where('no_boks', '!=', '')->distinct('no_boks')->count('no_boks');
            $monthlyGrowth = [];
            for ($i = 5; $i >= 0; $i--) {
                $month = now()->subMonths($i);
                $monthlyGrowth[] = [
                    'label' => $month->translatedFormat('M'),
                    'count' => Arsip::where('bidang_id', $bidangId)->whereYear('created_at', $month->year)->whereMonth('created_at', $month->month)->count(),
                ];
            }
            $recentPeminjaman = PeminjamanArsip::with(['arsip'])
                ->whereHas('arsip', fn($q) => $q->where('bidang_id', $bidangId))
                ->latest()->take(5)->get();
            $recentArsip = Arsip::where('bidang_id', $bidangId)->latest()->take(5)->get();
        }

        return view('dashboard.index', compact(
            'totalArsip', 'arsipAktif', 'arsipDipinjam',
            'totalPeminjaman', 'arsipPerBidang', 'recentPeminjaman',
            'totalBoks', 'monthlyGrowth', 'recentArsip'
        ));
    }
}