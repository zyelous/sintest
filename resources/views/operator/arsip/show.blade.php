@extends('layouts.app')
@section('title', $arsip->uraian_berkas)
@section('content')

<div class="flex items-start justify-between mb-6 flex-wrap gap-3">
    <h1 class="text-2xl font-bold text-slate-800">{{ $arsip->uraian_berkas }}</h1>
    <div class="flex items-center gap-2">
        <a href="{{ route('operator.arsip.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-lg border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 transition shadow-sm">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            Kembali
        </a>
        <a href="{{ route('operator.arsip.edit', $arsip) }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-lg bg-accent-gold text-primary-dark hover:bg-accent-gold-dark transition shadow-sm">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4z"/></svg>
            Edit Data
        </a>
        @if($arsip->file_arsip)
        <a href="{{ route('operator.arsip.download', $arsip) }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-lg bg-primary text-white hover:bg-primary-light transition shadow-sm">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            Unduh Berkas
        </a>
        @endif
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center gap-2">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#1B3A5C" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                <h3 class="font-semibold text-slate-800 text-sm uppercase tracking-wide">Informasi Utama</h3>
            </div>
            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide">Nomor Arsip</p>
                    <p class="font-bold text-primary mt-1">{{ $arsip->no_berkas }}</p>
                </div>
                <div>
                    <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide">Tanggal Dokumen</p>
                    <p class="font-bold text-slate-800 mt-1">{{ $arsip->tanggal_diarsipkan?->translatedFormat('d F Y') }}</p>
                </div>
                <div class="sm:col-span-2">
                    <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide">Judul Dokumen</p>
                    <p class="font-semibold text-slate-800 mt-1">{{ $arsip->uraian_berkas }}</p>
                    @if($arsip->uraian_arsip)<p class="text-sm text-slate-500 mt-1">{{ $arsip->uraian_arsip }}</p>@endif
                </div>
                <div>
                    <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide">Kode Klasifikasi</p>
                    <span class="inline-block mt-1.5 px-3 py-1 rounded-full bg-primary/10 text-primary text-xs font-semibold">{{ $arsip->kode_klasifikasi }}</span>
                </div>
                <div>
                    <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide">Bidang Pengolah</p>
                    <p class="font-semibold text-slate-800 mt-1">{{ $arsip->bidang->nama_bidang ?? '-' }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                <div class="flex items-center gap-2 mb-4">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#1B3A5C" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    <h3 class="font-semibold text-slate-800 text-sm uppercase tracking-wide">Klasifikasi & Keamanan</h3>
                </div>
                <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide">Tingkat Keamanan</p>
                @php
                    $secColor = match($arsip->klasifikasi_keamanan) {
                        'sangat_rahasia' => 'bg-red-50 text-red-600',
                        'rahasia' => 'bg-orange-50 text-orange-600',
                        'terbatas' => 'bg-amber-50 text-amber-600',
                        default => 'bg-emerald-50 text-emerald-600',
                    };
                @endphp
                <span class="inline-flex items-center gap-1.5 mt-2 px-3 py-1.5 rounded-lg text-sm font-semibold {{ $secColor }}">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M12 1a5 5 0 0 0-5 5v3H5a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-9a2 2 0 0 0-2-2h-2V6a5 5 0 0 0-5-5z"/></svg>
                    {{ ucwords(str_replace('_', ' ', $arsip->klasifikasi_keamanan)) }}
                </span>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                <div class="flex items-center gap-2 mb-4">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#1B3A5C" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    <h3 class="font-semibold text-slate-800 text-sm uppercase tracking-wide">Lokasi Fisik</h3>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-slate-50 rounded-lg px-3 py-2 flex items-center justify-between">
                        <span class="text-xs font-medium text-slate-500">Nomor Rak</span>
                        <span class="font-bold text-slate-800">{{ $arsip->no_rak ?: '-' }}</span>
                    </div>
                    <div class="bg-slate-50 rounded-lg px-3 py-2 flex items-center justify-between">
                        <span class="text-xs font-medium text-slate-500">Nomor Folder</span>
                        <span class="font-bold text-slate-800">{{ $arsip->no_folder ?: '-' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 text-center">
            <div class="w-full aspect-[4/3] rounded-lg bg-gradient-to-br from-primary/10 to-primary/5 flex flex-col items-center justify-center mb-3">
                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#1B3A5C" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            </div>
            <p class="text-sm font-semibold text-slate-800">{{ $arsip->file_arsip ? 'Berkas Tersedia' : 'Belum Ada Berkas Digital' }}</p>
            @if($arsip->file_arsip)
            <a href="{{ route('operator.arsip.download', $arsip) }}" class="mt-3 w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-lg bg-primary text-white hover:bg-primary-light transition">
                Unduh Berkas
            </a>
            @endif
        </div>
    </div>
</div>
@endsection
