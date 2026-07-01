@extends('layouts.app')
@section('title', 'Detail Surat Masuk')
@section('breadcrumb')<a href="{{ route('dashboard') }}">Dashboard</a> <span>/</span> <a href="{{ route('surat-masuk.index') }}">Surat Masuk</a> <span>/</span> <span>Detail</span>@endsection
@section('content')
<div class="page-header"><h1 class="page-title">Detail Surat Masuk</h1><div><a href="{{ route('surat-masuk.edit', $surat_masuk) }}" class="btn btn-warning">Edit</a> <a href="{{ route('surat-masuk.index') }}" class="btn btn-outline">Kembali</a></div></div>
<div class="card"><div class="card-body">
    <div class="detail-grid">
        <div class="detail-item"><span class="detail-label">Nomor Surat</span><span class="detail-value">{{ $surat_masuk->nomor_surat }}</span></div>
        <div class="detail-item"><span class="detail-label">Tanggal Surat</span><span class="detail-value">{{ $surat_masuk->tanggal_surat->format('d F Y') }}</span></div>
        <div class="detail-item"><span class="detail-label">Tanggal Diterima</span><span class="detail-value">{{ $surat_masuk->tanggal_diterima->format('d F Y') }}</span></div>
        <div class="detail-item"><span class="detail-label">Pengirim</span><span class="detail-value">{{ $surat_masuk->pengirim }}</span></div>
        <div class="detail-item full-width"><span class="detail-label">Perihal</span><span class="detail-value">{{ $surat_masuk->perihal }}</span></div>
        <div class="detail-item"><span class="detail-label">Sifat Surat</span><span class="detail-value"><span class="badge badge-{{ $surat_masuk->sifat_surat === 'biasa' ? 'secondary' : 'warning' }}">{{ ucfirst(str_replace('_',' ',$surat_masuk->sifat_surat)) }}</span></span></div>
        <div class="detail-item"><span class="detail-label">Status</span><span class="detail-value"><span class="badge badge-{{ $surat_masuk->status === 'diteruskan' ? 'info' : 'success' }}">{{ ucfirst($surat_masuk->status) }}</span></span></div>
        <div class="detail-item"><span class="detail-label">Bidang Tujuan</span><span class="detail-value">{{ $surat_masuk->bidang->nama_bidang ?? '-' }}</span></div>
        <div class="detail-item"><span class="detail-label">Dibuat Oleh</span><span class="detail-value">{{ $surat_masuk->creator->name ?? '-' }}</span></div>
        @if($surat_masuk->catatan)<div class="detail-item full-width"><span class="detail-label">Catatan</span><span class="detail-value">{{ $surat_masuk->catatan }}</span></div>@endif
        @if($surat_masuk->lampiran)<div class="detail-item"><span class="detail-label">Lampiran</span><span class="detail-value"><a href="{{ route('surat-masuk.download', $surat_masuk) }}" class="btn btn-sm btn-info">Download {{ $surat_masuk->lampiran_nama }}</a></span></div>@endif
    </div>
</div></div>
@endsection
