@extends('layouts.app')
@section('title', 'Surat Masuk')
@section('breadcrumb')<a href="{{ route('dashboard') }}">Dashboard</a> <span>/</span> <span>Surat Masuk</span>@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title">Surat Masuk</h1>
    <a href="{{ route('surat-masuk.create') }}" class="btn btn-primary">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Tambah Surat Masuk
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" class="filter-bar">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor, pengirim, perihal..." class="form-input">
            @if(auth()->user()->isAdmin())
            <select name="bidang_id" class="form-select" onchange="this.form.submit()">
                <option value="">-- Semua Bidang --</option>
                @foreach($bidangList as $b)
                <option value="{{ $b->id }}" {{ request('bidang_id') == $b->id ? 'selected' : '' }}>{{ $b->nama_bidang }}</option>
                @endforeach
            </select>
            @endif
            <select name="status" class="form-select" onchange="this.form.submit()">
                <option value="">-- Semua Status --</option>
                <option value="diteruskan" {{ request('status') === 'diteruskan' ? 'selected' : '' }}>Diteruskan</option>
                <option value="diarsipkan" {{ request('status') === 'diarsipkan' ? 'selected' : '' }}>Diarsipkan</option>
            </select>
            <select name="sifat_surat" class="form-select" onchange="this.form.submit()">
                <option value="">-- Sifat --</option>
                <option value="biasa" {{ request('sifat_surat') === 'biasa' ? 'selected' : '' }}>Biasa</option>
                <option value="segera" {{ request('sifat_surat') === 'segera' ? 'selected' : '' }}>Segera</option>
                <option value="sangat_segera" {{ request('sifat_surat') === 'sangat_segera' ? 'selected' : '' }}>Sangat Segera</option>
                <option value="rahasia" {{ request('sifat_surat') === 'rahasia' ? 'selected' : '' }}>Rahasia</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm">Cari</button>
            @if(request()->hasAny(['search','bidang_id','status','sifat_surat']))
            <a href="{{ route('surat-masuk.index') }}" class="btn btn-outline btn-sm">Reset</a>
            @endif
        </form>

        <div class="table-responsive mt-2">
            <table class="table">
                <thead>
                    <tr><th>No</th><th>Nomor Surat</th><th>Tanggal</th><th>Pengirim</th><th>Perihal</th><th>Sifat</th><th>Status</th><th>Bidang</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($suratMasuk as $i => $sm)
                    <tr>
                        <td>{{ $suratMasuk->firstItem() + $i }}</td>
                        <td><strong>{{ $sm->nomor_surat }}</strong></td>
                        <td>{{ $sm->tanggal_surat->format('d/m/Y') }}</td>
                        <td>{{ $sm->pengirim }}</td>
                        <td>{{ Str::limit($sm->perihal, 40) }}</td>
                        <td>
                            @php $sifatColors = ['biasa'=>'secondary','segera'=>'warning','sangat_segera'=>'danger','rahasia'=>'danger']; @endphp
                            <span class="badge badge-{{ $sifatColors[$sm->sifat_surat] ?? 'secondary' }}">{{ ucfirst(str_replace('_',' ',$sm->sifat_surat)) }}</span>
                        </td>
                        <td><span class="badge badge-{{ $sm->status === 'diteruskan' ? 'info' : 'success' }}">{{ ucfirst($sm->status) }}</span></td>
                        <td>{{ $sm->bidang->nama_bidang ?? '-' }}</td>
                        <td>
                            <div class="action-btns">
                                <a href="{{ route('surat-masuk.show', $sm) }}" class="btn btn-sm btn-info">Lihat</a>
                                <a href="{{ route('surat-masuk.edit', $sm) }}" class="btn btn-sm btn-warning">Edit</a>
                                <button class="btn btn-sm btn-danger" onclick="confirmDelete('{{ route('surat-masuk.destroy', $sm) }}')">Hapus</button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="text-center text-secondary">Tidak ada data surat masuk.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination-info">Menampilkan {{ $suratMasuk->firstItem() ?? 0 }} - {{ $suratMasuk->lastItem() ?? 0 }} dari {{ $suratMasuk->total() }} data</div>
        {{ $suratMasuk->links('components.pagination') }}
    </div>
</div>
@endsection
