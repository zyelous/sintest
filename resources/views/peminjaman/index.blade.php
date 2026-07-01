@extends('layouts.app')
@section('title', 'Peminjaman Arsip')
@section('breadcrumb')<a href="{{ route('dashboard') }}">Dashboard</a> <span>/</span> <span>Peminjaman Arsip</span>@endsection
@section('content')
<div class="page-header">
    <h1 class="page-title">Peminjaman Arsip</h1>
    <a href="{{ route('peminjaman.create') }}" class="btn btn-primary"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg> Pinjam Arsip</a>
</div>
<div class="card"><div class="card-body">
    <form method="GET" class="filter-bar">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama peminjam, bidang, kode arsip..." class="form-input">
        <select name="status" class="form-select" onchange="this.form.submit()"><option value="">-- Semua Status --</option><option value="dipinjam" {{ request('status') === 'dipinjam' ? 'selected' : '' }}>Dipinjam</option><option value="dikembalikan" {{ request('status') === 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option></select>
        <button type="submit" class="btn btn-primary btn-sm">Cari</button>
        @if(request()->hasAny(['search','status']))<a href="{{ route('peminjaman.index') }}" class="btn btn-outline btn-sm">Reset</a>@endif
    </form>
    <div class="table-responsive mt-2">
        <table class="table">
            <thead><tr><th>No</th><th>Kode Arsip</th><th>Peminjam</th><th>Bidang Peminjam</th><th>Tgl Pinjam</th><th>Tgl Kembali</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
                @forelse($peminjamanList as $i => $p)
                <tr>
                    <td>{{ $peminjamanList->firstItem() + $i }}</td>
                    <td><strong>{{ $p->arsip->kode_klasifikasi ?? '-' }} - {{ $p->arsip->no_berkas ?? '-' }}</strong></td>
                    <td>{{ $p->nama_peminjam }}</td>
                    <td>{{ $p->bidang_peminjam }}</td>
                    <td>{{ $p->tanggal_pinjam->format('d/m/Y') }}</td>
                    <td>{{ $p->tanggal_kembali?->format('d/m/Y') ?? '-' }}</td>
                    <td>
                        @if($p->status === 'dikembalikan')
                            <span class="badge badge-success">Dikembalikan</span>
                        @elseif($p->tanggal_pinjam->diffInDays(now()) > 14)
                            <span class="badge badge-danger">Terlambat</span>
                        @else
                            <span class="badge badge-warning">Dipinjam</span>
                        @endif
                    </td>
                    <td>
                        <div class="action-btns">
                            <a href="{{ route('peminjaman.show', $p) }}" class="btn btn-sm btn-info">Detail</a>
                            @if($p->status === 'dipinjam')
                            <form method="POST" action="{{ route('peminjaman.kembalikan', $p) }}" style="display:inline;">@csrf @method('PUT')<button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Konfirmasi pengembalian arsip?')">Kembalikan</button></form>
                            @else
                            <button class="btn btn-sm btn-danger" onclick="confirmDelete('{{ route('peminjaman.destroy', $p) }}')">Hapus</button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-secondary">Tidak ada data peminjaman.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-info">Menampilkan {{ $peminjamanList->firstItem() ?? 0 }} - {{ $peminjamanList->lastItem() ?? 0 }} dari {{ $peminjamanList->total() }} data</div>
    {{ $peminjamanList->links('components.pagination') }}
</div></div>
@endsection
