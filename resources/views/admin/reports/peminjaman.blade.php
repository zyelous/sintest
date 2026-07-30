@extends('layouts.app')
@section('title', 'Laporan Peminjaman Arsip')
@section('content')

<div class="flex items-center justify-between mb-6 flex-wrap gap-3">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Laporan Peminjaman Arsip</h1>
        <p class="text-xs text-slate-500 mt-1">Rekapitulasi riwayat transaksi peminjaman & pengembalian arsip.</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route(auth()->user()->isAdmin() ? 'admin.laporan.peminjaman.pdf' : 'operator.laporan.peminjaman.pdf', request()->query()) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-lg border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 transition shadow-sm">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            Export PDF
        </a>
        <a href="{{ route(auth()->user()->isAdmin() ? 'admin.laporan.peminjaman.excel' : 'operator.laporan.peminjaman.excel', request()->query()) }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 transition shadow-sm">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="9" y1="3" x2="9" y2="21"/></svg>
            Export Excel
        </a>
    </div>
</div>

<div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 mb-6">
    <form method="GET" action="{{ route(auth()->user()->isAdmin() ? 'admin.laporan.peminjaman' : 'operator.laporan.peminjaman') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
        <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1">Pencarian</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Peminjam / No Berkas..." class="w-full px-3.5 py-2 rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
        </div>
        @if(auth()->user()->isAdmin())
        <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1">Bidang Pemilik Arsip</label>
            <select name="bidang_id" class="w-full px-3.5 py-2 rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
                <option value="">Semua Bidang</option>
                @foreach($bidangList as $b)
                <option value="{{ $b->id }}" {{ request('bidang_id') == $b->id ? 'selected' : '' }}>{{ $b->nama_bidang }}</option>
                @endforeach
            </select>
        </div>
        @endif
        <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1">Status Peminjaman</label>
            <select name="status" class="w-full px-3.5 py-2 rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
                <option value="">Semua Status</option>
                <option value="menunggu_persetujuan" {{ request('status') === 'menunggu_persetujuan' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                <option value="dipinjam" {{ request('status') === 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                <option value="dikembalikan" {{ request('status') === 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="flex-1 inline-flex items-center justify-center gap-1.5 px-4 py-2 text-sm font-semibold rounded-lg bg-primary text-white hover:bg-primary-light transition">
                Filter
            </button>
            <a href="{{ route(auth()->user()->isAdmin() ? 'admin.laporan.peminjaman' : 'operator.laporan.peminjaman') }}" class="px-3 py-2 rounded-lg border border-slate-300 text-slate-500 hover:bg-slate-50 transition" title="Reset">
                Reset
            </a>
        </div>
    </form>
</div>

<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 text-slate-600 text-xs uppercase tracking-wide border-b border-slate-200">
                    <th class="px-4 py-3 text-left font-semibold">No. Berkas</th>
                    <th class="px-4 py-3 text-left font-semibold">Judul Arsip</th>
                    <th class="px-4 py-3 text-left font-semibold">Nama Peminjam</th>
                    <th class="px-4 py-3 text-left font-semibold">Bidang Peminjam</th>
                    <th class="px-4 py-3 text-left font-semibold">Tgl Pinjam</th>
                    <th class="px-4 py-3 text-left font-semibold">Batas Kembali</th>
                    <th class="px-4 py-3 text-center font-semibold">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($peminjamanList as $item)
                @php
                    $isOverdue = $item->status === 'dipinjam' && $item->tanggal_rencana_kembali && $item->tanggal_rencana_kembali->isPast();
                @endphp
                <tr class="hover:bg-slate-50 transition {{ $isOverdue ? 'bg-red-50/40' : '' }}">
                    <td class="px-4 py-3 font-semibold text-xs text-primary whitespace-nowrap">{{ $item->arsip?->no_berkas ?? '-' }}</td>
                    <td class="px-4 py-3">
                        <p class="font-semibold text-slate-800 line-clamp-1">{{ $item->arsip?->uraian_berkas ?? '-' }}</p>
                    </td>
                    <td class="px-4 py-3 font-medium text-slate-800 whitespace-nowrap">{{ $item->nama_peminjam }}</td>
                    <td class="px-4 py-3 text-slate-600 whitespace-nowrap">{{ $item->bidang_peminjam }}</td>
                    <td class="px-4 py-3 text-slate-600 whitespace-nowrap">{{ $item->tanggal_pinjam?->format('d/m/Y') }}</td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <span class="{{ $isOverdue ? 'text-red-600 font-bold' : 'text-slate-600' }}">
                            {{ $item->tanggal_rencana_kembali?->format('d/m/Y') ?? '-' }}
                        </span>
                        @if($isOverdue)
                            <span class="inline-block ml-1 px-1.5 py-0.5 rounded text-[0.6rem] font-extrabold bg-red-100 text-red-700">TERLAMBAT</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center whitespace-nowrap">
                        @php
                            $stColor = match($item->status) {
                                'dipinjam' => 'bg-amber-100 text-amber-800',
                                'dikembalikan' => 'bg-emerald-100 text-emerald-800',
                                'ditolak' => 'bg-red-100 text-red-800',
                                default => 'bg-blue-100 text-blue-800',
                            };
                        @endphp
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $stColor }}">
                            {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-slate-400">Tidak ada data peminjaman yang sesuai filter.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($peminjamanList->hasPages())
    <div class="px-5 py-3 border-t border-slate-100">
        {{ $peminjamanList->links() }}
    </div>
    @endif
</div>

@endsection
