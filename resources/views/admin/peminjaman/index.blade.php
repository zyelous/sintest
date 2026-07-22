@extends('layouts.app')
@section('title', 'Peminjaman Arsip')
@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="hover:text-primary">Dashboard</a>
    <span>/</span>
    <span class="text-slate-700 font-medium">Peminjaman Arsip</span>
@endsection

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-xl font-bold text-slate-800">Peminjaman Arsip</h1>
        <p class="text-sm text-slate-500 mt-0.5">Monitoring, persetujuan, dan pengembalian arsip fisik lintas bidang.</p>
    </div>
    <a href="{{ route('admin.peminjaman.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-lg bg-primary text-white hover:bg-primary-light transition shadow-sm">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Pinjam Arsip
    </a>
</div>

{{-- Ringkasan monitoring --}}
<div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
        <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide">Menunggu Persetujuan</p>
        <p class="text-3xl font-bold text-amber-600 mt-1">{{ number_format($totalMenunggu) }}</p>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
        <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide">Sedang Dipinjam</p>
        <p class="text-3xl font-bold text-primary mt-1">{{ number_format($totalDipinjam) }}</p>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
        <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide">Terlambat</p>
        <p class="text-3xl font-bold text-red-600 mt-1">{{ number_format($totalTerlambat) }}</p>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
        <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide">Sudah Dikembalikan</p>
        <p class="text-3xl font-bold text-emerald-600 mt-1">{{ number_format($totalDikembalikan) }}</p>
    </div>
</div>

