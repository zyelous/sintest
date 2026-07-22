@extends('layouts.app')
@section('title', 'Edit Peminjaman')
@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="hover:text-primary">Dashboard</a>
    <span>/</span>
    <a href="{{ route('admin.peminjaman.index') }}" class="hover:text-primary">Peminjaman Arsip</a>
    <span>/</span>
    <span class="text-slate-700 font-medium">Edit</span>
@endsection

@section('content')
<div class="mb-6">
    <h1 class="text-xl font-bold text-slate-800">Edit Peminjaman</h1>
    <p class="text-sm text-slate-500 mt-0.5">Perbarui detail peminjaman arsip.</p>
</div>

<div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 max-w-3xl">
    <div class="mb-5 pb-5 border-b border-slate-100">
        <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide">Arsip yang Dipinjam</p>
        <p class="text-sm font-semibold text-primary mt-1">{{ $peminjaman->arsip->kode_klasifikasi }} / {{ $peminjaman->arsip->no_berkas }}</p>
        <p class="text-sm text-slate-600">{{ $peminjaman->arsip->uraian_berkas }}</p>
    </div>

    <form method="POST" action="{{ route('admin.peminjaman.update', $peminjaman) }}">
        @csrf @method('PUT')
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nama Peminjam <span class="text-red-500">*</span></label>
                <input type="text" name="nama_peminjam" value="{{ old('nama_peminjam', $peminjaman->nama_peminjam) }}" required class="w-full px-3.5 py-2.5 rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary @error('nama_peminjam') border-red-400 @enderror">
                @error('nama_peminjam')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Unit Kerja/Bidang Peminjam <span class="text-red-500">*</span></label>
                <input type="text" name="bidang_peminjam" value="{{ old('bidang_peminjam', $peminjaman->bidang_peminjam) }}" required class="w-full px-3.5 py-2.5 rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary @error('bidang_peminjam') border-red-400 @enderror">
                @error('bidang_peminjam')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Tanggal Peminjaman <span class="text-red-500">*</span></label>
                <input type="date" name="tanggal_pinjam" value="{{ old('tanggal_pinjam', $peminjaman->tanggal_pinjam->format('Y-m-d')) }}" required class="w-full px-3.5 py-2.5 rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary @error('tanggal_pinjam') border-red-400 @enderror">
                @error('tanggal_pinjam')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Tanggal Rencana Kembali <span class="text-red-500">*</span></label>
                <input type="date" name="tanggal_rencana_kembali" value="{{ old('tanggal_rencana_kembali', $peminjaman->tanggal_rencana_kembali?->format('Y-m-d')) }}" required class="w-full px-3.5 py-2.5 rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary @error('tanggal_rencana_kembali') border-red-400 @enderror">
                @error('tanggal_rencana_kembali')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="sm:col-span-2">
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Keterangan</label>
                <textarea name="keterangan" rows="2" class="w-full px-3.5 py-2.5 rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">{{ old('keterangan', $peminjaman->keterangan) }}</textarea>
            </div>
        </div>

        <div class="flex justify-end gap-3 mt-6 pt-5 border-t border-slate-100">
            <a href="{{ route('admin.peminjaman.index') }}" class="px-5 py-2.5 text-sm font-semibold rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-50 transition">Batal</a>
            <button type="submit" class="px-5 py-2.5 text-sm font-semibold rounded-lg bg-primary text-white hover:bg-primary-light transition">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection