@extends('layouts.app')
@section('title', 'Peminjaman Arsip')

@section('breadcrumb')
<a href="{{ route('dashboard') }}" class="hover:text-primary transition">Dashboard</a>
<span class="text-slate-300">/</span>
<span class="text-slate-600 font-medium">Peminjaman Arsip</span>
@endsection

@section('content')
<div class="flex items-start justify-between mb-6 flex-wrap gap-3">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Peminjaman Arsip</h1>
        <p class="text-sm text-slate-500 mt-1">Kelola pencatatan dan status peminjaman berkas arsip fisik.</p>
    </div>
    <div class="flex items-center gap-2">
        <button type="button" onclick="document.getElementById('filterPanel').classList.toggle('hidden')" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-lg border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 transition shadow-sm">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
            Filter Data
        </button>
        <a href="{{ route('peminjaman.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-lg bg-primary text-white hover:bg-primary-light transition shadow-sm">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Pinjam Arsip Baru
        </a>
    </div>
</div>

{{-- Statistics Row --}}
@php
    $baseQuery = \App\Models\PeminjamanArsip::query();
    if (auth()->user()->isOperator()) {
        $baseQuery->whereHas('arsip', fn($q) => $q->where('bidang_id', auth()->user()->bidang_id));
    }
    $totalCount = (clone $baseQuery)->count();
    $activeCount = (clone $baseQuery)->where('status', 'dipinjam')->count();
    $lateCount = (clone $baseQuery)->where('status', 'dipinjam')->where('tanggal_pinjam', '<', now()->subDays(14))->count();
@endphp
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 flex items-start gap-4 hover:shadow-md transition-shadow duration-200">
        <div class="w-11 h-11 rounded-xl bg-slate-100 flex items-center justify-center shrink-0">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        </div>
        <div>
            <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wider">Total Peminjaman</p>
            <p class="text-3xl font-extrabold text-slate-800 mt-1">{{ number_format($totalCount) }}</p>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 flex items-start gap-4 hover:shadow-md transition-shadow duration-200">
        <div class="w-11 h-11 rounded-xl bg-amber-50 flex items-center justify-center shrink-0">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#D97706" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>
        <div>
            <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wider">Sedang Dipinjam</p>
            <p class="text-3xl font-extrabold text-amber-600 mt-1">{{ number_format($activeCount) }}</p>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 flex items-start gap-4 hover:shadow-md transition-shadow duration-200">
        <div class="w-11 h-11 rounded-xl bg-red-50 flex items-center justify-center shrink-0">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#DC2626" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        </div>
        <div>
            <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wider">Terlambat Kembali</p>
            <p class="text-3xl font-extrabold text-red-600 mt-1">{{ number_format($lateCount) }}</p>
        </div>
    </div>
</div>

