@extends('layouts.app')
@section('title', 'Surat Masuk')
@section('breadcrumb')
<a href="{{ route('dashboard') }}" class="hover:text-primary">Beranda</a> <span>/</span> <span class="text-slate-700 font-medium">Arsip Surat</span>
@endsection
@section('content')

<div class="flex items-start justify-between mb-6 flex-wrap gap-3">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Surat Masuk</h1>
        <p class="text-sm text-slate-500 mt-1">Daftar surat masuk yang diterima {{ auth()->user()->isAdmin() ? 'seluruh bidang' : 'bidang ' . (auth()->user()->bidang->nama_bidang ?? '-') }}.</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('surat-keluar.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-lg border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 transition shadow-sm">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
            Lihat Surat Keluar
        </a>
        <a href="{{ route('surat-masuk.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-lg bg-primary text-white hover:bg-primary-light transition shadow-sm">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Registrasi Surat Masuk
        </a>
    </div>
</div>

<div class="bg-white rounded-xl border border-slate-200 p-5 mb-6 shadow-sm">
    <form method="GET" action="{{ route('surat-masuk.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
        <div class="sm:col-span-2">
            <label class="block text-xs font-semibold text-slate-500 mb-1.5">Cari</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nomor surat, pengirim, perihal..." class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
        </div>
        @if(auth()->user()->isAdmin())
        <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1.5">Bidang</label>
            <select name="bidang_id" class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
                <option value="">Semua Bidang</option>
                @foreach($bidangList as $b)
                <option value="{{ $b->id }}" {{ request('bidang_id') == $b->id ? 'selected' : '' }}>{{ $b->nama_bidang }}</option>
                @endforeach
            </select>
        </div>
        @endif
        <button type="submit" class="px-4 py-2.5 text-sm font-semibold rounded-lg bg-primary text-white hover:bg-primary-light transition">Terapkan</button>
    </form>
</div>

<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-slate-100">
        <p class="text-sm text-slate-600"><span class="font-semibold text-slate-800">{{ number_format($suratMasuk->total()) }}</span> Total Surat Masuk</p>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-primary text-white text-xs uppercase tracking-wide">
                    <th class="px-5 py-3 text-left font-semibold whitespace-nowrap">No. Surat</th>
                    <th class="px-5 py-3 text-left font-semibold">Pengirim & Perihal</th>
                    <th class="px-5 py-3 text-left font-semibold whitespace-nowrap">Tgl Diterima</th>
                    <th class="px-5 py-3 text-left font-semibold whitespace-nowrap">Sifat</th>
                    <th class="px-5 py-3 text-left font-semibold whitespace-nowrap">Status</th>
                    <th class="px-5 py-3 text-right font-semibold whitespace-nowrap">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($suratMasuk as $s)
                <tr class="hover:bg-slate-50">
                    <td class="px-5 py-3.5 font-semibold text-primary whitespace-nowrap">{{ $s->nomor_surat }}</td>
                    <td class="px-5 py-3.5">
                        <p class="font-semibold text-slate-800">{{ $s->pengirim }}</p>
                        <p class="text-xs text-slate-400 mt-0.5 truncate max-w-[280px]">{{ $s->perihal }}</p>
                        @if(auth()->user()->isAdmin())<span class="inline-block mt-1 px-1.5 py-0.5 rounded text-[0.65rem] font-bold bg-slate-100 text-slate-500">{{ $s->bidang->nama_bidang ?? '-' }}</span>@endif
                    </td>
                    <td class="px-5 py-3.5 text-slate-600 whitespace-nowrap">{{ $s->tanggal_diterima?->translatedFormat('d M Y') }}</td>
                    <td class="px-5 py-3.5 whitespace-nowrap">
                        @php $sifatColor = match($s->sifat_surat) { 'rahasia' => 'bg-red-50 text-red-600', 'sangat_segera' => 'bg-orange-50 text-orange-600', 'segera' => 'bg-amber-50 text-amber-600', default => 'bg-slate-100 text-slate-500' }; @endphp
                        <span class="px-2 py-1 rounded-md text-[0.7rem] font-semibold {{ $sifatColor }}">{{ ucwords(str_replace('_', ' ', $s->sifat_surat)) }}</span>
                    </td>
                    <td class="px-5 py-3.5 whitespace-nowrap">
                        <span class="px-2.5 py-1 rounded-full text-[0.7rem] font-semibold {{ $s->status === 'diarsipkan' ? 'bg-emerald-100 text-emerald-700' : 'bg-blue-100 text-blue-700' }}">{{ ucfirst($s->status) }}</span>
                    </td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('surat-masuk.show', $s) }}" class="p-1.5 rounded-md text-slate-400 hover:bg-slate-100 hover:text-primary transition" title="Lihat"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></a>
                            <a href="{{ route('surat-masuk.edit', $s) }}" class="p-1.5 rounded-md text-slate-400 hover:bg-slate-100 hover:text-amber-600 transition" title="Edit"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4z"/></svg></a>
                            <button type="button" onclick="confirmDelete('{{ route('surat-masuk.destroy', $s) }}')" class="p-1.5 rounded-md text-slate-400 hover:bg-red-50 hover:text-red-600 transition" title="Hapus"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-10 text-center text-slate-400">Belum ada data surat masuk.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-4 border-t border-slate-100 flex items-center justify-between flex-wrap gap-3">
        <p class="text-xs text-slate-400">Menampilkan {{ $suratMasuk->firstItem() ?? 0 }}-{{ $suratMasuk->lastItem() ?? 0 }} dari {{ number_format($suratMasuk->total()) }} data</p>
        {{ $suratMasuk->links('components.pagination') }}
    </div>
</div>
@endsection