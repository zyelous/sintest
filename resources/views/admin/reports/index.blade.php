@extends('layouts.app')
@section('title', 'Laporan Rekapitulasi')
@section('content')

<h1 class="text-2xl font-bold text-slate-800 mb-6">Laporan Rekapitulasi</h1>

<div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 mb-6">
    <form method="GET" action="{{ route('admin.laporan.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
        <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1.5">Periode Tanggal</label>
            <div class="flex items-center gap-2">
                <input type="date" name="dari" value="{{ request('dari') }}" class="w-full px-3.5 py-2.5 rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
                <span class="text-slate-400 text-xs">sampai</span>
            </div>
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1.5">&nbsp;</label>
            <input type="date" name="sampai" value="{{ request('sampai') }}" class="w-full px-3.5 py-2.5 rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
        </div>
        @if(auth()->user()->isAdmin())
        <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1.5">Departemen / Bidang</label>
            <select name="bidang_id" class="w-full px-3.5 py-2.5 rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
                <option value="">Semua Bidang</option>
                @foreach($bidangList as $b)
                <option value="{{ $b->id }}" {{ request('bidang_id') == $b->id ? 'selected' : '' }}>{{ $b->nama_bidang }}</option>
                @endforeach
            </select>
        </div>
        @endif
        <div class="flex gap-2">
            <button type="submit" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-lg bg-primary text-white hover:bg-primary-light transition">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                Tampilkan
            </button>
            <a href="{{ route('admin.laporan.index') }}" class="inline-flex items-center justify-center px-3 py-2.5 rounded-lg border border-slate-300 text-slate-500 hover:bg-slate-50 transition" title="Reset">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/></svg>
            </a>
        </div>
    </form>
</div>

