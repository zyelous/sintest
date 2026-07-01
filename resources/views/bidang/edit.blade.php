@extends('layouts.app')
@section('title', 'Edit Bidang')
@section('breadcrumb')
<a href="{{ route('dashboard') }}" class="hover:text-primary">Manajemen</a> <span>/</span> <a href="{{ route('bidang.index') }}" class="hover:text-primary">Daftar Bidang</a> <span>/</span> <span class="text-slate-700 font-medium">Edit Bidang</span>
@endsection
@section('content')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 shadow-sm p-6">
        <h1 class="text-xl font-bold text-slate-800">Edit Data Bidang</h1>
        <p class="text-sm text-slate-500 mt-1 mb-6">Pastikan seluruh data departemen diperbarui sesuai dengan Struktur Organisasi (SOTK) terbaru.</p>

        <form action="{{ route('bidang.update', $bidang) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-5">
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Kode Bidang</label>
                <input type="text" value="{{ $bidang->kode_bidang }}" disabled class="w-full rounded-lg border-slate-200 bg-slate-50 text-sm text-slate-500">
                <p class="text-xs text-slate-400 mt-1">Kode bidang bersifat permanen dan tidak dapat diubah.</p>
            </div>
            <div class="mb-6">
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nama Bidang</label>
                <input type="text" name="nama_bidang" value="{{ old('nama_bidang', $bidang->nama_bidang) }}" required class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary @error('nama_bidang') border-red-400 @enderror">
                @error('nama_bidang')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-center gap-3 pt-5 border-t border-slate-100">
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold rounded-lg bg-primary text-white hover:bg-primary-light transition shadow-sm">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Simpan Perubahan
                </button>
                <a href="{{ route('bidang.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-50 transition">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                    Kembali
                </a>
            </div>
        </form>
    </div>

    <div class="space-y-6">
        <div class="bg-primary rounded-xl shadow-sm p-5 text-white">
            <div class="flex items-center gap-2 mb-4">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                <h3 class="font-semibold text-sm">Statistik Bidang</h3>
            </div>
            <div class="space-y-3">
                <div class="bg-white/10 rounded-lg px-4 py-3">
                    <p class="text-[0.65rem] font-semibold text-white/60 uppercase">Total Personel</p>
                    <p class="text-2xl font-bold mt-0.5">{{ str_pad($bidang->users()->count(), 2, '0', STR_PAD_LEFT) }} <span class="text-sm font-normal text-white/70">Staf Aktif</span></p>
                </div>
                <div class="bg-white/10 rounded-lg px-4 py-3">
                    <p class="text-[0.65rem] font-semibold text-white/60 uppercase">Dokumen Tersimpan</p>
                    <p class="text-2xl font-bold mt-0.5">{{ number_format($bidang->arsip()->count()) }} <span class="text-sm font-normal text-white/70">Arsip</span></p>
                </div>
            </div>
            <p class="text-[0.65rem] text-white/50 mt-4">Data diperbarui terakhir pada: {{ $bidang->updated_at?->translatedFormat('d M Y, H:i') }} WIB</p>
        </div>

        <div class="bg-primary/5 rounded-xl border border-primary/10 p-5">
            <h3 class="font-semibold text-slate-700 text-sm mb-4">Aktivitas Terakhir</h3>
            <div class="flex gap-3 mb-4">
                <div class="w-7 h-7 rounded-full bg-amber-100 flex items-center justify-center shrink-0">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#D97706" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-700">Update Data Bidang</p>
                    <p class="text-xs text-slate-400">Oleh {{ auth()->user()->name }} · {{ $bidang->updated_at?->diffForHumans() }}</p>
                </div>
            </div>
            <div class="flex gap-3">
                <div class="w-7 h-7 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#1B3A5C" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-700">Bidang Dibuat</p>
                    <p class="text-xs text-slate-400">{{ $bidang->created_at?->translatedFormat('d M Y') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
