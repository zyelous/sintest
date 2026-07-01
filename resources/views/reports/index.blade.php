@extends('layouts.app')
@section('title', 'Laporan Rekapitulasi')
@section('content')

<h1 class="text-2xl font-bold text-slate-800 mb-6">Laporan Rekapitulasi</h1>

<div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 mb-6">
    <form method="GET" action="{{ route('laporan.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
        <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1.5">Periode Tanggal</label>
            <div class="flex items-center gap-2">
                <input type="date" name="dari" value="{{ request('dari') }}" class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
                <span class="text-slate-400 text-xs">sampai</span>
            </div>
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1.5">&nbsp;</label>
            <input type="date" name="sampai" value="{{ request('sampai') }}" class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
        </div>
        @if(auth()->user()->isAdmin())
        <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1.5">Departemen / Bidang</label>
            <select name="bidang_id" class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
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
            <a href="{{ route('laporan.index') }}" class="inline-flex items-center justify-center px-3 py-2.5 rounded-lg border border-slate-300 text-slate-500 hover:bg-slate-50 transition" title="Reset">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/></svg>
            </a>
        </div>
    </form>
</div>

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

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
            <h3 class="font-semibold text-slate-800">Data Rekapitulasi Bidang</h3>
            <div class="flex items-center gap-2">
                <a href="{{ route('report.arsip.pdf') }}{{ request('bidang_id') ? '?bidang_id=' . request('bidang_id') : '' }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-50 transition">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    Export PDF
                </a>
                <a href="{{ route('report.arsip.excel') }}{{ request('bidang_id') ? '?bidang_id=' . request('bidang_id') : '' }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 transition">
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
                        <th class="px-5 py-3 text-left font-semibold">Surat Masuk</th>
                        <th class="px-5 py-3 text-left font-semibold">Surat Keluar</th>
                        <th class="px-5 py-3 text-left font-semibold">Total</th>
                        <th class="px-5 py-3 text-left font-semibold">Trend</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($rekap as $r)
                    <tr class="hover:bg-slate-50">
                        <td class="px-5 py-3.5 font-semibold text-slate-800">{{ $r['bidang'] }}</td>
                        <td class="px-5 py-3.5 text-slate-600">{{ number_format($r['masuk']) }}</td>
                        <td class="px-5 py-3.5 text-slate-600">{{ number_format($r['keluar']) }}</td>
                        <td class="px-5 py-3.5"><span class="px-2 py-1 rounded-full bg-primary/10 text-primary text-xs font-semibold">{{ number_format($r['total']) }}</span></td>
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
                    <tr><td colspan="5" class="px-5 py-8 text-center text-slate-400">Belum ada data.</td></tr>
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
            <a href="{{ route('report.arsip.pdf') }}" target="_blank" class="inline-flex items-center gap-2 w-full justify-center px-4 py-2.5 text-sm font-semibold rounded-lg bg-accent-gold text-primary-dark hover:bg-accent-gold-dark transition">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Download Laporan {{ date('Y') }}
            </a>
        </div>
    </div>
</div>
@endsection