{{-- 3 Box Utama --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-primary rounded-xl p-5 text-white">
        <p class="text-[0.7rem] font-semibold text-white/60 uppercase tracking-wide">Total Arsip</p>
        <p class="text-3xl font-bold mt-1">{{ number_format($totalArsip) }}</p>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
        <div class="flex items-center justify-between">
            <div class="w-9 h-9 rounded-lg bg-amber-50 flex items-center justify-center">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#D97706" stroke-width="2"><path d="M3 7v13h18V7"/><path d="M1 3h22v5H1z"/></svg>
            </div>
            <span class="text-[0.65rem] font-bold text-amber-600 uppercase">Aktif</span>
        </div>
        <p class="text-xs font-medium text-slate-500 mt-3">Arsip Aktif</p>
        <p class="text-2xl font-bold text-slate-800">{{ number_format($arsipAktif) }}</p>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
        <div class="flex items-center justify-between">
            <div class="w-9 h-9 rounded-lg bg-slate-100 flex items-center justify-center">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="2"><path d="M21 8L12 3 3 8v13h18z"/></svg>
            </div>
            <span class="text-[0.65rem] font-bold text-slate-500 uppercase">Inaktif</span>
        </div>
        <p class="text-xs font-medium text-slate-500 mt-3">Arsip Inaktif</p>
        <p class="text-2xl font-bold text-slate-800">{{ number_format($arsipInaktif) }}</p>
    </div>
</div>

{{-- Data Arsip Terbaru & Laporan Sisanya (Langsung dibawah 3 Box) --}}
<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-6">
    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 flex-wrap gap-2">
        <div>
            <h3 class="font-bold text-slate-800 text-base">Data Arsip Terbaru</h3>
            <p class="text-xs text-slate-500 mt-0.5">Daftar dokumen arsip paling baru yang terdaftar di laporan sistem.</p>
        </div>
        <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-primary/10 text-primary">
            Terbaru
        </span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 text-slate-600 text-xs uppercase tracking-wide border-b border-slate-100">
                    <th class="px-5 py-3 text-left font-semibold">No. Berkas</th>
                    <th class="px-5 py-3 text-left font-semibold">Uraian Berkas / Judul</th>
                    <th class="px-5 py-3 text-left font-semibold">Bidang</th>
                    <th class="px-5 py-3 text-left font-semibold">Tanggal Diarsipkan</th>
                    <th class="px-5 py-3 text-left font-semibold">Umur Arsip</th>
                    <th class="px-5 py-3 text-left font-semibold">Status Retensi</th>
                    <th class="px-5 py-3 text-right font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($recentArsip as $arsip)
                <tr class="hover:bg-slate-50/80 transition">
                    <td class="px-5 py-3.5 font-semibold text-primary whitespace-nowrap">{{ $arsip->no_berkas }}</td>
                    <td class="px-5 py-3.5">
                        <p class="font-semibold text-slate-800 line-clamp-1">{{ $arsip->uraian_berkas }}</p>
                        <span class="text-xs text-slate-400">Kode: {{ $arsip->kode_klasifikasi }}</span>
                    </td>
                    <td class="px-5 py-3.5 text-slate-600 whitespace-nowrap">
                        {{ $arsip->bidang->nama_bidang ?? '-' }}
                    </td>
                    <td class="px-5 py-3.5 text-slate-600 whitespace-nowrap">
                        {{ $arsip->tanggal_diarsipkan?->translatedFormat('d M Y') ?? '-' }}
                    </td>
                    <td class="px-5 py-3.5 whitespace-nowrap">
                        @if($arsip->tanggal_diarsipkan)
                            @php
                                $umur = $arsip->tanggal_diarsipkan->diffInDays(now());
                                $tahun = floor($umur / 365);
                                $bulan = floor(($umur % 365) / 30);
                                $hari = $umur % 30;
                                $umurText = $tahun > 0 ? $tahun . ' thn ' . $bulan . ' bln' : ($bulan > 0 ? $bulan . ' bln ' . $hari . ' hr' : $hari . ' hari');
                            @endphp
                            <span class="text-xs font-medium text-slate-600">{{ $umurText }}</span>
                        @else
                            <span class="text-xs text-slate-400">-</span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5 whitespace-nowrap">
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $arsip->status_retensi === 'aktif' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                            {{ ucfirst($arsip->status_retensi) }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5 text-right whitespace-nowrap">
                        <a href="{{ route(auth()->user()->isAdmin() ? 'admin.arsip.show' : 'operator.arsip.show', $arsip) }}" class="inline-flex items-center gap-1 text-xs font-semibold text-primary hover:underline">
                            Detail
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-8 text-center text-slate-400">Belum ada data arsip terbaru.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($recentArsip->hasPages())
    <div class="px-5 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between flex-wrap gap-3">
        <p class="text-xs text-slate-500">
            Menampilkan <span class="font-semibold text-slate-700">{{ $recentArsip->firstItem() }}–{{ $recentArsip->lastItem() }}</span> dari total <span class="font-semibold text-slate-700">{{ number_format($recentArsip->total()) }}</span> data arsip terbaru.
        </p>
        <div class="flex items-center gap-2">
            {{-- Tombol Previous --}}
            @if($recentArsip->onFirstPage())
                <span class="inline-flex items-center gap-1 px-3 py-2 text-xs font-semibold rounded-lg border border-slate-200 text-slate-300 cursor-not-allowed">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
                    Prev
                </span>
            @else
                <a href="{{ $recentArsip->previousPageUrl() }}" class="inline-flex items-center gap-1 px-3 py-2 text-xs font-semibold rounded-lg border border-slate-300 text-slate-600 hover:bg-white hover:shadow-sm transition">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
                    Prev
                </a>
            @endif

            {{-- Nomor Halaman --}}
            <span class="px-3 py-2 text-xs font-bold rounded-lg bg-primary text-white">
                {{ $recentArsip->currentPage() }}
            </span>
            <span class="text-xs text-slate-400">dari {{ $recentArsip->lastPage() }}</span>

            {{-- Tombol Next --}}
            @if($recentArsip->hasMorePages())
                <a href="{{ $recentArsip->nextPageUrl() }}" class="inline-flex items-center gap-1 px-3 py-2 text-xs font-semibold rounded-lg bg-primary text-white hover:bg-primary-light transition shadow-sm hover:shadow-md">
                    Next
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                </a>
            @else
                <span class="inline-flex items-center gap-1 px-3 py-2 text-xs font-semibold rounded-lg bg-primary/40 text-white/60 cursor-not-allowed">
                    Next
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                </span>
            @endif
        </div>
    </div>
    @endif
</div>

{{-- Data Rekapitulasi Bidang & Stat --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
            <h3 class="font-semibold text-slate-800">Data Rekapitulasi Bidang</h3>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.report.arsip.pdf') }}{{ request('bidang_id') ? '?bidang_id=' . request('bidang_id') : '' }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-50 transition">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    Export PDF
                </a>
                <a href="{{ route('admin.report.arsip.excel') }}{{ request('bidang_id') ? '?bidang_id=' . request('bidang_id') : '' }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 transition">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="9" y1="3" x2="9" y2="21"/></svg>
                    Excel (.xlsx)
                </a>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-primary text-white text-xs uppercase tracking-wide">
                        <th class="px-5 py-3 text-left font-semibold">Bidang / Unit Kerja</th>
                        <th class="px-5 py-3 text-left font-semibold">Total Arsip</th>
                        <th class="px-5 py-3 text-left font-semibold">Aktif</th>
                        <th class="px-5 py-3 text-left font-semibold">Inaktif</th>
                        <th class="px-5 py-3 text-left font-semibold">Dipinjam</th>
                        <th class="px-5 py-3 text-left font-semibold">Trend</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($rekap as $r)
                    <tr class="hover:bg-slate-50">
                        <td class="px-5 py-3.5 font-semibold text-slate-800">{{ $r['bidang'] }}</td>
                        <td class="px-5 py-3.5"><span class="px-2 py-1 rounded-full bg-primary/10 text-primary text-xs font-semibold">{{ number_format($r['total']) }}</span></td>
                        <td class="px-5 py-3.5 text-slate-600">{{ number_format($r['aktif']) }}</td>
                        <td class="px-5 py-3.5 text-slate-600">{{ number_format($r['inaktif']) }}</td>
                        <td class="px-5 py-3.5 text-slate-600">{{ number_format($r['dipinjam']) }}</td>
                        <td class="px-5 py-3.5">
                            @if($r['trend'] > 0)
                                <span class="text-emerald-600 text-xs font-semibold">↑ {{ $r['trend'] }}%</span>
                            @elseif($r['trend'] < 0)
                                <span class="text-red-500 text-xs font-semibold">↓ {{ abs($r['trend']) }}%</span>
                            @else
                                <span class="text-slate-400 text-xs font-semibold">— 0%</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-5 py-8 text-center text-slate-400">Belum ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3 border-t border-slate-100">
            <p class="text-xs text-slate-400">Menampilkan {{ $rekap->count() }} dari {{ $rekap->count() }} Unit Kerja</p>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <h3 class="font-semibold text-slate-800 text-sm mb-4">Status Retensi</h3>
            @php $totalRetensi = max($arsipAktif + $arsipInaktif, 1); @endphp
            <div class="space-y-3">
                <div>
                    <div class="flex items-center justify-between text-xs mb-1"><span class="font-medium text-slate-600">● Aktif</span><span class="text-slate-400">{{ round($arsipAktif / $totalRetensi * 100) }}%</span></div>
                    <div class="h-1.5 bg-slate-100 rounded-full overflow-hidden"><div class="h-full bg-primary" style="width: {{ round($arsipAktif / $totalRetensi * 100) }}%"></div></div>
                </div>
                <div>
                    <div class="flex items-center justify-between text-xs mb-1"><span class="font-medium text-slate-600">● Inaktif</span><span class="text-slate-400">{{ round($arsipInaktif / $totalRetensi * 100) }}%</span></div>
                    <div class="h-1.5 bg-slate-100 rounded-full overflow-hidden"><div class="h-full bg-accent-gold" style="width: {{ round($arsipInaktif / $totalRetensi * 100) }}%"></div></div>
                </div>
            </div>
            <div class="mt-5 pt-4 border-t border-slate-100 text-center">
                <p class="text-2xl font-bold text-slate-800">{{ number_format($totalArsip) }}</p>
                <p class="text-[0.65rem] text-slate-400 uppercase">Total</p>
            </div>
        </div>

        <div class="bg-primary rounded-xl p-5 text-white">
            <h3 class="font-semibold text-sm mb-2">Laporan Tahunan</h3>
            <p class="text-xs text-white/60 mb-4">Unduh kompilasi data arsip tahun {{ date('Y') }} dalam format resmi buku laporan.</p>
            <a href="{{ route('admin.report.arsip.pdf') }}" target="_blank" class="inline-flex items-center gap-2 w-full justify-center px-4 py-2.5 text-sm font-semibold rounded-lg bg-accent-gold text-primary-dark hover:bg-accent-gold-dark transition">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Download Laporan {{ date('Y') }}
            </a>
        </div>
    </div>
</div>
@endsection