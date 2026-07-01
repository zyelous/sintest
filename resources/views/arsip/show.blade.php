@extends('layouts.app')
@section('title', 'Detail Arsip')
@section('breadcrumb')<a href="{{ route('dashboard') }}">Dashboard</a> <span>/</span> <a href="{{ route('arsip.index') }}">Arsip</a> <span>/</span> <span>Detail</span>@endsection
@section('content')
<div class="page-header"><h1 class="page-title">Detail Arsip</h1><div><a href="{{ route('arsip.edit', $arsip) }}" class="btn btn-warning">Edit</a> <a href="{{ route('arsip.index') }}" class="btn btn-outline">Kembali</a></div></div>

<div class="grid-2">
    <div class="card">
        <div class="card-header"><h3>Informasi Berkas</h3></div>
        <div class="card-body">
            <div class="detail-grid">
                <div class="detail-item"><span class="detail-label">Kode Klasifikasi</span><span class="detail-value"><strong>{{ $arsip->kode_klasifikasi }}</strong></span></div>
                <div class="detail-item"><span class="detail-label">No. Berkas</span><span class="detail-value">{{ $arsip->no_berkas }}</span></div>
                <div class="detail-item full-width"><span class="detail-label">Uraian Berkas</span><span class="detail-value">{{ $arsip->uraian_berkas }}</span></div>
                <div class="detail-item"><span class="detail-label">Kurun Waktu</span><span class="detail-value">{{ $arsip->kurun_waktu ?? '-' }}</span></div>
                <div class="detail-item"><span class="detail-label">Jumlah Berkas</span><span class="detail-value">{{ $arsip->jumlah_berkas }}</span></div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h3>Klasifikasi & Status</h3></div>
        <div class="card-body">
            <div class="detail-grid">
                <div class="detail-item"><span class="detail-label">Tanggal Diarsipkan</span><span class="detail-value">{{ $arsip->tanggal_diarsipkan?->format('d F Y') }}</span></div>
                <div class="detail-item"><span class="detail-label">Umur Arsip</span><span class="detail-value"><span class="badge badge-{{ $arsip->umur_hari > 1825 ? 'danger' : ($arsip->umur_hari > 365 ? 'warning' : 'success') }}">{{ $arsip->umur_arsip }}</span></span></div>
                <div class="detail-item"><span class="detail-label">Klasifikasi Keamanan</span><span class="detail-value"><span class="badge badge-{{ $arsip->klasifikasi_keamanan === 'biasa' ? 'secondary' : 'danger' }}">{{ ucfirst(str_replace('_',' ',$arsip->klasifikasi_keamanan)) }}</span></span></div>
                <div class="detail-item"><span class="detail-label">Status Retensi</span><span class="detail-value"><span class="badge badge-{{ $arsip->status_retensi === 'aktif' ? 'success' : 'secondary' }}">{{ ucfirst($arsip->status_retensi) }}</span></span></div>
                <div class="detail-item"><span class="detail-label">Status Arsip</span><span class="detail-value"><span class="badge badge-{{ $arsip->status_arsip === 'tersedia' ? 'info' : 'warning' }}">{{ ucfirst($arsip->status_arsip) }}</span></span></div>
                <div class="detail-item"><span class="detail-label">Nasib Akhir</span><span class="detail-value">{{ $arsip->nasib_akhir ?? '-' }}</span></div>
                <div class="detail-item"><span class="detail-label">Bidang</span><span class="detail-value">{{ $arsip->bidang->nama_bidang ?? '-' }}</span></div>
                <div class="detail-item"><span class="detail-label">Dibuat Oleh</span><span class="detail-value">{{ $arsip->user->name ?? '-' }}</span></div>
                @if($arsip->file_arsip)<div class="detail-item"><span class="detail-label">File</span><span class="detail-value"><a href="{{ route('arsip.download', $arsip) }}" class="btn btn-sm btn-info">Download File</a></span></div>@endif
            </div>
        </div>
    </div>
</div>

{{-- Lokasi Penyimpanan --}}
<div class="card mt-3">
    <div class="card-header"><h3>Lokasi Penyimpanan</h3></div>
    <div class="card-body">
        <div class="detail-grid">
            <div class="detail-item"><span class="detail-label">Lokasi Simpan</span><span class="detail-value">{{ $arsip->lokasi_simpan ?? '-' }}</span></div>
            <div class="detail-item"><span class="detail-label">No. Rak</span><span class="detail-value">{{ $arsip->no_rak ?? '-' }}</span></div>
            <div class="detail-item"><span class="detail-label">No. Boks</span><span class="detail-value">{{ $arsip->no_boks ?? '-' }}</span></div>
            <div class="detail-item"><span class="detail-label">No. Folder</span><span class="detail-value">{{ $arsip->no_folder ?? '-' }}</span></div>
        </div>
    </div>
</div>

{{-- Riwayat Peminjaman --}}
<div class="card mt-3">
    <div class="card-header"><h3>Riwayat Peminjaman</h3></div>
    <div class="card-body">
        @if($arsip->peminjaman->isEmpty())
            <p class="text-secondary text-center" style="padding:16px;">Belum ada riwayat peminjaman.</p>
        @else
        <div class="table-responsive">
            <table class="table">
                <thead><tr><th>Peminjam</th><th>Bidang</th><th>Tgl Pinjam</th><th>Tgl Kembali</th><th>Status</th></tr></thead>
                <tbody>
                    @foreach($arsip->peminjaman as $p)
                    <tr>
                        <td>{{ $p->nama_peminjam }}</td>
                        <td>{{ $p->bidang_peminjam }}</td>
                        <td>{{ $p->tanggal_pinjam->format('d/m/Y') }}</td>
                        <td>{{ $p->tanggal_kembali?->format('d/m/Y') ?? '-' }}</td>
                        <td><span class="badge badge-{{ $p->status === 'dikembalikan' ? 'success' : 'warning' }}">{{ ucfirst($p->status) }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
@endsection
