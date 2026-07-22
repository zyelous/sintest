@extends('layouts.app')
@section('title', 'Detail Peminjaman')
@section('breadcrumb')
    <a href="{{ route('operator.dashboard') }}" class="hover:text-primary">Dashboard</a>
    <span>/</span>
    <a href="{{ route('operator.peminjaman.index') }}" class="hover:text-primary">Peminjaman Arsip</a>
    <span>/</span>
    <span class="text-slate-700 font-medium">Detail</span>
@endsection

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-xl font-bold text-slate-800">Detail Peminjaman</h1>
    <a href="{{ route('operator.peminjaman.index') }}" class="px-4 py-2.5 text-sm font-semibold rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-50 transition">Kembali</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100">
            <h3 class="font-semibold text-slate-800">Data Peminjam</h3>
        </div>
        <div class="p-5 space-y-4">
            <div>
                <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide">Nama Peminjam</p>
                <p class="text-sm text-slate-700 mt-0.5">{{ $peminjaman->nama_peminjam }}</p>
            </div>
            <div>
                <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide">Unit Kerja/Bidang Peminjam</p>
                <p class="text-sm text-slate-700 mt-0.5">{{ $peminjaman->bidang_peminjam }}</p>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide">Tanggal Pinjam</p>
                    <p class="text-sm text-slate-700 mt-0.5">{{ $peminjaman->tanggal_pinjam->format('d F Y') }}</p>
                </div>
                <div>
                    <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide">Rencana Kembali</p>
                    <p class="text-sm text-slate-700 mt-0.5">{{ $peminjaman->tanggal_rencana_kembali?->format('d F Y') ?? '-' }}</p>
                </div>
            </div>
            <div>
                <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide">Tanggal Kembali (Aktual)</p>
                <p class="text-sm text-slate-700 mt-0.5">{{ $peminjaman->tanggal_kembali?->format('d F Y') ?? '-' }}</p>
            </div>
            <div>
                <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide">Status</p>
                <p class="mt-1">
                    @if($peminjaman->status === 'menunggu_persetujuan')
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700">MENUNGGU PERSETUJUAN</span>
                    @elseif($peminjaman->status === 'ditolak')
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-slate-200 text-slate-600">DITOLAK</span>
                    @elseif($peminjaman->status === 'dikembalikan')
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-sky-100 text-sky-700">DIKEMBALIKAN</span>
                    @elseif($peminjaman->terlambat)
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">TERLAMBAT ({{ $peminjaman->tanggal_rencana_kembali->diffInDays(now()) }} hari dari rencana)</span>
                    @else
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">DISETUJUI ({{ $peminjaman->tanggal_pinjam->diffInDays(now()) }} hari)</span>
                    @endif
                </p>
            </div>
            @if($peminjaman->keterangan)
            <div>
                <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide">Keterangan</p>
                <p class="text-sm text-slate-700 mt-0.5">{{ $peminjaman->keterangan }}</p>
            </div>
            @endif
            <div>
                <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide">Diajukan Oleh</p>
                <p class="text-sm text-slate-700 mt-0.5">{{ $peminjaman->creator->name ?? '-' }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100">
            <h3 class="font-semibold text-slate-800">Data Arsip</h3>
        </div>
        <div class="p-5 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide">Kode Klasifikasi</p>
                    <p class="text-sm font-semibold text-primary mt-0.5">{{ $peminjaman->arsip->kode_klasifikasi }}</p>
                </div>
                <div>
                    <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide">No. Berkas</p>
                    <p class="text-sm text-slate-700 mt-0.5">{{ $peminjaman->arsip->no_berkas }}</p>
                </div>
            </div>
            <div>
                <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide">Uraian</p>
                <p class="text-sm text-slate-700 mt-0.5">{{ $peminjaman->arsip->uraian_berkas }}</p>
            </div>
            <div>
                <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide">Bidang Arsip</p>
                <p class="text-sm text-slate-700 mt-0.5">{{ $peminjaman->arsip->bidang->nama_bidang ?? '-' }}</p>
            </div>
        </div>
    </div>
</div>

@if($peminjaman->status === 'menunggu_persetujuan')
<div class="mt-6 flex gap-3">
    <form method="POST" action="{{ route('operator.peminjaman.approve', $peminjaman) }}">
        @csrf @method('PUT')
        <button type="submit" onclick="return confirm('Setujui peminjaman ini?')" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold rounded-lg bg-emerald-500 text-white hover:bg-emerald-600 transition shadow-sm">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            Setujui Peminjaman
        </button>
    </form>
    <form method="POST" action="{{ route('operator.peminjaman.reject', $peminjaman) }}">
        @csrf @method('PUT')
        <button type="submit" onclick="return confirm('Tolak peminjaman ini?')" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold rounded-lg border border-red-300 text-red-600 hover:bg-red-50 transition">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            Tolak
        </button>
    </form>
</div>
@elseif($peminjaman->status === 'dipinjam')
<div class="mt-6">
    <form method="POST" action="{{ route('operator.peminjaman.kembalikan', $peminjaman) }}">
        @csrf @method('PUT')
        <button type="submit" onclick="return confirm('Konfirmasi pengembalian arsip?')" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold rounded-lg bg-emerald-500 text-white hover:bg-emerald-600 transition shadow-sm">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
            Proses Pengembalian
        </button>
    </form>
</div>
@endif
@endsection