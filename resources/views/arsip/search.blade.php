@extends('layouts.app')
@section('title', 'Pencarian Arsip')
@section('content')

<div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 mb-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <p class="text-sm uppercase tracking-[0.2em] text-primary/80 font-semibold mb-2">Pencarian Arsip</p>
            <h1 class="text-2xl font-bold text-slate-900">{{ auth()->user()->isAdmin() ? 'Cari Arsip Semua Bidang' : 'Cari Arsip ' . (auth()->user()->bidang->nama_bidang ?? 'Bidang Saya') }}</h1>
            <p class="text-sm text-slate-500 mt-2">Cari arsip berdasarkan klasifikasi, nomor berkas, lokasi rak, atau status retensi untuk unit kerja Anda.</p>
            <p class="text-xs text-slate-400 mt-2">{{ auth()->user()->isAdmin() ? 'Filter ini berlaku untuk seluruh bidang.' : 'Hanya arsip bidang Anda yang akan ditampilkan.' }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('arsip.search') }}" class="text-sm font-semibold text-primary hover:underline">Reset Filter</a>
        </div>
    </div>

    <form method="GET" action="{{ route('arsip.search') }}" class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-5 gap-4">
        <div class="xl:col-span-2">
            <label class="block text-xs font-semibold text-slate-500 mb-1.5">Kata Kunci</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor berkas, judul, atau uraian" class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
        </div>

        @if(auth()->user()->isAdmin())
        <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1.5">Bidang / Unit Kerja</label>
            <select name="bidang_id" class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
                <option value="">Semua Bidang</option>
                @foreach($bidangList as $b)
                <option value="{{ $b->id }}" {{ request('bidang_id') == $b->id ? 'selected' : '' }}>{{ $b->nama_bidang }}</option>
                @endforeach
            </select>
        </div>
        @endif

        <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1.5">Kode Klasifikasi</label>
            <input type="text" name="kode_klasifikasi" value="{{ request('kode_klasifikasi') }}" placeholder="Contoh: 005/TU" class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1.5">Klasifikasi Keamanan</label>
            <select name="klasifikasi_keamanan" class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
                <option value="">Semua Tingkat</option>
                <option value="biasa" {{ request('klasifikasi_keamanan') == 'biasa' ? 'selected' : '' }}>Biasa</option>
                <option value="terbatas" {{ request('klasifikasi_keamanan') == 'terbatas' ? 'selected' : '' }}>Terbatas</option>
                <option value="rahasia" {{ request('klasifikasi_keamanan') == 'rahasia' ? 'selected' : '' }}>Rahasia</option>
                <option value="sangat_rahasia" {{ request('klasifikasi_keamanan') == 'sangat_rahasia' ? 'selected' : '' }}>Sangat Rahasia</option>
            </select>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1.5">Status Retensi</label>
            <select name="status_retensi" class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
                <option value="">Semua Status</option>
                <option value="aktif" {{ request('status_retensi') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="inaktif" {{ request('status_retensi') == 'inaktif' ? 'selected' : '' }}>Inaktif</option>
            </select>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-500 mb-1.5">Status Arsip</label>
            <select name="status_arsip" class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
                <option value="">Semua Status</option>
                <option value="tersedia" {{ request('status_arsip') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                <option value="dipinjam" {{ request('status_arsip') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
            </select>
        </div>

        <div class="lg:col-span-2 xl:col-span-3 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Tanggal Mulai</label>
                <input type="date" name="dari" value="{{ request('dari') }}" class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Tanggal Akhir</label>
                <input type="date" name="sampai" value="{{ request('sampai') }}" class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
            </div>
        </div>

        <div class="lg:col-span-2 xl:col-span-3 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1.5">No. Boks</label>
                <input type="text" name="no_boks" value="{{ request('no_boks') }}" placeholder="No. Boks" class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1.5">No. Rak</label>
                <input type="text" name="no_rak" value="{{ request('no_rak') }}" placeholder="No. Rak" class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
            </div>
        </div>

        <div class="xl:col-span-5 flex flex-col gap-3 sm:flex-row sm:justify-end sm:items-center">
            <a href="{{ route('arsip.search') }}" class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">Bersihkan</a>
            <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-primary px-5 py-3 text-sm font-semibold text-white hover:bg-primary-light transition">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="mr-2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                Terapkan Filter
            </button>
        </div>
    </form>
</div>

<div class="flex items-center justify-between mb-4 flex-wrap gap-2">
    <div>
        <h2 class="font-semibold text-slate-800">Hasil Pencarian</h2>
        <p class="text-xs text-slate-400 mt-0.5">Menampilkan {{ $arsipList->total() }} arsip ditemukan</p>
    </div>
    <a href="{{ route('report.arsip.excel') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-50 transition">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
        Export Excel
    </a>
</div>

<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-6">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-primary text-white text-xs uppercase tracking-wide">
                    <th class="px-5 py-3 text-left font-semibold">No. Surat / Arsip</th>
                    <th class="px-5 py-3 text-left font-semibold whitespace-nowrap">Tgl Masuk</th>
                    <th class="px-5 py-3 text-left font-semibold">Instansi / Bidang</th>
                    <th class="px-5 py-3 text-left font-semibold">Lokasi</th>
                    <th class="px-5 py-3 text-left font-semibold">Status</th>
                    <th class="px-5 py-3 text-right font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($arsipList as $arsip)
                <tr class="hover:bg-slate-50">
                    <td class="px-5 py-3.5">
                        <p class="font-semibold text-primary">{{ $arsip->no_berkas }}</p>
                        <p class="text-xs text-slate-400 truncate max-w-[220px]">{{ $arsip->uraian_berkas }}</p>
                    </td>
                    <td class="px-5 py-3.5 text-slate-600 whitespace-nowrap">{{ $arsip->tanggal_diarsipkan?->translatedFormat('d M Y') }}</td>
                    <td class="px-5 py-3.5">
                        <p class="font-medium text-slate-700">{{ $arsip->bidang->nama_bidang ?? '-' }}</p>
                        <p class="text-xs text-slate-400">{{ $arsip->kode_klasifikasi }}</p>
                    </td>
                    <td class="px-5 py-3.5 whitespace-nowrap">
                        <span class="px-2 py-1 rounded-md bg-slate-100 text-slate-600 text-xs font-medium">BOKS-{{ $arsip->no_boks ?: '-' }}</span>
                        <span class="px-2 py-1 rounded-md bg-slate-100 text-slate-600 text-xs font-medium">RAK-{{ $arsip->no_rak ?: '-' }}</span>
                    </td>
                    <td class="px-5 py-3.5 whitespace-nowrap">
                        <span class="px-2.5 py-1 rounded-full text-[0.7rem] font-semibold {{ $arsip->status_arsip === 'dipinjam' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' }}">{{ $arsip->status_arsip === 'dipinjam' ? 'Dipinjam' : 'Biasa' }}</span>
                    </td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('arsip.show', $arsip) }}" class="p-1.5 rounded-md text-slate-400 hover:bg-slate-100 hover:text-primary transition" title="Lihat"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></a>
                            <a href="{{ route('arsip.edit', $arsip) }}" class="p-1.5 rounded-md text-slate-400 hover:bg-slate-100 hover:text-amber-600 transition" title="Edit"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4z"/></svg></a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-10 text-center text-slate-400">Tidak ada arsip yang ditemukan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-4 border-t border-slate-100 flex items-center justify-between flex-wrap gap-3">
        <p class="text-xs text-slate-400">Menampilkan {{ $arsipList->firstItem() ?? 0 }} - {{ $arsipList->lastItem() ?? 0 }} dari {{ $arsipList->total() }} arsip</p>
        {{ $arsipList->links('components.pagination') }}
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
        <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide">Arsip Terklasifikasi</p>
        <p class="text-3xl font-bold text-primary mt-1">{{ $arsipList->total() }}</p>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
        <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide">Total Surat Masuk</p>
        <p class="text-3xl font-bold text-primary mt-1">{{ number_format($totalSuratMasuk) }}</p>
        <p class="text-xs text-slate-400 mt-1">Tahun {{ date('Y') }}</p>
    </div>
</div>

<div class="flex items-center justify-between mt-8 pt-5 border-t border-slate-200 text-xs text-slate-400 flex-wrap gap-2">
    <p>© {{ date('Y') }} Bappeda Provinsi Lampung. Sistem Informasi Tata Kelola Arsip (SINTARA).</p>
    <div class="flex items-center gap-4">
        <a href="#" class="hover:text-primary">Panduan Pengguna</a>
        <a href="#" class="hover:text-primary">Bantuan Teknis</a>
    </div>
</div>
@endsection
