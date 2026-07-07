@extends('layouts.app')
@section('title', auth()->user()->isOperator() ? 'Arsip ' . (auth()->user()->bidang->nama_bidang ?? 'Saya') : 'Manajemen Arsip')
@section('content')

@if(auth()->user()->isOperator())
{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- OPERATOR: ARSIP SAYA --}}
{{-- ═══════════════════════════════════════════════════════════ --}}

{{-- Header --}}
<div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">
            Arsip {{ auth()->user()->bidang->nama_bidang ?? 'Bidang Saya' }}
        </h1>
        <p class="text-sm text-slate-500 mt-2">
            Mengelola dokumen dan data fisik unit kerja bidang {{ auth()->user()->bidang->nama_bidang ?? '' }}.
        </p>
    </div>
    <div class="flex flex-wrap items-center gap-2">
        <button type="button" onclick="document.getElementById('filterPanel').classList.toggle('hidden')" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-lg border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 transition shadow-sm">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
            Advanced Filter
        </button>
        <a href="{{ route('arsip.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-lg bg-primary text-white hover:bg-primary-light transition shadow-sm">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Tambah Arsip Baru
        </a>
    </div>
</div>

{{-- Stat Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    {{-- Total Arsip --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 flex items-start gap-4 hover:shadow-md transition-shadow duration-200">
        <div class="w-11 h-11 rounded-xl bg-primary/10 flex items-center justify-center shrink-0">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#1B3A5C" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        </div>
        <div>
            <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wider">Total Arsip</p>
            <p class="text-3xl font-extrabold text-slate-900 mt-1">{{ number_format($totalArsip) }}</p>
        </div>
    </div>
    {{-- Arsip Aktif --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 flex items-start gap-4 hover:shadow-md transition-shadow duration-200">
        <div class="w-11 h-11 rounded-xl bg-emerald-50 flex items-center justify-center shrink-0">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
        </div>
        <div>
            <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wider">Arsip Aktif</p>
            <p class="text-3xl font-extrabold text-slate-900 mt-1">{{ number_format($arsipAktif) }}</p>
        </div>
    </div>
    {{-- Sedang Dipinjam --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 flex items-start gap-4 hover:shadow-md transition-shadow duration-200">
        <div class="w-11 h-11 rounded-xl bg-amber-50 flex items-center justify-center shrink-0">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#D97706" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
        </div>
        <div>
            <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wider">Sedang Dipinjam</p>
            <p class="text-3xl font-extrabold text-slate-900 mt-1">{{ number_format($arsipDipinjam) }}</p>
        </div>
    </div>
</div>

{{-- Filter Panel --}}
<div id="filterPanel" class="hidden bg-white rounded-xl border border-slate-200 p-5 mb-6 shadow-sm">
    <form method="GET" action="{{ route('arsip.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
        <div class="sm:col-span-2">
            <label class="block text-xs font-semibold text-slate-500 mb-1.5">Cari</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Kode, no. berkas, uraian..." class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
        </div>
        <button type="submit" class="px-4 py-2.5 text-sm font-semibold rounded-lg bg-primary text-white hover:bg-primary-light transition">Terapkan</button>
    </form>
</div>

{{-- Table Section --}}
<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    {{-- Table Header --}}
    <div class="flex items-center justify-between px-6 py-4 bg-primary/[0.03] border-b border-slate-200">
        <h2 class="text-base font-bold text-slate-800">
            Daftar Arsip Unit {{ auth()->user()->bidang->nama_bidang ?? '' }}
        </h2>
        <div class="flex items-center gap-1">
            <a href="{{ route('report.arsip.excel') }}" class="p-2 rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition" title="Export Excel">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            </a>
        </div>
    </div>

    {{-- Data Table --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-primary-dark text-white text-xs uppercase tracking-wider">
                    <th class="px-5 py-3.5 text-left font-semibold whitespace-nowrap">No. Berkas</th>
                    <th class="px-5 py-3.5 text-left font-semibold">Judul & Perihal</th>
                    <th class="px-5 py-3.5 text-left font-semibold whitespace-nowrap">Tanggal</th>
                    <th class="px-5 py-3.5 text-left font-semibold whitespace-nowrap">Lokasi Fisik</th>
                    <th class="px-5 py-3.5 text-left font-semibold whitespace-nowrap">Umur Arsip</th>
                    <th class="px-5 py-3.5 text-center font-semibold whitespace-nowrap">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($arsipList as $arsip)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-5 py-4 font-bold text-primary whitespace-nowrap">{{ $arsip->no_berkas }}</td>
                    <td class="px-5 py-4">
                        <p class="font-bold text-slate-800">{{ $arsip->uraian_berkas }}</p>
                        <p class="text-xs text-slate-400 mt-1 max-w-[280px] truncate">{{ $arsip->uraian_arsip }}</p>
                    </td>
                    <td class="px-5 py-4 text-slate-600 whitespace-nowrap">{{ $arsip->tanggal_diarsipkan?->translatedFormat('d M Y') }}</td>
                    <td class="px-5 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-1.5">
                            <span class="inline-flex items-center gap-1 text-xs text-slate-600">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
                                Rak {{ $arsip->no_rak ?: '-' }} /
                            </span>
                            <span class="text-xs text-slate-600">Boks {{ $arsip->no_boks ?: '-' }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-2.5">
                            @php
                                $umurHari = $arsip->umur_hari;
                                $maxHari = 365 * 5; // 5 tahun max
                                $persen = min(100, ($umurHari / max(1, $maxHari)) * 100);
                                $barColor = $persen < 30 ? 'bg-emerald-400' : ($persen < 70 ? 'bg-amber-400' : 'bg-primary');
                            @endphp
                            <div class="w-16 h-1.5 bg-slate-200 rounded-full overflow-hidden">
                                <div class="{{ $barColor }} h-full rounded-full" style="width: {{ $persen }}%"></div>
                            </div>
                            <span class="text-xs font-medium text-slate-600">{{ $arsip->umur_arsip }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-4 text-center">
                        <a href="{{ route('arsip.show', $arsip) }}" class="inline-flex items-center px-3.5 py-1.5 text-xs font-semibold rounded-lg border border-primary text-primary hover:bg-primary hover:text-white transition-all duration-200">
                            Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-10 text-center text-slate-400">Belum ada data arsip.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-between flex-wrap gap-3">
        <p class="text-xs text-slate-400">Menampilkan {{ $arsipList->firstItem() ?? 0 }}-{{ $arsipList->lastItem() ?? 0 }} dari {{ number_format($arsipList->total()) }} arsip</p>
        {{ $arsipList->links('components.pagination') }}
    </div>
</div>

{{-- Preview Section --}}
@if($arsipList->count() > 0)
<div class="mt-6 bg-white rounded-xl border border-slate-200 shadow-sm p-6">
    <div class="flex items-center gap-2 mb-4">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#1B3A5C" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
        <h3 class="text-base font-bold text-slate-800">Preview Berkas Terpilih</h3>
    </div>

    @php $firstArsip = $arsipList->first(); @endphp

    @if($firstArsip->file_arsip)
    <div class="mb-5 rounded-xl bg-slate-100 border border-slate-200 overflow-hidden" style="max-height: 200px;">
        @if(in_array(pathinfo($firstArsip->file_arsip, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png']))
            <img src="{{ asset('storage/' . $firstArsip->file_arsip) }}" alt="Preview" class="w-full h-48 object-cover">
        @else
            <div class="flex items-center justify-center h-48 text-slate-400">
                <div class="text-center">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="mx-auto mb-2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    <p class="text-xs">{{ strtoupper(pathinfo($firstArsip->file_arsip, PATHINFO_EXTENSION)) }}</p>
                </div>
            </div>
        @endif
    </div>
    @else
    <div class="mb-5 rounded-xl bg-slate-100 border border-slate-200 overflow-hidden flex items-center justify-center h-48 text-slate-400">
        <div class="text-center">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="mx-auto mb-2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            <p class="text-xs">Tidak ada file</p>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div>
            <p class="text-[0.65rem] font-semibold text-slate-400 uppercase tracking-wider">Klasifikasi</p>
            <p class="text-sm font-bold text-slate-800 mt-1">{{ $firstArsip->kode_klasifikasi }}</p>
        </div>
        <div>
            <p class="text-[0.65rem] font-semibold text-slate-400 uppercase tracking-wider">Tingkat Perkembangan</p>
            <p class="text-sm font-bold text-slate-800 mt-1">{{ $firstArsip->tingkat_perkembangan ?: 'N/A' }}</p>
        </div>
        <div>
            <p class="text-[0.65rem] font-semibold text-slate-400 uppercase tracking-wider">Media Simpan</p>
            <p class="text-sm font-bold text-slate-800 mt-1">{{ $firstArsip->lokasi_simpan ?: 'Kertas' }}</p>
        </div>
        <div>
            <p class="text-[0.65rem] font-semibold text-slate-400 uppercase tracking-wider">Petugas Entri</p>
            <p class="text-sm font-bold text-slate-800 mt-1">{{ $firstArsip->user->name ?? 'N/A' }}</p>
        </div>
    </div>
</div>
@endif

@else
{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- ADMIN: MANAJEMEN ARSIP (unchanged) --}}
{{-- ═══════════════════════════════════════════════════════════ --}}

<div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 mb-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <p class="text-sm uppercase tracking-[0.2em] text-primary/80 font-semibold mb-2">Manajemen Arsip</p>
            <h1 class="text-3xl font-bold text-slate-900">Manajemen Semua Arsip</h1>
            <p class="text-sm text-slate-500 mt-2">Kendali penuh atas seluruh data arsip dari semua bidang.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <button type="button" onclick="document.getElementById('filterPanel').classList.toggle('hidden')" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-full border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 transition shadow-sm">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                Filter
            </button>
            <a href="{{ route('arsip.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-full bg-primary text-white hover:bg-primary-light transition shadow-sm">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Tambah Arsip Baru
            </a>
        </div>
    </div>
</div>

<div class="flex items-start gap-3 bg-primary/5 border border-primary/10 rounded-xl px-5 py-4 mb-6">
    <div class="w-7 h-7 rounded-full bg-primary/10 flex items-center justify-center shrink-0 mt-0.5">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#1B3A5C" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
    </div>
    <div>
        <p class="text-sm font-semibold text-primary">Mode Administrator Aktif</p>
        <p class="text-xs text-slate-500 mt-0.5">Anda dapat melihat, mengubah, dan menghapus seluruh data arsip lintas bidang. Perubahan akan dicatat di log sistem.</p>
    </div>
</div>

<div id="filterPanel" class="hidden bg-white rounded-xl border border-slate-200 p-5 mb-6 shadow-sm">
    <form method="GET" action="{{ route('arsip.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
        <div class="sm:col-span-2">
            <label class="block text-xs font-semibold text-slate-500 mb-1.5">Cari</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Kode, no. berkas, uraian..." class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1.5">Bidang</label>
            <select name="bidang_id" class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
                <option value="">Semua Bidang</option>
                @foreach($bidangList as $b)
                <option value="{{ $b->id }}" {{ request('bidang_id') == $b->id ? 'selected' : '' }}>{{ $b->nama_bidang }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="px-4 py-2.5 text-sm font-semibold rounded-lg bg-primary text-white hover:bg-primary-light transition">Terapkan</button>
    </form>
</div>

<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
        <p class="text-sm text-slate-600"><span class="font-semibold text-slate-800">{{ number_format($arsipList->total()) }}</span> Total Arsip (Semua Bidang)</p>
        <div class="flex items-center gap-1">
            <a href="{{ route('report.arsip.excel') }}" class="p-2 rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition" title="Export Excel">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            </a>
            <a href="{{ route('report.arsip.pdf') }}" target="_blank" class="p-2 rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition" title="Cetak PDF">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
            </a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-primary text-white text-xs uppercase tracking-wide">
                    <th class="px-5 py-3 text-left font-semibold whitespace-nowrap">No. Arsip</th>
                    <th class="px-5 py-3 text-left font-semibold">Judul & Bidang</th>
                    <th class="px-5 py-3 text-left font-semibold whitespace-nowrap">Tanggal</th>
                    <th class="px-5 py-3 text-left font-semibold whitespace-nowrap">Lokasi</th>
                    <th class="px-5 py-3 text-left font-semibold whitespace-nowrap">Status</th>
                    <th class="px-5 py-3 text-right font-semibold whitespace-nowrap">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($arsipList as $arsip)
                <tr class="hover:bg-slate-50">
                    <td class="px-5 py-3.5 font-semibold text-primary whitespace-nowrap">{{ $arsip->no_berkas }}</td>
                    <td class="px-5 py-3.5">
                        <p class="font-semibold text-slate-800">{{ $arsip->uraian_berkas }}</p>
                        <div class="flex items-center gap-1.5 mt-1">
                            <span class="px-1.5 py-0.5 rounded text-[0.65rem] font-bold bg-slate-100 text-slate-500 uppercase">{{ $arsip->kode_klasifikasi }}</span>
                            <span class="text-xs text-slate-400">{{ $arsip->bidang->nama_bidang ?? '-' }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-3.5 text-slate-600 whitespace-nowrap">{{ $arsip->tanggal_diarsipkan?->translatedFormat('d M Y') }}</td>
                    <td class="px-5 py-3.5 whitespace-nowrap">
                        <span class="px-2 py-1 rounded-md bg-slate-100 text-slate-600 text-xs font-medium">Rak-{{ $arsip->no_rak ?: '-' }} / Boks-{{ $arsip->no_boks ?: '-' }}</span>
                    </td>
                    <td class="px-5 py-3.5 whitespace-nowrap">
                        @php
                            $statusColor = match($arsip->status_arsip) {
                                'dipinjam' => 'bg-amber-100 text-amber-700',
                                default => 'bg-emerald-100 text-emerald-700',
                            };
                        @endphp
                        <span class="px-2.5 py-1 rounded-full text-[0.7rem] font-semibold {{ $statusColor }}">{{ $arsip->status_arsip === 'dipinjam' ? 'Dipinjam' : 'Tersedia' }}</span>
                    </td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('arsip.show', $arsip) }}" class="p-1.5 rounded-md text-slate-400 hover:bg-slate-100 hover:text-primary transition" title="Lihat"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></a>
                            <a href="{{ route('arsip.edit', $arsip) }}" class="p-1.5 rounded-md text-slate-400 hover:bg-slate-100 hover:text-amber-600 transition" title="Edit"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4z"/></svg></a>
                            <button type="button" onclick="confirmDelete('{{ route('arsip.destroy', $arsip) }}')" class="p-1.5 rounded-md text-slate-400 hover:bg-red-50 hover:text-red-600 transition" title="Hapus"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-10 text-center text-slate-400">Belum ada data arsip.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-5 py-4 border-t border-slate-100 flex items-center justify-between flex-wrap gap-3">
        <p class="text-xs text-slate-400">Menampilkan {{ $arsipList->firstItem() ?? 0 }}-{{ $arsipList->lastItem() ?? 0 }} dari {{ number_format($arsipList->total()) }} data</p>
        {{ $arsipList->links('components.pagination') }}
    </div>
</div>

@endif
@endsection
