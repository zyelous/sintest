@extends('layouts.app')
@section('title', 'Data Arsip')
@section('breadcrumb')<a href="{{ route('dashboard') }}">Dashboard</a> <span>/</span> <span>Data Arsip</span>@endsection

@section('content')
<div class="flex items-center justify-between mb-6 flex-wrap gap-3">
    <h1 class="text-2xl font-bold text-slate-800">Data Arsip</h1>
    <div class="flex items-center gap-2">
        <a href="{{ route('report.arsip.excel') }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-semibold rounded-lg transition bg-gradient-to-r from-emerald-500 to-emerald-600 text-white shadow-sm hover:-translate-y-0.5">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            Export Excel
        </a>
        <a href="{{ route('report.arsip.pdf') }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg transition bg-gradient-to-r from-red-500 to-red-600 text-white shadow-sm hover:-translate-y-0.5">PDF</a>
        <a href="{{ route('arsip.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-semibold rounded-lg transition bg-gradient-to-r from-primary to-primary-light text-white shadow-sm hover:-translate-y-0.5">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Tambah Arsip
        </a>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="p-6">
        {{-- Filter Bar --}}
        <form method="GET" class="flex flex-wrap gap-3 items-center pb-4 border-b border-slate-100">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode, nomor, uraian..." class="w-full sm:w-auto flex-1 min-w-[200px] px-3 py-2.5 border-[1.5px] border-slate-200 rounded-lg text-sm outline-none transition focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
            @if(auth()->user()->isAdmin())
            <select name="bidang_id" class="px-3 py-2.5 border-[1.5px] border-slate-200 rounded-lg text-sm outline-none transition focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" onchange="this.form.submit()">
                <option value="">-- Bidang --</option>
                @foreach($bidangList as $b)
                <option value="{{ $b->id }}" {{ request('bidang_id') == $b->id ? 'selected' : '' }}>{{ $b->nama_bidang }}</option>
                @endforeach
            </select>
            @endif
            <select name="status_retensi" class="px-3 py-2.5 border-[1.5px] border-slate-200 rounded-lg text-sm outline-none transition focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" onchange="this.form.submit()">
                <option value="">-- Retensi --</option>
                <option value="aktif" {{ request('status_retensi') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="inaktif" {{ request('status_retensi') === 'inaktif' ? 'selected' : '' }}>Inaktif</option>
            </select>
            <select name="status_arsip" class="px-3 py-2.5 border-[1.5px] border-slate-200 rounded-lg text-sm outline-none transition focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" onchange="this.form.submit()">
                <option value="">-- Status --</option>
                <option value="tersedia" {{ request('status_arsip') === 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                <option value="dipinjam" {{ request('status_arsip') === 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
            </select>
            <button type="submit" class="px-3 py-1.5 text-xs font-semibold rounded-lg transition bg-gradient-to-r from-primary to-primary-light text-white shadow-sm hover:-translate-y-0.5">Cari</button>
            @if(request()->hasAny(['search','bidang_id','status_retensi','status_arsip','klasifikasi_keamanan']))
            <a href="{{ route('arsip.index') }}" class="px-3 py-1.5 text-xs font-semibold rounded-lg transition border border-slate-300 text-slate-600 hover:bg-slate-50">Reset</a>
            @endif
        </form>

        {{-- Table --}}
        <div class="overflow-x-auto mt-4">
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="th-sintara">No</th>
                        <th class="th-sintara">Kode Klasifikasi</th>
                        <th class="th-sintara">No. Berkas</th>
                        <th class="th-sintara">Uraian</th>
                        <th class="th-sintara">Tgl Arsip</th>
                        <th class="th-sintara">Umur Arsip</th>
                        <th class="th-sintara">Retensi</th>
                        <th class="th-sintara">Status</th>
                        <th class="th-sintara">Bidang</th>
                        <th class="th-sintara">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($arsipList as $i => $a)
                    <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition">
                        <td class="td-sintara">{{ $arsipList->firstItem() + $i }}</td>
                        <td class="td-sintara font-bold text-slate-800">{{ $a->kode_klasifikasi }}</td>
                        <td class="td-sintara">{{ $a->no_berkas }}</td>
                        <td class="td-sintara">{{ Str::limit($a->uraian_berkas, 40) }}</td>
                        <td class="td-sintara">{{ $a->tanggal_diarsipkan?->format('d/m/Y') }}</td>
                        <td class="td-sintara">
                            @php $umurHari = $a->umur_hari; @endphp
                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full {{ $umurHari > 1825 ? 'bg-red-100 text-red-700' : ($umurHari > 365 ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700') }}">{{ $a->umur_arsip }}</span>
                        </td>
                        <td class="td-sintara">
                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full {{ $a->status_retensi === 'aktif' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">{{ ucfirst($a->status_retensi) }}</span>
                        </td>
                        <td class="td-sintara">
                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full {{ $a->status_arsip === 'tersedia' ? 'bg-cyan-100 text-cyan-700' : 'bg-amber-100 text-amber-700' }}">{{ ucfirst($a->status_arsip) }}</span>
                        </td>
                        <td class="td-sintara">{{ $a->bidang->nama_bidang ?? '-' }}</td>
                        <td class="td-sintara">
                            <div class="flex items-center gap-1">
                                <a href="{{ route('arsip.show', $a) }}" class="px-3 py-1.5 text-xs font-semibold rounded-lg transition bg-gradient-to-r from-cyan-500 to-cyan-600 text-white hover:-translate-y-0.5">Lihat</a>
                                <a href="{{ route('arsip.edit', $a) }}" class="px-3 py-1.5 text-xs font-semibold rounded-lg transition bg-gradient-to-r from-amber-500 to-amber-600 text-white hover:-translate-y-0.5">Edit</a>
                                <button class="px-3 py-1.5 text-xs font-semibold rounded-lg transition bg-gradient-to-r from-red-500 to-red-600 text-white hover:-translate-y-0.5" onclick="confirmDelete('{{ route('arsip.destroy', $a) }}')">Hapus</button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="td-sintara text-center text-slate-400">Tidak ada data arsip.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="text-xs text-slate-500 mt-4">Menampilkan {{ $arsipList->firstItem() ?? 0 }} - {{ $arsipList->lastItem() ?? 0 }} dari {{ $arsipList->total() }} data</div>
        {{ $arsipList->links('components.pagination') }}
    </div>
</div>
@endsection
