@extends('layouts.app')
@section('title', 'Pencatatan Peminjaman Arsip')

@section('breadcrumb')
<a href="{{ route('dashboard') }}" class="hover:text-primary transition">Dashboard</a>
<span class="text-slate-300">/</span>
<a href="{{ route('peminjaman.index') }}" class="hover:text-primary transition">Peminjaman</a>
<span class="text-slate-300">/</span>
<span class="text-slate-600 font-medium">Pinjam</span>
@endsection

@section('content')
<p class="text-xs font-semibold text-primary uppercase tracking-wide mb-1">Transaksi Kearsipan</p>
<h1 class="text-2xl font-bold text-slate-800 mb-1">Catat Peminjaman Baru</h1>
<p class="text-sm text-slate-500 mb-6">Silakan pilih berkas arsip yang akan dipinjam serta lengkapi data peminjam secara lengkap.</p>

<form method="POST" action="{{ route('peminjaman.store') }}">
    @csrf
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Form Fields --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Arsip Selection --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                <div class="flex items-center gap-2 mb-5">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#1B3A5C" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    <h3 class="font-semibold text-slate-800">Berkas Arsip</h3>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Pilih Arsip yang Tersedia <span class="text-red-500">*</span></label>
                    <select name="arsip_id" required class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary @error('arsip_id') border-red-400 @enderror">
                        <option value="">-- Pilih Berkas Arsip --</option>
                        @foreach($arsipTersedia as $a)
                        <option value="{{ $a->id }}" {{ old('arsip_id') == $a->id ? 'selected' : '' }}>
                            [{{ $a->kode_klasifikasi }}] {{ $a->no_berkas }} - {{ Str::limit($a->uraian_berkas, 80) }}
                        </option>
                        @endforeach
                    </select>
                    @error('arsip_id')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    @if($arsipTersedia->isEmpty())
                        <div class="mt-2.5 p-3 rounded-lg bg-amber-50 text-amber-700 text-xs flex items-center gap-2">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            Tidak ada arsip yang saat ini tersedia/status "tersedia" untuk dipinjam di bidang Anda.
                        </div>
                    @endif
                </div>
            </div>

            {{-- Borrower Info --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                <div class="flex items-center gap-2 mb-5">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#1B3A5C" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    <h3 class="font-semibold text-slate-800">Identitas Peminjam</h3>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nama Peminjam <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_peminjam" value="{{ old('nama_peminjam') }}" required placeholder="Nama Lengkap Peminjam" class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary @error('nama_peminjam') border-red-400 @enderror">
                        @error('nama_peminjam')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Instansi/Bidang Peminjam <span class="text-red-500">*</span></label>
                        <input type="text" name="bidang_peminjam" value="{{ old('bidang_peminjam') }}" required placeholder="Contoh: Bidang Infrastruktur / Dinas X" class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary @error('bidang_peminjam') border-red-400 @enderror">
                        @error('bidang_peminjam')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Keterangan / Keperluan Peminjaman</label>
                        <textarea name="keterangan" rows="3" placeholder="Tuliskan alasan peminjaman atau keperluan penggunaan arsip..." class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">{{ old('keterangan') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Side Sidebar panel --}}
        <div class="space-y-6">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                <div class="flex items-center gap-2 mb-4">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#1B3A5C" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    <h3 class="font-semibold text-slate-800">Tanggal Transaksi</h3>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Tanggal Peminjaman <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_pinjam" value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" required class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
                </div>
                <div class="mt-4 p-3.5 bg-slate-50 border border-slate-200 rounded-lg text-slate-500 text-xs space-y-2 leading-relaxed">
                    <p class="font-semibold text-slate-700">Ketentuan Peminjaman:</p>
                    <ul class="list-disc list-inside space-y-1">
                        <li>Batas peminjaman maksimal adalah 14 hari kerja.</li>
                        <li>Peminjam berkewajiban merawat fisik arsip agar tidak rusak/hilang.</li>
                        <li>Pastikan status arsip diubah kembali saat pengembalian diproses.</li>
                    </ul>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('peminjaman.index') }}" class="w-1/2 text-center py-2.5 rounded-lg border border-slate-300 text-slate-700 font-semibold text-sm bg-white hover:bg-slate-50 transition shadow-sm">
                    Batal
                </a>
                <button type="submit" class="w-1/2 py-2.5 rounded-lg bg-primary text-white hover:bg-primary-light transition font-semibold text-sm shadow-sm flex items-center justify-center gap-1.5">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                    Simpan
                </button>
            </div>
        </div>
    </div>
</form>
@endsection
