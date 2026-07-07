@extends('layouts.app')
@section('title', 'Pencarian Arsip')
@section('content')

{{-- Hero Search --}}
<div class="-mx-4 sm:-mx-6 lg:-mx-8 mb-10 px-4 sm:px-6 lg:px-8 pt-16 pb-24 bg-gradient-to-br from-primary-dark to-primary text-center relative overflow-hidden">
    <div class="absolute inset-0 opacity-10 pointer-events-none">
        <div class="absolute top-0 right-0 w-96 h-96 bg-white rounded-full blur-3xl -mr-48 -mt-48"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-accent-gold rounded-full blur-3xl -ml-32 -mb-32"></div>
    </div>
    <div class="relative z-10 max-w-3xl mx-auto">
        <h1 class="text-3xl sm:text-4xl font-extrabold text-white mb-2">Pencarian Arsip</h1>
        <p class="text-white/70 mb-8">
            {{ auth()->user()->isAdmin() ? 'Mencari arsip di seluruh bidang' : 'Mencari arsip di lingkup ' . (auth()->user()->bidang->nama_bidang ?? 'bidang Anda') }}
        </p>
        <form method="GET" action="{{ route('arsip.search') }}" class="relative">
            <svg class="absolute left-6 top-1/2 -translate-y-1/2 text-primary/40" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik kata kunci, nomor berkas, atau uraian arsip..." class="w-full h-16 sm:h-20 pl-16 pr-32 sm:pr-40 rounded-xl border-0 shadow-2xl focus:ring-4 focus:ring-accent-gold/40 text-base sm:text-lg text-slate-800 placeholder-slate-400 outline-none">
            <button type="submit" class="absolute right-2 sm:right-3 top-1/2 -translate-y-1/2 px-5 sm:px-8 py-3 sm:py-4 bg-primary text-white rounded-lg text-sm font-bold hover:bg-primary-light transition">CARI</button>
        </form>
    </div>
</div>

{{-- Filter Lanjutan --}}
<div class="max-w-none mb-10">
    <form method="GET" action="{{ route('arsip.search') }}" class="bg-white rounded-xl shadow-lg border border-slate-200 p-8 sm:p-10">
        <input type="hidden" name="search" value="{{ request('search') }}">
        <div class="flex items-center gap-2 mb-8 text-primary">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
            <h3 class="font-bold text-slate-800 text-lg">Filter Lanjutan</h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
            @if(auth()->user()->isAdmin())
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Bidang / Unit Kerja</label>
                <select name="bidang_id" class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary bg-slate-50">
                    <option value="">Semua Bidang</option>
                    @foreach($bidangList as $b)
                    <option value="{{ $b->id }}" {{ request('bidang_id') == $b->id ? 'selected' : '' }}>{{ $b->nama_bidang }}</option>
                    @endforeach
                </select>
            </div>
            @endif

            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Kode Klasifikasi</label>
                <input type="text" name="kode_klasifikasi" value="{{ request('kode_klasifikasi') }}" placeholder="Contoh: 005/TU" class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary bg-slate-50">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Klasifikasi Keamanan</label>
                <select name="klasifikasi_keamanan" class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary bg-slate-50">
                    <option value="">Semua Tingkat</option>
                    <option value="biasa" {{ request('klasifikasi_keamanan') == 'biasa' ? 'selected' : '' }}>Biasa</option>
                    <option value="terbatas" {{ request('klasifikasi_keamanan') == 'terbatas' ? 'selected' : '' }}>Terbatas</option>
                    <option value="rahasia" {{ request('klasifikasi_keamanan') == 'rahasia' ? 'selected' : '' }}>Rahasia</option>
                    <option value="sangat_rahasia" {{ request('klasifikasi_keamanan') == 'sangat_rahasia' ? 'selected' : '' }}>Sangat Rahasia</option>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">No. Boks</label>
                    <input type="text" name="no_boks" value="{{ request('no_boks') }}" placeholder="No. Boks" class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary bg-slate-50">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">No. Rak</label>
                    <input type="text" name="no_rak" value="{{ request('no_rak') }}" placeholder="No. Rak" class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary bg-slate-50">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Tanggal Mulai</label>
                <input type="date" name="dari" value="{{ request('dari') }}" class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary bg-slate-50">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Tanggal Akhir</label>
                <input type="date" name="sampai" value="{{ request('sampai') }}" class="w-full rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary bg-slate-50">
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-slate-100 flex justify-end gap-3">
            <a href="{{ route('arsip.search') }}" class="px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50 rounded-lg transition">Reset Filter</a>
            <button type="submit" class="px-6 py-2.5 bg-primary text-white text-sm font-semibold rounded-lg shadow-sm hover:bg-primary-light transition">Terapkan Filter</button>
        </div>
    </form>
</div>

{{-- Hasil Pencarian --}}
<div class="max-w-none mt-2">
    <div class="flex items-center justify-between mb-6 flex-wrap gap-2">
        <div>
            <h2 class="font-bold text-slate-800">Hasil Pencarian</h2>
            <p class="text-xs text-slate-400 mt-0.5">Menampilkan {{ $totalArsip }} arsip ditemukan</p>
        </div>
        <a href="{{ route('report.arsip.excel') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-50 transition">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            Export Excel
        </a>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-10">
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

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-10">
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-7">
            <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide">Arsip Ditemukan</p>
            <p class="text-3xl font-bold text-primary mt-2">{{ $totalArsip }}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-7">
            <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide">Total Surat Masuk</p>
            <p class="text-3xl font-bold text-primary mt-2">{{ number_format($totalSuratMasuk) }}</p>
            <p class="text-xs text-slate-400 mt-1">Tahun {{ date('Y') }}</p>
        </div>
    </div>

    <div class="p-6 bg-slate-50 rounded-xl border-l-4 border-primary flex items-start gap-4 mb-8">
        <svg class="text-primary shrink-0 mt-0.5" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
        <div>
            <h6 class="font-bold text-slate-800 text-sm">Informasi Akses</h6>
            <p class="text-slate-500 text-sm mt-0.5">
                @if(auth()->user()->isOperator())
                    Hanya arsip milik bidang <span class="font-semibold text-primary">{{ auth()->user()->bidang->nama_bidang ?? 'Anda' }}</span> yang ditampilkan dalam hasil pencarian ini.
                @else
                    Filter berlaku untuk seluruh bidang. Gunakan filter <span class="font-semibold text-primary">Bidang / Unit Kerja</span> untuk mempersempit hasil.
                @endif
            </p>
        </div>
    </div>

    <div class="flex items-center justify-between pt-8 border-t border-slate-200 text-xs text-slate-400 flex-wrap gap-2">
        <p>© {{ date('Y') }} Bappeda Provinsi Lampung. Sistem Informasi Tata Kelola Arsip (SINTARA).</p>
        <div class="flex items-center gap-4">
            <a href="#" class="hover:text-primary">Panduan Pengguna</a>
            <a href="#" class="hover:text-primary">Bantuan Teknis</a>
        </div>
    </div>
</div>
@endsection