{{-- Filter bar --}}
<div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 mb-6">
    <form method="GET" action="{{ route('admin.peminjaman.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="sm:col-span-2">
            <label class="block text-xs font-semibold text-slate-500 mb-1.5">Cari</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama peminjam, bidang, kode arsip..." class="w-full px-3.5 py-2.5 rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1.5">Bidang</label>
            <select name="bidang_id" class="w-full px-3.5 py-2.5 rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
                <option value="">Semua Bidang</option>
                @foreach($bidangList as $b)
                <option value="{{ $b->id }}" {{ request('bidang_id') == $b->id ? 'selected' : '' }}>{{ $b->nama_bidang }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1.5">Status</label>
            <select name="status" class="w-full px-3.5 py-2.5 rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
                <option value="">Semua Status</option>
                <option value="menunggu_persetujuan" {{ request('status') === 'menunggu_persetujuan' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                <option value="dipinjam" {{ request('status') === 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                <option value="dikembalikan" {{ request('status') === 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1.5">Tgl Pinjam Dari</label>
            <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}" class="w-full px-3.5 py-2.5 rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1.5">Tgl Pinjam Sampai</label>
            <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}" class="w-full px-3.5 py-2.5 rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
        </div>
        <div class="sm:col-span-2 lg:col-span-5 flex justify-end gap-2">
            @if(request()->hasAny(['search', 'status', 'tanggal_dari', 'tanggal_sampai', 'bidang_id']))
            <a href="{{ route('admin.peminjaman.index') }}" class="px-4 py-2.5 text-sm font-semibold rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-50 transition">Reset</a>
            @endif
            <button type="submit" class="px-5 py-2.5 text-sm font-semibold rounded-lg bg-primary text-white hover:bg-primary-light transition">Terapkan Filter</button>
        </div>
    </form>
</div>

{{-- Table --}}
<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-primary text-white text-xs uppercase tracking-wide">
                    <th class="px-5 py-3 text-left font-semibold">No</th>
                    <th class="px-5 py-3 text-left font-semibold">Kode Arsip</th>
                    <th class="px-5 py-3 text-left font-semibold">Peminjam</th>
                    <th class="px-5 py-3 text-left font-semibold">Bidang Peminjam</th>
                    <th class="px-5 py-3 text-left font-semibold">Tgl Pinjam</th>
                    <th class="px-5 py-3 text-left font-semibold">Rencana Kembali</th>
                    <th class="px-5 py-3 text-center font-semibold">Status</th>
                    <th class="px-5 py-3 text-center font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($peminjamanList as $i => $p)
                <tr class="hover:bg-slate-50">
                    <td class="px-5 py-3.5 text-slate-500">{{ $peminjamanList->firstItem() + $i }}</td>
                    <td class="px-5 py-3.5">
                        <span class="font-semibold text-primary">{{ $p->arsip->kode_klasifikasi ?? '-' }}</span>
                        <span class="text-slate-400">/</span>
                        <span class="text-slate-600">{{ $p->arsip->no_berkas ?? '-' }}</span>
                    </td>
                    <td class="px-5 py-3.5 font-medium text-slate-700">{{ $p->nama_peminjam }}</td>
                    <td class="px-5 py-3.5 text-slate-600">{{ $p->bidang_peminjam }}</td>
                    <td class="px-5 py-3.5 text-slate-600 whitespace-nowrap">{{ $p->tanggal_pinjam->format('d/m/Y') }}</td>
                    <td class="px-5 py-3.5 text-slate-600 whitespace-nowrap">{{ $p->tanggal_rencana_kembali?->format('d/m/Y') ?? '-' }}</td>
                    <td class="px-5 py-3.5 text-center">
                        @if($p->status === 'menunggu_persetujuan')
                            <span class="inline-block px-3 py-1.5 rounded-full text-[0.7rem] font-bold bg-amber-100 text-amber-700">MENUNGGU PERSETUJUAN</span>
                        @elseif($p->status === 'ditolak')
                            <span class="inline-block px-3 py-1.5 rounded-full text-[0.7rem] font-bold bg-slate-200 text-slate-600">DITOLAK</span>
                        @elseif($p->status === 'dikembalikan')
                            <span class="inline-block px-3 py-1.5 rounded-full text-[0.7rem] font-bold bg-sky-100 text-sky-700">DIKEMBALIKAN</span>
                        @elseif($p->terlambat)
                            <span class="inline-block px-3 py-1.5 rounded-full text-[0.7rem] font-bold bg-red-100 text-red-700">TERLAMBAT</span>
                        @else
                            <span class="inline-block px-3 py-1.5 rounded-full text-[0.7rem] font-bold bg-emerald-100 text-emerald-700">DISETUJUI</span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center justify-center gap-1.5">
                            <a href="{{ route('admin.peminjaman.show', $p) }}" title="Detail" class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            </a>

                            @if($p->status === 'menunggu_persetujuan')
                            <form method="POST" action="{{ route('admin.peminjaman.approve', $p) }}" class="inline">
                                @csrf @method('PUT')
                                <button type="submit" title="Setujui" onclick="return confirm('Setujui peminjaman ini?')" class="w-8 h-8 flex items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100 transition">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.peminjaman.reject', $p) }}" class="inline">
                                @csrf @method('PUT')
                                <button type="submit" title="Tolak" onclick="return confirm('Tolak peminjaman ini?')" class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                </button>
                            </form>
                            @endif

                            @if(in_array($p->status, ['menunggu_persetujuan', 'dipinjam']))
                            <a href="{{ route('admin.peminjaman.edit', $p) }}" title="Edit" class="w-8 h-8 flex items-center justify-center rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 transition">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </a>
                            @endif

                            <button type="button" title="Hapus" onclick="confirmDelete('{{ route('admin.peminjaman.destroy', $p) }}')" class="w-8 h-8 flex items-center justify-center rounded-lg bg-rose-50 text-rose-500 hover:bg-rose-100 transition">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="px-5 py-10 text-center text-slate-400">Tidak ada data peminjaman.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="flex items-center justify-between px-5 py-4 border-t border-slate-100">
        <p class="text-xs text-slate-400">Menampilkan {{ $peminjamanList->firstItem() ?? 0 }}–{{ $peminjamanList->lastItem() ?? 0 }} dari {{ $peminjamanList->total() }} data</p>
        {{ $peminjamanList->links('components.pagination') }}
    </div>
</div>
@endsection