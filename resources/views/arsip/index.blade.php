@extends('layouts.app')
@section('title', 'Manajemen Arsip')
@section('content')

<div class="flex items-start justify-between mb-6 flex-wrap gap-3">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">{{ auth()->user()->isAdmin() ? 'Manajemen Semua Arsip' : 'Manajemen Arsip Bidang' }}</h1>
        <p class="text-sm text-slate-500 mt-1">{{ auth()->user()->isAdmin() ? 'Kendali penuh atas seluruh data arsip dari semua bidang.' : 'Kelola data arsip untuk bidang ' . (auth()->user()->bidang->nama_bidang ?? '-') . '.' }}</p>
    </div>
    <div class="flex items-center gap-2">
        <button type="button" onclick="document.getElementById('filterPanel').classList.toggle('hidden')" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-lg border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 transition shadow-sm">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
            Filter Bidang
        </button>
        <a href="{{ route('arsip.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-lg bg-primary text-white hover:bg-primary-light transition shadow-sm">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Tambah Arsip Baru
        </a>
    </div>
</div>

@if(auth()->user()->isAdmin())
<div class="flex items-start gap-3 bg-primary/5 border border-primary/10 rounded-xl px-5 py-4 mb-6">
    <div class="w-7 h-7 rounded-full bg-primary/10 flex items-center justify-center shrink-0 mt-0.5">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#1B3A5C" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
    </div>
    <div>
        <p class="text-sm font-semibold text-primary">Mode Administrator Aktif</p>
        <p class="text-xs text-slate-500 mt-0.5">Anda dapat melihat, mengubah, dan menghapus seluruh data arsip lintas bidang. Perubahan akan dicatat di log sistem.</p>
    </div>
</div>
@endif

<div id="filterPanel" class="hidden bg-white rounded-xl border border-slate-200 p-5 mb-6 shadow-sm">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-sm font-semibold text-slate-700">Filter Lanjutan</h3>
        <a href="{{ route('arsip.index') }}" class="text-xs font-semibold text-primary hover:underline">Reset Filter</a>
    </div>
    <form method="GET" action="{{ route('arsip.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="sm:col-span-2">
            <label class="block text-xs font-semibold text-slate-500 mb-1.5">Cari</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Kode, no. berkas, uraian..." class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
        </div>
        @if(auth()->user()->isAdmin())
        <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1.5">Bidang</label>
            <select name="bidang_id" class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
                <option value="">Semua Bidang</option>
                @foreach($bidangList as $b)
                <option value="{{ $b->id }}" {{ request('bidang_id') == $b->id ? 'selected' : '' }}>{{ $b->nama_bidang }}</option>
                @endforeach
            </select>
        </div>
        @endif
        <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1.5">Kode Klasifikasi</label>
            <input type="text" name="kode_klasifikasi" value="{{ request('kode_klasifikasi') }}" placeholder="Contoh: 005/TU" class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1.5">Status Retensi</label>
            <select name="status_retensi" class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
                <option value="">Semua</option>
                <option value="aktif" {{ request('status_retensi') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="inaktif" {{ request('status_retensi') === 'inaktif' ? 'selected' : '' }}>Inaktif</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1.5">Klasifikasi Keamanan</label>
            <select name="klasifikasi_keamanan" class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
                <option value="">Semua</option>
                <option value="biasa" {{ request('klasifikasi_keamanan') === 'biasa' ? 'selected' : '' }}>Biasa</option>
                <option value="terbatas" {{ request('klasifikasi_keamanan') === 'terbatas' ? 'selected' : '' }}>Terbatas</option>
                <option value="rahasia" {{ request('klasifikasi_keamanan') === 'rahasia' ? 'selected' : '' }}>Rahasia</option>
                <option value="sangat_rahasia" {{ request('klasifikasi_keamanan') === 'sangat_rahasia' ? 'selected' : '' }}>Sangat Rahasia</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1.5">Status Arsip</label>
            <select name="status_arsip" class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
                <option value="">Semua</option>
                <option value="tersedia" {{ request('status_arsip') === 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                <option value="dipinjam" {{ request('status_arsip') === 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1.5">No. Rak</label>
            <input type="text" name="no_rak" value="{{ request('no_rak') }}" placeholder="No. Rak" class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1.5">No. Boks</label>
            <input type="text" name="no_boks" value="{{ request('no_boks') }}" placeholder="No. Boks" class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
        </div>
        <div class="sm:col-span-2">
            <label class="block text-xs font-semibold text-slate-500 mb-1.5">Rentang Tanggal Diarsipkan</label>
            <div class="flex items-center gap-1.5">
                <input type="date" name="dari" value="{{ request('dari') }}" class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
                <span class="text-slate-300">–</span>
                <input type="date" name="sampai" value="{{ request('sampai') }}" class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
            </div>
        </div>
        <div class="sm:col-span-2 lg:col-span-4 flex justify-end">
            <button type="submit" class="px-5 py-2.5 text-sm font-semibold rounded-lg bg-primary text-white hover:bg-primary-light transition">Terapkan Filter</button>
        </div>
    </form>
</div>

<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
        <p class="text-sm text-slate-600"><span class="font-semibold text-slate-800">{{ number_format($arsipList->total()) }}</span> Total Arsip {{ auth()->user()->isAdmin() ? '(Semua Bidang)' : '' }}</p>
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
@endsection