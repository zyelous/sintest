@extends('layouts.app')
@section('title', 'Detail Surat Keluar')
@section('breadcrumb')<a href="{{ route('dashboard') }}">Dashboard</a> <span>/</span> <a href="{{ route('surat-keluar.index') }}">Surat Keluar</a> <span>/</span> <span>Detail</span>@endsection
@section('content')
<div class="page-header"><h1 class="page-title">Detail Surat Keluar</h1><div><a href="{{ route('surat-keluar.edit', $surat_keluar) }}" class="btn btn-warning">Edit</a> <a href="{{ route('surat-keluar.index') }}" class="btn btn-outline">Kembali</a></div></div>
<div class="card"><div class="card-body">
    <div class="detail-grid">
        <div class="detail-item"><span class="detail-label">Nomor Surat</span><span class="detail-value">{{ $surat_keluar->nomor_surat }}</span></div>
        <div class="detail-item"><span class="detail-label">Tanggal Surat</span><span class="detail-value">{{ $surat_keluar->tanggal_surat->format('d F Y') }}</span></div>
        <div class="detail-item"><span class="detail-label">Tujuan</span><span class="detail-value">{{ $surat_keluar->tujuan }}</span></div>
        <div class="detail-item"><span class="detail-label">Sifat Surat</span><span class="detail-value"><span class="badge badge-{{ $surat_keluar->sifat_surat === 'biasa' ? 'secondary' : 'warning' }}">{{ ucfirst(str_replace('_',' ',$surat_keluar->sifat_surat)) }}</span></span></div>
        <div class="detail-item full-width"><span class="detail-label">Perihal</span><span class="detail-value">{{ $surat_keluar->perihal }}</span></div>
        <div class="detail-item"><span class="detail-label">Bidang Pembuat</span><span class="detail-value">{{ $surat_keluar->bidang->nama_bidang ?? '-' }}</span></div>
        <div class="detail-item"><span class="detail-label">Dibuat Oleh</span><span class="detail-value">{{ $surat_keluar->creator->name ?? '-' }}</span></div>
        @if($surat_keluar->catatan)<div class="detail-item full-width"><span class="detail-label">Catatan</span><span class="detail-value">{{ $surat_keluar->catatan }}</span></div>@endif
        @if($surat_keluar->lampiran)<div class="detail-item"><span class="detail-label">Lampiran</span><span class="detail-value"><a href="{{ route('surat-keluar.download', $surat_keluar) }}" class="btn btn-sm btn-info">Download {{ $surat_keluar->lampiran_nama }}</a></span></div>@endif
    </div>
</div></div>
@endsection
