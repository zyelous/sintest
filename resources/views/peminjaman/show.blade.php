@extends('layouts.app')
@section('title', 'Detail Peminjaman')
@section('breadcrumb')<a href="{{ route('dashboard') }}">Dashboard</a> <span>/</span> <a href="{{ route('peminjaman.index') }}">Peminjaman</a> <span>/</span> <span>Detail</span>@endsection
@section('content')
<div class="page-header"><h1 class="page-title">Detail Peminjaman</h1><a href="{{ route('peminjaman.index') }}" class="btn btn-outline">Kembali</a></div>
<div class="grid-2">
    <div class="card">
        <div class="card-header"><h3>Data Peminjam</h3></div>
        <div class="card-body">
            <div class="detail-grid">
                <div class="detail-item"><span class="detail-label">Nama Peminjam</span><span class="detail-value">{{ $peminjaman->nama_peminjam }}</span></div>
                <div class="detail-item"><span class="detail-label">Bidang Peminjam</span><span class="detail-value">{{ $peminjaman->bidang_peminjam }}</span></div>
                <div class="detail-item"><span class="detail-label">Tanggal Pinjam</span><span class="detail-value">{{ $peminjaman->tanggal_pinjam->format('d F Y') }}</span></div>
                <div class="detail-item"><span class="detail-label">Tanggal Kembali</span><span class="detail-value">{{ $peminjaman->tanggal_kembali?->format('d F Y') ?? '-' }}</span></div>
                <div class="detail-item"><span class="detail-label">Status</span><span class="detail-value">
                    @if($peminjaman->status === 'dikembalikan')<span class="badge badge-success">Dikembalikan</span>
                    @elseif($peminjaman->tanggal_pinjam->diffInDays(now()) > 14)<span class="badge badge-danger">Terlambat ({{ $peminjaman->tanggal_pinjam->diffInDays(now()) }} hari)</span>
                    @else<span class="badge badge-warning">Dipinjam ({{ $peminjaman->tanggal_pinjam->diffInDays(now()) }} hari)</span>@endif
                </span></div>
                @if($peminjaman->keterangan)<div class="detail-item full-width"><span class="detail-label">Keterangan</span><span class="detail-value">{{ $peminjaman->keterangan }}</span></div>@endif
                <div class="detail-item"><span class="detail-label">Dicatat Oleh</span><span class="detail-value">{{ $peminjaman->creator->name ?? '-' }}</span></div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header"><h3>Data Arsip</h3></div>
        <div class="card-body">
            <div class="detail-grid">
                <div class="detail-item"><span class="detail-label">Kode Klasifikasi</span><span class="detail-value">{{ $peminjaman->arsip->kode_klasifikasi }}</span></div>
                <div class="detail-item"><span class="detail-label">No. Berkas</span><span class="detail-value">{{ $peminjaman->arsip->no_berkas }}</span></div>
                <div class="detail-item full-width"><span class="detail-label">Uraian</span><span class="detail-value">{{ $peminjaman->arsip->uraian_berkas }}</span></div>
                <div class="detail-item"><span class="detail-label">Bidang Arsip</span><span class="detail-value">{{ $peminjaman->arsip->bidang->nama_bidang ?? '-' }}</span></div>
            </div>
        </div>
    </div>
</div>

@if($peminjaman->status === 'dipinjam')
<div class="mt-3">
    <form method="POST" action="{{ route('peminjaman.kembalikan', $peminjaman) }}">@csrf @method('PUT')
        <button type="submit" class="btn btn-success" onclick="return confirm('Konfirmasi pengembalian arsip?')">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
            Proses Pengembalian
        </button>
    </form>
</div>
@endif
@endsection
