@extends('layouts.app')
@section('title', 'Surat Keluar')
@section('breadcrumb')<a href="{{ route('dashboard') }}">Dashboard</a> <span>/</span> <span>Surat Keluar</span>@endsection
@section('content')
<div class="page-header">
    <h1 class="page-title">Surat Keluar</h1>
    <a href="{{ route('surat-keluar.create') }}" class="btn btn-primary"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg> Tambah Surat Keluar</a>
</div>
<div class="card"><div class="card-body">
    <form method="GET" class="filter-bar">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor, tujuan, perihal..." class="form-input">
        @if(auth()->user()->isAdmin())<select name="bidang_id" class="form-select" onchange="this.form.submit()"><option value="">-- Semua Bidang --</option>@foreach($bidangList as $b)<option value="{{ $b->id }}" {{ request('bidang_id') == $b->id ? 'selected' : '' }}>{{ $b->nama_bidang }}</option>@endforeach</select>@endif
        <select name="sifat_surat" class="form-select" onchange="this.form.submit()"><option value="">-- Sifat --</option>@foreach(['biasa','segera','sangat_segera','rahasia'] as $s)<option value="{{ $s }}" {{ request('sifat_surat') === $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>@endforeach</select>
        <button type="submit" class="btn btn-primary btn-sm">Cari</button>
        @if(request()->hasAny(['search','bidang_id','sifat_surat']))<a href="{{ route('surat-keluar.index') }}" class="btn btn-outline btn-sm">Reset</a>@endif
    </form>
    <div class="table-responsive mt-2">
        <table class="table">
            <thead><tr><th>No</th><th>Nomor Surat</th><th>Tanggal</th><th>Tujuan</th><th>Perihal</th><th>Sifat</th><th>Bidang</th><th>Aksi</th></tr></thead>
            <tbody>
                @forelse($suratKeluar as $i => $sk)
                <tr>
                    <td>{{ $suratKeluar->firstItem() + $i }}</td>
                    <td><strong>{{ $sk->nomor_surat }}</strong></td>
                    <td>{{ $sk->tanggal_surat->format('d/m/Y') }}</td>
                    <td>{{ $sk->tujuan }}</td>
                    <td>{{ Str::limit($sk->perihal, 40) }}</td>
                    <td>@php $sc = ['biasa'=>'secondary','segera'=>'warning','sangat_segera'=>'danger','rahasia'=>'danger']; @endphp<span class="badge badge-{{ $sc[$sk->sifat_surat] ?? 'secondary' }}">{{ ucfirst(str_replace('_',' ',$sk->sifat_surat)) }}</span></td>
                    <td>{{ $sk->bidang->nama_bidang ?? '-' }}</td>
                    <td><div class="action-btns"><a href="{{ route('surat-keluar.show', $sk) }}" class="btn btn-sm btn-info">Lihat</a><a href="{{ route('surat-keluar.edit', $sk) }}" class="btn btn-sm btn-warning">Edit</a><button class="btn btn-sm btn-danger" onclick="confirmDelete('{{ route('surat-keluar.destroy', $sk) }}')">Hapus</button></div></td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-secondary">Tidak ada data surat keluar.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-info">Menampilkan {{ $suratKeluar->firstItem() ?? 0 }} - {{ $suratKeluar->lastItem() ?? 0 }} dari {{ $suratKeluar->total() }} data</div>
    {{ $suratKeluar->links('components.pagination') }}
</div></div>
@endsection
