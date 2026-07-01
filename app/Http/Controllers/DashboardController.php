<?php

namespace App\Http\Controllers;

use App\Models\Arsip;
use App\Models\Bidang;
use App\Models\PeminjamanArsip;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            $totalArsip = Arsip::count();
            $arsipAktif = Arsip::where('status_retensi', 'aktif')->count();
            $arsipDipinjam = Arsip::where('status_arsip', 'dipinjam')->count();
            $totalSuratMasuk = SuratMasuk::count();
            $totalSuratKeluar = SuratKeluar::count();
            $totalPeminjaman = PeminjamanArsip::where('status', 'dipinjam')->count();
            $arsipPerBidang = Bidang::withCount('arsip')->get();
            $recentPeminjaman = PeminjamanArsip::with(['arsip.bidang'])
                ->where('status', 'dipinjam')->latest()->take(5)->get();
            $recentSuratMasuk = SuratMasuk::with('bidang')->latest()->take(5)->get();
        } else {
            $bidangId = $user->bidang_id;
            $totalArsip = Arsip::where('bidang_id', $bidangId)->count();
            $arsipAktif = Arsip::where('bidang_id', $bidangId)->where('status_retensi', 'aktif')->count();
            $arsipDipinjam = Arsip::where('bidang_id', $bidangId)->where('status_arsip', 'dipinjam')->count();
            $totalSuratMasuk = SuratMasuk::where('bidang_id', $bidangId)->count();
            $totalSuratKeluar = SuratKeluar::where('bidang_id', $bidangId)->count();
            $totalPeminjaman = PeminjamanArsip::whereHas('arsip', fn($q) => $q->where('bidang_id', $bidangId))
                ->where('status', 'dipinjam')->count();
            $arsipPerBidang = null;
            $recentPeminjaman = PeminjamanArsip::with(['arsip'])
                ->whereHas('arsip', fn($q) => $q->where('bidang_id', $bidangId))
                ->where('status', 'dipinjam')->latest()->take(5)->get();
            $recentSuratMasuk = SuratMasuk::where('bidang_id', $bidangId)->latest()->take(5)->get();
        }

        return view('dashboard.index', compact(
            'totalArsip', 'arsipAktif', 'arsipDipinjam',
            'totalSuratMasuk', 'totalSuratKeluar', 'totalPeminjaman',
            'arsipPerBidang', 'recentPeminjaman', 'recentSuratMasuk'
        ));
    }
}
