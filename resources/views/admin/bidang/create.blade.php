@extends('layouts.app')
@section('title', 'Tambah Bidang')
@section('breadcrumb')
<a href="{{ route('admin.dashboard') }}" class="hover:text-primary">Manajemen</a> <span>/</span> <a href="{{ route('admin.bidang.index') }}" class="hover:text-primary">Daftar Bidang</a> <span>/</span> <span class="text-slate-700 font-medium">Tambah Bidang</span>
@endsection
@section('content')

<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-6">
    <div class="bg-primary px-6 py-5">
        <h1 class="text-lg font-bold text-white">Identitas Bidang</h1>
        <p class="text-xs text-white/60 mt-1">Masukkan detail informasi departemen untuk struktur organisasi Bappeda.</p>
    </div>

    <form action="{{ route('admin.bidang.store') }}" method="POST" class="p-6">
        @csrf
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nama Bidang / Departemen <span class="text-red-500">*</span></label>
                <input type="text" name="nama_bidang" value="{{ old('nama_bidang') }}" required placeholder="Contoh: Bidang Infrastruktur dan Kewilayahan" class="w-full px-3.5 py-2.5 rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary @error('nama_bidang') border-red-400 @enderror">
                @error('nama_bidang')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Kode Bidang <span class="text-red-500">*</span></label>
                <input type="text" name="kode_bidang" value="{{ old('kode_bidang') }}" required placeholder="Contoh: INF-002" class="w-full px-3.5 py-2.5 rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary @error('kode_bidang') border-red-400 @enderror">
                @error('kode_bidang')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <h3 class="text-sm font-bold text-primary mt-8 mb-1">Penanggung Jawab</h3>
        <p class="text-xs text-slate-400 mb-4">Tentukan Kepala Bidang atau Operator Utama untuk unit ini (dikelola melalui Manajemen User).</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Kepala Bidang (NIP/Nama)</label>
                <input type="text" name="kepala_bidang" value="{{ old('kepala_bidang') }}" placeholder="Contoh: Drs. Budi Santoso, M.Si / NIP. 19780512..." class="w-full px-3.5 py-2.5 rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-400 mb-1.5">Operator Utama</label>
                <input type="text" disabled placeholder="Ditambahkan setelah bidang dibuat" class="w-full rounded-lg border-slate-200 bg-slate-50 text-sm text-slate-400">
            </div>
        </div>

        <div class="mt-5">
            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Catatan / Deskripsi Tugas</label>
            <textarea name="deskripsi" placeholder="Tuliskan deskripsi singkat mengenai fungsi bidang ini..." class="w-full px-3.5 py-2.5 rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary" rows="4">{{ old('deskripsi') }}</textarea>
        </div>

        <div class="flex items-center justify-end gap-3 mt-8 pt-5 border-t border-slate-100">
            <a href="{{ route('admin.bidang.index') }}" class="px-4 py-2.5 text-sm font-semibold rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-50 transition">Batal</a>
            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold rounded-lg bg-primary text-white hover:bg-primary-light transition shadow-sm">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                Simpan Bidang
            </button>
        </div>
    </form>
</div>

<div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 flex gap-3">
        <svg width="18" height="18" class="shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="#1B3A5C" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
        <div>
            <p class="text-sm font-semibold text-slate-700">Auto-generate Folder</p>
            <p class="text-xs text-slate-400 mt-0.5">Sistem akan otomatis membuat struktur folder arsip untuk bidang baru ini.</p>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 flex gap-3">
        <svg width="18" height="18" class="shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="#1B3A5C" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        <div>
            <p class="text-sm font-semibold text-slate-700">Hak Akses</p>
            <p class="text-xs text-slate-400 mt-0.5">Operator yang dipilih akan mendapatkan akses manajemen arsip untuk bidang ini.</p>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 flex gap-3">
        <svg width="18" height="18" class="shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="#1B3A5C" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        <div>
            <p class="text-sm font-semibold text-slate-700">Log Aktivitas</p>
            <p class="text-xs text-slate-400 mt-0.5">Penambahan bidang akan tercatat secara permanen di log sistem.</p>
        </div>
    </div>
</div>
@endsection
