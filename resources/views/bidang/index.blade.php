@extends('layouts.app')
@section('title', 'Manajemen Bidang')
@section('breadcrumb')
<a href="{{ route('dashboard') }}" class="hover:text-primary">Manajemen</a> <span>/</span> <span class="text-slate-700 font-medium">Daftar Bidang</span>
@endsection
@section('content')

<div class="flex items-start justify-between mb-6 flex-wrap gap-3">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Manajemen Bidang</h1>
        <p class="text-sm text-slate-500 mt-1">Kelola struktur organisasi dan unit kerja Bappeda Provinsi Lampung.</p>
    </div>
    <a href="{{ route('bidang.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-lg bg-primary text-white hover:bg-primary-light transition shadow-sm">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Tambah Bidang Baru
    </a>
</div>

<form method="GET" action="{{ route('bidang.index') }}" class="mb-6">
    <div class="relative max-w-sm">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau kode bidang..." class="w-full bg-white rounded-lg border-slate-300 pl-9 text-sm focus:ring-primary focus:border-primary">
    </div>
</form>

<div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 mb-6 flex items-center justify-between">
    <div>
        <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide">Total Bidang</p>
        <p class="text-3xl font-bold text-slate-800 mt-1">{{ str_pad($bidangList->total(), 2, '0', STR_PAD_LEFT) }}</p>
    </div>
    <div class="w-11 h-11 rounded-lg bg-primary/10 flex items-center justify-center">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1B3A5C" stroke-width="2"><rect x="3" y="7" width="18" height="14" rx="2"/><path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
    @forelse($bidangList as $b)
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
        <div class="w-11 h-11 rounded-lg bg-primary/10 flex items-center justify-center mb-4">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1B3A5C" stroke-width="2"><rect x="4" y="2" width="16" height="20" rx="1"/><line x1="9" y1="8" x2="15" y2="8"/><line x1="9" y1="12" x2="15" y2="12"/><line x1="9" y1="16" x2="12" y2="16"/></svg>
        </div>
        <h3 class="font-bold text-slate-800 text-lg">{{ $b->nama_bidang }}</h3>
        <p class="text-xs text-slate-400 mb-4"># KODE: {{ $b->kode_bidang }}</p>
        <div class="flex items-center justify-between pt-4 border-t border-slate-100 mb-4">
            <div>
                <p class="text-[0.65rem] font-semibold text-slate-400 uppercase">Operator Utama</p>
                <p class="text-sm font-semibold text-slate-700 mt-0.5">{{ $b->users_count }} Personel</p>
            </div>
            <div class="text-right">
                <p class="text-[0.65rem] font-semibold text-slate-400 uppercase">Total Arsip</p>
                <p class="text-sm font-semibold text-slate-700 mt-0.5">{{ number_format($b->arsip_count) }} Dokumen</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('bidang.edit', $b) }}" class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 text-sm font-semibold rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-50 transition">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4z"/></svg>
                Edit
            </a>
            <button type="button" onclick="confirmDelete('{{ route('bidang.destroy', $b) }}')" class="inline-flex items-center justify-center px-3 py-2 rounded-lg border border-red-200 text-red-500 hover:bg-red-50 transition">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
            </button>
        </div>
    </div>
    @empty
    <p class="text-slate-400 col-span-2 text-center py-10">Belum ada data bidang.</p>
    @endforelse

    <a href="{{ route('bidang.create') }}" class="border-2 border-dashed border-slate-300 rounded-xl flex flex-col items-center justify-center py-10 text-slate-400 hover:border-primary hover:text-primary hover:bg-primary/5 transition">
        <div class="w-11 h-11 rounded-full border-2 border-current flex items-center justify-center mb-3">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        </div>
        <p class="font-semibold text-sm">Tambah Unit</p>
        <p class="text-xs mt-1 text-center px-6">Daftarkan sub-bidang atau unit kerja baru ke dalam sistem.</p>
    </a>
</div>

<div class="mt-6">{{ $bidangList->links('components.pagination') }}</div>
@endsection