{{-- Filter Panel --}}
<div id="filterPanel" class="{{ request()->hasAny(['search', 'status', 'tanggal_dari', 'tanggal_sampai']) ? '' : 'hidden' }} bg-white rounded-xl border border-slate-200 p-5 mb-6 shadow-sm">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-sm font-semibold text-slate-700">Filter Lanjutan</h3>
        <a href="{{ route('peminjaman.index') }}" class="text-xs font-semibold text-primary hover:underline">Reset Filter</a>
    </div>
    <form method="GET" action="{{ route('peminjaman.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="sm:col-span-2">
            <label class="block text-xs font-semibold text-slate-500 mb-1.5">Kata Kunci Cari</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama peminjam, bidang, kode arsip..." class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1.5">Status Peminjaman</label>
            <select name="status" class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
                <option value="">Semua Status</option>
                <option value="dipinjam" {{ request('status') === 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                <option value="dikembalikan" {{ request('status') === 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1.5">Rentang Tanggal Dari</label>
            <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}" class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1.5">Sampai Tanggal</label>
            <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}" class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
        </div>
        <div class="sm:col-span-2 lg:col-span-4 flex justify-end gap-2 mt-2">
            <button type="submit" class="px-5 py-2.5 text-sm font-semibold rounded-lg bg-primary text-white hover:bg-primary-light transition">Terapkan Filter</button>
        </div>
    </form>
</div>

{{-- Table Content --}}
<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
        <p class="text-sm text-slate-600"><span class="font-semibold text-slate-800">{{ number_format($peminjamanList->total()) }}</span> Transaksi Peminjaman</p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-primary text-white text-xs uppercase tracking-wide">
                    <th class="px-5 py-3 text-left font-semibold whitespace-nowrap">No</th>
                    <th class="px-5 py-3 text-left font-semibold">Arsip</th>
                    <th class="px-5 py-3 text-left font-semibold">Peminjam</th>
                    <th class="px-5 py-3 text-left font-semibold whitespace-nowrap">Tgl Pinjam</th>
                    <th class="px-5 py-3 text-left font-semibold whitespace-nowrap">Tgl Kembali</th>
                    <th class="px-5 py-3 text-left font-semibold whitespace-nowrap">Status</th>
                    <th class="px-5 py-3 text-right font-semibold whitespace-nowrap">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($peminjamanList as $i => $p)
                <tr class="hover:bg-slate-50">
                    <td class="px-5 py-3.5 text-slate-500 whitespace-nowrap">{{ $peminjamanList->firstItem() + $i }}</td>
                    <td class="px-5 py-3.5">
                        <p class="font-semibold text-slate-800">{{ $p->arsip->uraian_berkas ?? '-' }}</p>
                        <div class="flex items-center gap-1.5 mt-1">
                            <span class="px-1.5 py-0.5 rounded text-[0.65rem] font-bold bg-slate-100 text-slate-500 uppercase">{{ $p->arsip->kode_klasifikasi ?? '-' }} - {{ $p->arsip->no_berkas ?? '-' }}</span>
                            <span class="text-xs text-slate-400">{{ $p->arsip->bidang->nama_bidang ?? '-' }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-3.5">
                        <p class="font-semibold text-slate-800">{{ $p->nama_peminjam }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">Bidang: {{ $p->bidang_peminjam }}</p>
                    </td>
                    <td class="px-5 py-3.5 text-slate-600 whitespace-nowrap">{{ $p->tanggal_pinjam->translatedFormat('d M Y') }}</td>
                    <td class="px-5 py-3.5 text-slate-600 whitespace-nowrap">
                        @if($p->tanggal_kembali)
                            {{ $p->tanggal_kembali->translatedFormat('d M Y') }}
                        @else
                            <span class="text-slate-300">-</span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5 whitespace-nowrap">
                        @if($p->status === 'dikembalikan')
                            <span class="px-2.5 py-1 rounded-full text-[0.7rem] font-semibold bg-emerald-100 text-emerald-700">Dikembalikan</span>
                        @elseif($p->tanggal_pinjam->diffInDays(now()) > 14)
                            <span class="px-2.5 py-1 rounded-full text-[0.7rem] font-semibold bg-red-100 text-red-700">Terlambat ({{ $p->durasi_pinjam }})</span>
                        @else
                            <span class="px-2.5 py-1 rounded-full text-[0.7rem] font-semibold bg-amber-100 text-amber-700">Dipinjam ({{ $p->durasi_pinjam }})</span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5 whitespace-nowrap">
                        <div class="flex items-center justify-end gap-1.5">
                            <a href="{{ route('peminjaman.show', $p) }}" class="p-1.5 rounded-md text-slate-400 hover:bg-slate-100 hover:text-primary transition" title="Detail">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            </a>
                            @if($p->status === 'dipinjam')
                            <form method="POST" action="{{ route('peminjaman.kembalikan', $p) }}" class="inline">
                                @csrf @method('PUT')
                                <button type="submit" class="p-1.5 rounded-md text-slate-400 hover:bg-emerald-50 hover:text-emerald-600 transition" onclick="return confirm('Konfirmasi pengembalian arsip ini?')" title="Kembalikan">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                                </button>
                            </form>
                            @else
                            <button type="button" onclick="confirmDelete('{{ route('peminjaman.destroy', $p) }}')" class="p-1.5 rounded-md text-slate-400 hover:bg-red-50 hover:text-red-600 transition" title="Hapus">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-10 text-center text-slate-400">Tidak ada data transaksi peminjaman.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-5 py-4 border-t border-slate-100 flex items-center justify-between flex-wrap gap-3">
        <p class="text-xs text-slate-400">Menampilkan {{ $peminjamanList->firstItem() ?? 0 }}-{{ $peminjamanList->lastItem() ?? 0 }} dari {{ number_format($peminjamanList->total()) }} data</p>
        {{ $peminjamanList->links('components.pagination') }}
    </div>
</div>
@endsection
