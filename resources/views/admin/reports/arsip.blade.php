@extends('layouts.app')
@section('title', 'Laporan Data Arsip')
@section('content')

<div class="flex items-center justify-between mb-6 flex-wrap gap-3">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Laporan Data Arsip</h1>
        <p class="text-xs text-slate-500 mt-1">Daftar rekapitulasi data arsip Bappeda Provinsi Lampung.</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route(auth()->user()->isAdmin() ? 'admin.laporan.arsip.pdf' : 'operator.laporan.arsip.pdf', request()->query()) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-lg border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 transition shadow-sm">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            Export PDF
        </a>
        <a href="{{ route(auth()->user()->isAdmin() ? 'admin.laporan.arsip.excel' : 'operator.laporan.arsip.excel', request()->query()) }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 transition shadow-sm">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="9" y1="3" x2="9" y2="21"/></svg>
            Export Excel
        </a>
    </div>
</div>

<div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 mb-6">
    <form method="GET" action="{{ route(auth()->user()->isAdmin() ? 'admin.laporan.arsip' : 'operator.laporan.arsip') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
        <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1">Cari Kata Kunci</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="No / Judul Berkas..." class="w-full px-3.5 py-2 rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
        </div>
        @if(auth()->user()->isAdmin())
        <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1">Bidang / Unit Kerja</label>
            <select name="bidang_id" class="w-full px-3.5 py-2 rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
                <option value="">Semua Bidang</option>
                @foreach($bidangList as $b)
                <option value="{{ $b->id }}" {{ request('bidang_id') == $b->id ? 'selected' : '' }}>{{ $b->nama_bidang }}</option>
                @endforeach
            </select>
        </div>
        @endif
        <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1">Klasifikasi Keamanan</label>
            <select name="klasifikasi_keamanan" class="w-full px-3.5 py-2 rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
                <option value="">Semua Tingkat</option>
                <option value="biasa" {{ request('klasifikasi_keamanan') === 'biasa' ? 'selected' : '' }}>Biasa</option>
                <option value="terbatas" {{ request('klasifikasi_keamanan') === 'terbatas' ? 'selected' : '' }}>Terbatas</option>
                <option value="rahasia" {{ request('klasifikasi_keamanan') === 'rahasia' ? 'selected' : '' }}>Rahasia</option>
                <option value="sangat_rahasia" {{ request('klasifikasi_keamanan') === 'sangat_rahasia' ? 'selected' : '' }}>Sangat Rahasia</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1">Status Retensi</label>
            <select name="status_retensi" class="w-full px-3.5 py-2 rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
                <option value="">Semua Retensi</option>
                <option value="aktif" {{ request('status_retensi') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="inaktif" {{ request('status_retensi') === 'inaktif' ? 'selected' : '' }}>Inaktif</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="flex-1 inline-flex items-center justify-center gap-1.5 px-4 py-2 text-sm font-semibold rounded-lg bg-primary text-white hover:bg-primary-light transition">
                Filter
            </button>
            <a href="{{ route(auth()->user()->isAdmin() ? 'admin.laporan.arsip' : 'operator.laporan.arsip') }}" class="px-3 py-2 rounded-lg border border-slate-300 text-slate-500 hover:bg-slate-50 transition" title="Reset">
                Reset
            </a>
        </div>
    </form>
</div>

<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 text-slate-600 text-xs uppercase tracking-wide border-b border-slate-200">
                    <th class="px-4 py-3 text-left font-semibold">Kode</th>
                    <th class="px-4 py-3 text-left font-semibold">No. Berkas</th>
                    <th class="px-4 py-3 text-left font-semibold">Uraian Berkas</th>
                    <th class="px-4 py-3 text-left font-semibold">Bidang</th>
                    <th class="px-4 py-3 text-left font-semibold">Tgl Diarsipkan</th>
                    <th class="px-4 py-3 text-center font-semibold">Keamanan</th>
                    <th class="px-4 py-3 text-center font-semibold">Retensi</th>
                    <th class="px-4 py-3 text-left font-semibold">Lokasi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($arsipList as $arsip)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-4 py-3 font-semibold text-xs text-primary">{{ $arsip->kode_klasifikasi }}</td>
                    <td class="px-4 py-3 font-medium text-slate-800 whitespace-nowrap">{{ $arsip->no_berkas }}</td>
                    <td class="px-4 py-3">
                        <p class="font-semibold text-slate-800 line-clamp-1">{{ $arsip->uraian_berkas }}</p>
                        @if($arsip->uraian_arsip)<p class="text-xs text-slate-400 line-clamp-1">{{ $arsip->uraian_arsip }}</p>@endif
                    </td>
                    <td class="px-4 py-3 text-slate-600 whitespace-nowrap">{{ $arsip->bidang->nama_bidang ?? '-' }}</td>
                    <td class="px-4 py-3 text-slate-600 whitespace-nowrap">{{ $arsip->tanggal_diarsipkan?->format('d/m/Y') }}</td>
                    <td class="px-4 py-3 text-center whitespace-nowrap">
                        <span class="px-2 py-0.5 rounded text-[0.65rem] font-bold uppercase border border-slate-200">
                            {{ str_replace('_', ' ', $arsip->klasifikasi_keamanan) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center whitespace-nowrap">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $arsip->status_retensi === 'aktif' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                            {{ ucfirst($arsip->status_retensi) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-xs text-slate-500 whitespace-nowrap">
                        Rak: {{ $arsip->no_rak ?: '-' }} | Boks: {{ $arsip->no_boks ?: '-' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-8 text-center text-slate-400">Tidak ada data arsip yang sesuai filter.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($arsipList->hasPages())
    <div class="px-5 py-3 border-t border-slate-100">
        {{ $arsipList->links() }}
    </div>
    @endif
</div>

@endsection
