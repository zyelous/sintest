@extends('layouts.app')
@section('title', 'Detail Peminjaman')

@section('breadcrumb')
<a href="{{ route('dashboard') }}" class="hover:text-primary transition">Dashboard</a>
<span class="text-slate-300">/</span>
<a href="{{ route('peminjaman.index') }}" class="hover:text-primary transition">Peminjaman</a>
<span class="text-slate-300">/</span>
<span class="text-slate-600 font-medium">Detail Peminjaman</span>
@endsection

@section('content')
<div class="flex items-start justify-between mb-6 flex-wrap gap-3">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Detail Transaksi Peminjaman</h1>
        <p class="text-sm text-slate-500 mt-1">Detail data peminjaman berkas arsip fisikal unit kerja.</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('peminjaman.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-lg border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 transition shadow-sm">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            Kembali
        </a>
        @if($peminjaman->status === 'dipinjam')
        <form method="POST" action="{{ route('peminjaman.kembalikan', $peminjaman) }}" class="inline">
            @csrf @method('PUT')
            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 transition shadow-sm" onclick="return confirm('Konfirmasi pengembalian arsip ini?')">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                Proses Pengembalian
            </button>
        </form>
        @endif
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Left Section --}}
    <div class="lg:col-span-2 space-y-6">
        {{-- Peminjam Info --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center gap-2 bg-slate-50/50">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#1B3A5C" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                <h3 class="font-semibold text-slate-800 text-sm uppercase tracking-wide">Data Peminjam</h3>
            </div>
            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide">Nama Peminjam</p>
                    <p class="font-bold text-slate-850 text-base mt-1">{{ $peminjaman->nama_peminjam }}</p>
                </div>
                <div>
                    <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide">Instansi / Bidang Peminjam</p>
                    <p class="font-semibold text-slate-800 text-base mt-1">{{ $peminjaman->bidang_peminjam }}</p>
                </div>
                <div>
                    <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide">Tanggal Pinjam</p>
                    <p class="font-medium text-slate-850 mt-1">{{ $peminjaman->tanggal_pinjam->translatedFormat('d F Y') }}</p>
                </div>
                <div>
                    <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide">Tanggal Pengembalian</p>
                    <p class="font-medium text-slate-850 mt-1">
                        @if($peminjaman->tanggal_kembali)
                            {{ $peminjaman->tanggal_kembali->translatedFormat('d F Y') }}
                        @else
                            <span class="text-slate-400 italic">Belum dikembalikan</span>
                        @endif
                    </p>
                </div>
                @if($peminjaman->keterangan)
                <div class="sm:col-span-2">
                    <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide">Keterangan / Keperluan</p>
                    <p class="text-sm text-slate-700 mt-1 bg-slate-50 p-3 rounded-lg border border-slate-200 leading-relaxed">{{ $peminjaman->keterangan }}</p>
                </div>
                @endif
                <div>
                    <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide">Dicatat Oleh</p>
                    <p class="font-medium text-slate-800 mt-1">{{ $peminjaman->creator->name ?? '-' }}</p>
                </div>
            </div>
        </div>

        {{-- Arsip Info --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center gap-2 bg-slate-50/50">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#1B3A5C" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                <h3 class="font-semibold text-slate-800 text-sm uppercase tracking-wide">Data Berkas Arsip</h3>
            </div>
            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="sm:col-span-2">
                    <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide">Uraian Berkas</p>
                    <p class="font-bold text-primary text-base mt-1">{{ $peminjaman->arsip->uraian_berkas }}</p>
                </div>
                <div>
                    <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide">Nomor Berkas</p>
                    <p class="font-semibold text-slate-800 mt-1">{{ $peminjaman->arsip->no_berkas }}</p>
                </div>
                <div>
                    <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide">Kode Klasifikasi</p>
                    <span class="inline-block mt-1 px-2.5 py-0.5 rounded text-[0.7rem] font-bold bg-slate-100 text-slate-600 uppercase">{{ $peminjaman->arsip->kode_klasifikasi }}</span>
                </div>
                <div>
                    <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide">Bidang Pemilik</p>
                    <p class="font-medium text-slate-800 mt-1">{{ $peminjaman->arsip->bidang->nama_bidang ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide">Lokasi Penyimpanan Fisik</p>
                    <p class="font-medium text-slate-800 mt-1">Rak-{{ $peminjaman->arsip->no_rak ?: '-' }} / Boks-{{ $peminjaman->arsip->no_boks ?: '-' }}</p>
                </div>
                <div class="sm:col-span-2 pt-2.5 border-t border-slate-100 flex items-center justify-end">
                    <a href="{{ route('arsip.show', $peminjaman->arsip_id) }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-primary hover:underline">
                        Lihat Detail Berkas Lengkap
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Right Section (Status Card) --}}
    <div class="space-y-6">
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 text-center">
            @php
                $isReturned = $peminjaman->status === 'dikembalikan';
                $isLate = !$isReturned && $peminjaman->tanggal_pinjam->diffInDays(now()) > 14;
                $days = $peminjaman->tanggal_pinjam->diffInDays(now());
            @endphp

            @if($isReturned)
                <div class="w-16 h-16 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-4 border border-emerald-100 shadow-inner">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                </div>
                <h4 class="text-lg font-bold text-slate-800">Sudah Dikembalikan</h4>
                <p class="text-xs text-slate-400 mt-1">Status arsip saat ini tersedia dan tersimpan di lokasi penyimpanan.</p>
            @elseif($isLate)
                <div class="w-16 h-16 rounded-full bg-red-50 text-red-600 flex items-center justify-center mx-auto mb-4 border border-red-100 shadow-inner animate-pulse">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                </div>
                <h4 class="text-lg font-bold text-red-600">Peminjaman Terlambat</h4>
                <p class="text-sm font-semibold text-slate-800 mt-1">Terlambat: {{ $days }} Hari</p>
                <p class="text-xs text-slate-400 mt-2">Batas waktu peminjaman 14 hari terlampaui. Segera hubungi peminjam.</p>
            @else
                <div class="w-16 h-16 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center mx-auto mb-4 border border-amber-100 shadow-inner">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
                <h4 class="text-lg font-bold text-amber-600">Sedang Dipinjam</h4>
                <p class="text-sm font-semibold text-slate-800 mt-1">Durasi Peminjaman: {{ $days }} Hari</p>
                <p class="text-xs text-slate-400 mt-2">Arsip dalam kondisi dipinjam dan batas waktu pengembalian adalah 14 hari.</p>
            @endif

            @if($peminjaman->status === 'dipinjam')
            <div class="mt-5 pt-5 border-t border-slate-100">
                <form method="POST" action="{{ route('peminjaman.kembalikan', $peminjaman) }}" class="w-full">
                    @csrf @method('PUT')
                    <button type="submit" class="w-full py-2.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 transition text-white font-semibold text-sm shadow-sm flex items-center justify-center gap-1.5" onclick="return confirm('Konfirmasi pengembalian arsip ini?')">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                        Proses Pengembalian
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
