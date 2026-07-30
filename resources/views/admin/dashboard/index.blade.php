@extends('layouts.app')
@section('title', 'Beranda')
@section('content')

@if(auth()->user()->isOperator())
{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- OPERATOR DASHBOARD --}}
{{-- ═══════════════════════════════════════════════════════════ --}}

{{-- Hero Banner --}}
<div class="grid gap-6 mb-6">
    <div class="relative overflow-hidden rounded-[2rem] bg-gradient-to-br from-slate-900 via-slate-900 to-slate-800 text-white shadow-2xl">
        <div class="absolute inset-0 opacity-20 bg-[radial-gradient(circle_at_top_left,_rgba(59,130,246,0.35),_transparent_25%)]"></div>
        <div class="absolute -right-8 top-10 h-48 w-48 rounded-full bg-slate-700/40 blur-3xl"></div>
        <div class="relative p-8 sm:p-10 lg:p-12">
            <p class="text-sm uppercase tracking-[0.28em] text-sky-300/80 font-semibold mb-3">Ringkasan Bidang</p>
            <h1 class="text-3xl sm:text-4xl font-extrabold leading-tight">{{ auth()->user()->bidang->nama_bidang ?? 'Bidang Saya' }}</h1>
            <p class="mt-3 max-w-2xl text-slate-300 text-sm sm:text-base">Kelola arsip strategis pembangunan {{ strtolower(auth()->user()->bidang->nama_bidang ?? '') }} Provinsi Lampung dengan sistem terintegrasi.</p>

            <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-[1.5rem] bg-slate-950/60 border border-white/10 p-4 sm:p-5">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Total Arsip</p>
                    <p class="mt-2 text-2xl sm:text-3xl font-bold">{{ number_format($totalArsip) }}</p>
                </div>
                <div class="rounded-[1.5rem] bg-slate-950/60 border border-white/10 p-4 sm:p-5">
                    <p class="text-xs uppercase tracking-[0.2em] text-emerald-400">Arsip Aktif / Inaktif</p>
                    <p class="mt-2 text-2xl sm:text-3xl font-bold">{{ number_format($arsipAktif) }} <span class="text-sm font-normal text-slate-400">/ {{ number_format($arsipInaktif) }}</span></p>
                </div>
                <div class="rounded-[1.5rem] bg-slate-950/60 border border-white/10 p-4 sm:p-5">
                    <p class="text-xs uppercase tracking-[0.2em] text-amber-400">Sedang Dipinjam</p>
                    <p class="mt-2 text-2xl sm:text-3xl font-bold">{{ number_format($arsipDipinjam) }}</p>
                </div>
                <div class="rounded-[1.5rem] bg-slate-950/60 border border-white/10 p-4 sm:p-5">
                    <p class="text-xs uppercase tracking-[0.2em] text-red-400">Pinjaman Terlambat</p>
                    <p class="mt-2 text-2xl sm:text-3xl font-bold {{ $peminjamanTerlambat > 0 ? 'text-red-400' : 'text-white' }}">{{ number_format($peminjamanTerlambat) }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Two Column Layout: Table + Activity --}}
<div class="grid gap-6 lg:grid-cols-[1.7fr_1fr]">
    {{-- Left: Arsip Terbaru Table --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between gap-4 px-6 py-5 border-b border-slate-200">
            <h2 class="text-base font-bold text-slate-900">
                Arsip Terbaru Bidang {{ auth()->user()->bidang->nama_bidang ?? '' }}
            </h2>
            <a href="{{ route('arsip.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-semibold rounded-lg border-2 border-primary text-primary hover:bg-primary hover:text-white transition-all duration-200">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Tambah Arsip
            </a>
        </div>
        <p class="text-xs text-slate-400 mb-4">Analitik bulanan penambahan arsip fisik dan digital.</p>
        <canvas id="growthChart" height="90"></canvas>
    </div>

    {{-- Right: Aktivitas Terakhir --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
        <div class="flex items-center gap-2 mb-5">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#1B3A5C" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            <h3 class="text-base font-bold text-slate-900">Aktivitas Terakhir</h3>
        </div>

        <div class="relative">
            {{-- Timeline line --}}
            <div class="absolute left-[18px] top-2 bottom-2 w-0.5 bg-slate-200"></div>

            <div class="space-y-5">
                @forelse($recentArsip->take(4) as $idx => $arsip)
                <div class="relative flex gap-3.5 pl-0">
                    <div class="w-9 h-9 rounded-full shrink-0 flex items-center justify-center z-10 {{ $idx === 0 ? 'bg-primary text-white' : 'bg-amber-100 text-amber-600' }}">
                        @if($idx === 0)
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        @else
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="6" y="6" width="12" height="12" rx="1"/></svg>
                        @endif
                    </div>
                    <div class="min-w-0 flex-1 pt-1">
                        <p class="text-sm font-semibold text-slate-800 leading-snug">{{ $arsip->uraian_berkas }}</p>
                        <p class="text-xs text-slate-400 mt-1">{{ $arsip->created_at?->diffForHumans() ?? '-' }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-6">
                    <p class="text-sm text-slate-400">Belum ada aktivitas terbaru.</p>
                </div>
                @endforelse
            </div>
        </div>

        <a href="{{ route('admin.arsip.index') }}" class="mt-6 flex items-center justify-center w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 hover:border-slate-300 transition">
            Lihat Semua Aktivitas
        </a>
    </div>
</div>

@else
{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- ADMIN DASHBOARD (unchanged) --}}
{{-- ═══════════════════════════════════════════════════════════ --}}

@php
    $pendingResetRequestsCount = \App\Models\PasswordResetRequest::where('status', 'pending')->count();
@endphp

@if($pendingResetRequestsCount > 0)
<div class="mb-6 p-4.5 rounded-2xl bg-amber-50 border border-amber-200 flex items-center justify-between gap-4 shadow-sm">
    <div class="flex items-center gap-3.5">
        <div class="w-10 h-10 rounded-xl bg-amber-500/15 flex items-center justify-center text-amber-600 shrink-0">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
        </div>
        <div>
            <h4 class="text-sm font-bold text-amber-950">Permintaan Reset Password Operator ({{ $pendingResetRequestsCount }})</h4>
            <p class="text-xs text-amber-800 mt-0.5">Ada {{ $pendingResetRequestsCount }} permohonan reset password dari Operator yang membutuhkan konfirmasi Admin.</p>
        </div>
    </div>
    <a href="{{ route('admin.bidang.index') }}" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-semibold text-xs rounded-xl shadow-xs transition shrink-0">
        Konfirmasi Sekarang &rarr;
    </a>
</div>
@endif

<div class="mb-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 p-6 rounded-2xl text-white shadow-md relative overflow-hidden">
    <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-sky-500/10 rounded-full blur-2xl"></div>
    <div class="relative z-10">
        <p class="text-[0.7rem] uppercase tracking-[0.2em] text-amber-400 font-bold mb-1">Dashboard Administrator</p>
        <h1 class="text-2xl font-extrabold text-white">Selamat Datang, {{ auth()->user()->name }}</h1>
        <p class="text-xs text-slate-300 mt-1">Ringkasan arsip, status fisik dokumen, dan peminjaman Bappeda Provinsi Lampung.</p>
    </div>
</div>

{{-- Compact Stat Cards (Smaller & Sleeker) --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 mb-6">
    {{-- Card 1: Total Arsip --}}
    <div class="bg-white rounded-xl border border-slate-200/80 p-3.5 sm:p-4 shadow-xs flex items-center gap-3">
        <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 8v11a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V8"/><path d="M22 8H2l1.5-4h17z"/><path d="M12 12v4"/><path d="M10 14h4"/></svg>
        </div>
        <div class="min-w-0 flex-1">
            <p class="text-[0.68rem] font-bold text-slate-400 uppercase tracking-wide truncate">Total Arsip</p>
            <p class="text-xl sm:text-2xl font-extrabold text-slate-800 mt-0.5">{{ number_format($totalArsip) }}</p>
        </div>
    </div>

    {{-- Card 2: Aktif / Inaktif --}}
    <div class="bg-white rounded-xl border border-slate-200/80 p-3.5 sm:p-4 shadow-xs flex items-center gap-3">
        <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        </div>
        <div class="min-w-0 flex-1">
            <p class="text-[0.68rem] font-bold text-emerald-600 uppercase tracking-wide truncate">Aktif / Inaktif</p>
            <p class="text-xl sm:text-2xl font-extrabold text-slate-800 mt-0.5">
                {{ number_format($arsipAktif) }}
                <span class="text-xs font-semibold text-slate-400">/ {{ number_format($arsipInaktif) }}</span>
            </p>
        </div>
    </div>

    {{-- Card 3: Sedang Dipinjam --}}
    <div class="bg-white rounded-xl border border-slate-200/80 p-3.5 sm:p-4 shadow-xs flex items-center gap-3">
        <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
        </div>
        <div class="min-w-0 flex-1">
            <p class="text-[0.68rem] font-bold text-amber-600 uppercase tracking-wide truncate">Sedang Dipinjam</p>
            <p class="text-xl sm:text-2xl font-extrabold text-slate-800 mt-0.5">{{ number_format($arsipDipinjam) }}</p>
        </div>
    </div>

    {{-- Card 4: Pinjaman Terlambat --}}
    <div class="bg-white rounded-xl border border-slate-200/80 p-3.5 sm:p-4 shadow-xs flex items-center gap-3">
        <div class="w-10 h-10 rounded-lg {{ $peminjamanTerlambat > 0 ? 'bg-red-50 text-red-600' : 'bg-slate-100 text-slate-500' }} flex items-center justify-center shrink-0">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        </div>
        <div class="min-w-0 flex-1">
            <p class="text-[0.68rem] font-bold {{ $peminjamanTerlambat > 0 ? 'text-red-600' : 'text-slate-400' }} uppercase tracking-wide truncate">Terlambat</p>
            <p class="text-xl sm:text-2xl font-extrabold {{ $peminjamanTerlambat > 0 ? 'text-red-600' : 'text-slate-800' }} mt-0.5">{{ number_format($peminjamanTerlambat) }}</p>
        </div>
    </div>
</div>

{{-- Two Column Layout: Table + Activity --}}
<div class="grid gap-6 lg:grid-cols-[1.7fr_1fr] mb-6">
    {{-- Left: Daftar Arsip Baru --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden min-w-0">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 px-5 py-4 border-b border-slate-100">
            <div>
                <h3 class="font-bold text-slate-800 text-base">Daftar Arsip Baru</h3>
                <p class="text-xs text-slate-500 mt-0.5">Arsip dokumen terbaru di bidang {{ auth()->user()->bidang->nama_bidang ?? 'Anda' }}.</p>
            </div>
            <a href="{{ route('admin.arsip.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg bg-primary text-white hover:bg-primary-light transition shrink-0">
                Tambah Arsip
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 text-slate-600 text-xs uppercase tracking-wide border-b border-slate-100">
                        <th class="px-4 py-3 text-left font-semibold whitespace-nowrap">No. Berkas</th>
                        <th class="px-4 py-3 text-left font-semibold">Judul & Perihal</th>
                        <th class="px-4 py-3 text-left font-semibold whitespace-nowrap">Tanggal</th>
                        <th class="px-4 py-3 text-left font-semibold whitespace-nowrap">Lokasi</th>
                        <th class="px-4 py-3 text-right font-semibold whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentArsip->take(4) as $arsip)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="px-4 py-3.5 font-semibold text-primary text-xs whitespace-nowrap">{{ $arsip->no_berkas }}</td>
                        <td class="px-4 py-3.5 min-w-0 max-w-[200px]">
                            <p class="font-semibold text-slate-800 text-xs truncate" title="{{ $arsip->uraian_berkas }}">{{ $arsip->uraian_berkas }}</p>
                            <p class="text-[0.7rem] text-slate-400 mt-0.5 truncate" title="{{ $arsip->uraian_arsip }}">{{ Str::limit($arsip->uraian_arsip, 60) }}</p>
                        </td>
                        <td class="px-4 py-3.5 text-slate-600 text-xs whitespace-nowrap">{{ $arsip->tanggal_diarsipkan?->translatedFormat('d M Y') ?? '-' }}</td>
                        <td class="px-4 py-3.5 whitespace-nowrap">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-slate-100 text-slate-600 text-[0.75rem]">Rak {{ $arsip->no_rak ?: '-' }}</span>
                        </td>
                        <td class="px-4 py-3.5 text-right whitespace-nowrap">
                            <a href="{{ route('admin.arsip.show', $arsip) }}" class="inline-flex items-center gap-1 text-xs font-semibold text-primary hover:underline">
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-8 text-center text-slate-400 text-xs">Tidak ada arsip terbaru.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3 bg-slate-50 border-t border-slate-100 text-xs text-slate-500">
            Menampilkan {{ count($recentArsip->take(4)) }} dari {{ number_format($recentArsip->count()) }} arsip terbaru.
        </div>
    </div>

    {{-- Right: Aktivitas Terakhir --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 min-w-0">
        <div class="flex items-center gap-2 mb-4">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#1B3A5C" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            <h3 class="text-base font-bold text-slate-900">Aktivitas Terakhir</h3>
        </div>

        <div class="relative">
            {{-- Timeline line --}}
            <div class="absolute left-[15px] top-2 bottom-2 w-0.5 bg-slate-200"></div>

            <div class="space-y-4">
                @forelse($recentArsip->take(4) as $idx => $arsip)
                <div class="relative flex gap-3 pl-0">
                    <div class="w-8 h-8 rounded-full shrink-0 flex items-center justify-center z-10 {{ $idx === 0 ? 'bg-primary text-white' : 'bg-amber-100 text-amber-600' }}">
                        @if($idx === 0)
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        @else
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="6" y="6" width="12" height="12" rx="1"/></svg>
                        @endif
                    </div>
                    <div class="min-w-0 flex-1 pt-0.5">
                        <p class="text-xs font-semibold text-slate-800 leading-snug truncate" title="{{ $arsip->uraian_berkas }}">{{ $arsip->uraian_berkas }}</p>
                        <p class="text-[0.7rem] text-slate-400 mt-0.5">{{ $arsip->created_at?->diffForHumans() ?? '-' }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-6">
                    <p class="text-xs text-slate-400">Belum ada aktivitas terbaru.</p>
                </div>
                @endforelse
            </div>
        </div>

        <a href="{{ route('admin.arsip.index') }}" class="mt-5 flex items-center justify-center w-full rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:border-slate-300 transition">
            Lihat Semua Aktivitas
        </a>
    </div>
</div>

@if($arsipBulanIniPerBidang && $arsipBulanIniPerBidang->count())
{{-- Statistik Arsip Per Bidang Bulan Ini --}}
<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-6">
    <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
        <div>
            <h3 class="font-bold text-slate-800 text-base">Statistik Arsip Per Bidang</h3>
            <p class="text-xs text-slate-400 mt-0.5">Jumlah arsip masuk di bulan {{ now()->translatedFormat('F Y') }} — seluruh bidang</p>
        </div>
        <span class="text-xs font-semibold text-primary bg-primary/10 px-3 py-1 rounded-full">{{ now()->translatedFormat('F Y') }}</span>
    </div>
    <div class="p-5">
        {{-- Bar rows per bidang --}}
        @php
            $maxTotal = $arsipBulanIniPerBidang->max('arsip_total') ?: 1;
            $maxBulan = $arsipBulanIniPerBidang->max('arsip_bulan_ini') ?: 1;
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
            @foreach($arsipBulanIniPerBidang as $b)
            <div class="flex flex-col gap-1.5 rounded-xl bg-slate-50 border border-slate-100 px-4 py-3">
                <div class="flex items-center justify-between gap-2">
                    <p class="text-xs font-semibold text-slate-700 truncate" title="{{ $b->nama_bidang }}">{{ $b->nama_bidang }}</p>
                    <span class="text-xs font-bold text-primary shrink-0">+{{ number_format($b->arsip_bulan_ini) }}</span>
                </div>
                <div class="w-full bg-slate-200 rounded-full h-1.5 overflow-hidden">
                    <div class="bg-primary h-1.5 rounded-full transition-all" style="width: {{ $maxBulan > 0 ? round(($b->arsip_bulan_ini / $maxBulan) * 100) : 0 }}%"></div>
                </div>
                <p class="text-[0.68rem] text-slate-400">Total: {{ number_format($b->arsip_total) }} arsip</p>
            </div>
            @endforeach
        </div>

        {{-- Chart canvas --}}
        <canvas id="bidangBulanChart" height="80"></canvas>
    </div>
</div>
@endif

{{-- Import Modal (overlay) --}}
<div id="importModal" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl max-h-[80vh] flex flex-col mx-4">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <h3 class="text-lg font-bold text-slate-800">Preview Import</h3>
            <button type="button" onclick="cancelImport()" class="text-slate-400 hover:text-slate-600 transition">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="p-6 overflow-y-auto flex-1">
            <div id="importLoading" class="text-center py-10 text-slate-400 text-sm">Membaca file...</div>
            <div id="importErrorBox" class="hidden bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg px-4 py-3 mb-4"></div>
            <div id="importPreviewWrap" class="hidden">
                <div class="mb-4">
                    <label class="block text-xs font-semibold text-slate-500 mb-1.5">Assign ke Bidang (opsional)</label>
                    <select id="importBidangSelect" onchange="document.getElementById('importBidangId').value = this.value" class="w-full px-3.5 py-2.5 rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
                        <option value="">Deteksi otomatis dari kolom "Bidang" di file (atau bidang pertama jika tidak ditemukan)</option>
                        @foreach(\App\Models\Bidang::orderBy('nama_bidang')->get() as $b)
                        <option value="{{ $b->id }}">{{ $b->nama_bidang }}</option>
                        @endforeach
                    </select>
                </div>
                <p class="text-xs text-slate-500 mb-2"><span id="importRowCount" class="font-semibold text-slate-700">0</span> baris data terdeteksi. Menampilkan maksimal 10 baris pertama sebagai contoh:</p>
                <div class="overflow-x-auto border border-slate-200 rounded-lg">
                    <table class="w-full text-xs">
                        <thead id="importPreviewHead" class="bg-slate-50"></thead>
                        <tbody id="importPreviewBody" class="divide-y divide-slate-100"></tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="flex justify-end gap-3 px-6 py-4 border-t border-slate-100 shrink-0">
            <button type="button" onclick="cancelImport()" class="px-4 py-2 text-sm font-semibold rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-50 transition">Batal</button>
            <button type="button" id="importConfirmBtn" onclick="confirmImport()" disabled class="px-4 py-2 text-sm font-semibold rounded-lg bg-primary text-white hover:bg-primary-light transition disabled:opacity-40 disabled:cursor-not-allowed">Konfirmasi & Import</button>
        </div>
    </div>
</div>
@endif

@push('scripts')
<script>
const growthCtx = document.getElementById('growthChart');
new Chart(growthCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode(collect($monthlyGrowth)->pluck('label')) !!},
        datasets: [{
            data: {!! json_encode(collect($monthlyGrowth)->pluck('count')) !!},
            backgroundColor: '#1B3A5C',
            borderRadius: 4,
            maxBarThickness: 42,
        }]
    },
    options: {
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: '#F1F5F9' }, ticks: { precision: 0 } },
            x: { grid: { display: false } }
        }
    }
});

@if($arsipBulanIniPerBidang && $arsipBulanIniPerBidang->count())
const bidangBulanCtx = document.getElementById('bidangBulanChart');
if (bidangBulanCtx) {
    new Chart(bidangBulanCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($arsipBulanIniPerBidang->pluck('nama_bidang')) !!},
            datasets: [
                {
                    label: 'Bulan Ini',
                    data: {!! json_encode($arsipBulanIniPerBidang->pluck('arsip_bulan_ini')) !!},
                    backgroundColor: '#1B3A5C',
                    borderRadius: 6,
                    maxBarThickness: 48,
                },
                {
                    label: 'Total Keseluruhan',
                    data: {!! json_encode($arsipBulanIniPerBidang->pluck('arsip_total')) !!},
                    backgroundColor: '#CBD5E1',
                    borderRadius: 6,
                    maxBarThickness: 48,
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: true, position: 'top', labels: { font: { size: 11 }, boxWidth: 12 } },
                tooltip: { callbacks: { label: ctx => ` ${ctx.dataset.label}: ${ctx.parsed.y.toLocaleString('id-ID')}` } }
            },
            scales: {
                y: { beginAtZero: true, grid: { color: '#F1F5F9' }, ticks: { precision: 0 } },
                x: { grid: { display: false }, ticks: { maxRotation: 30, font: { size: 10 } } }
            }
        }
    });
}
@endif

function openImportModal() {
    const m = document.getElementById('importModal');
    m.classList.remove('hidden');
    m.classList.add('flex');
}
function closeImportModal() {
    const m = document.getElementById('importModal');
    m.classList.add('hidden');
    m.classList.remove('flex');
}
function resetImportModal() {
    document.getElementById('importLoading').classList.remove('hidden');
    document.getElementById('importErrorBox').classList.add('hidden');
    document.getElementById('importPreviewWrap').classList.add('hidden');
    document.getElementById('importConfirmBtn').disabled = true;
    document.getElementById('importPreviewHead').innerHTML = '';
    document.getElementById('importPreviewBody').innerHTML = '';
}
function cancelImport() {
    document.getElementById('importFile').value = '';
    document.getElementById('importBidangId').value = '';
    document.getElementById('importBidangSelect').value = '';
    closeImportModal();
}
function confirmImport() {
    document.getElementById('importForm').submit();
}
async function handleImportFileSelect(event) {
    const file = event.target.files[0];
    if (!file) return;
    resetImportModal();
    openImportModal();

    const formData = new FormData();
    formData.append('file', file);

    try {
        const res = await fetch('{{ route('admin.arsip.preview') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: formData,
        });
        const json = await res.json();
        document.getElementById('importLoading').classList.add('hidden');

        if (json.status !== 'success') {
            document.getElementById('importErrorBox').textContent = json.message || 'Gagal membaca file.';
            document.getElementById('importErrorBox').classList.remove('hidden');
            return;
        }

        renderImportPreview(json.data);
    } catch (e) {
        document.getElementById('importLoading').classList.add('hidden');
        document.getElementById('importErrorBox').textContent = 'Terjadi kesalahan saat membaca file.';
        document.getElementById('importErrorBox').classList.remove('hidden');
    }
}
function renderImportPreview(rows) {
    document.getElementById('importRowCount').textContent = rows.length;
    document.getElementById('importPreviewWrap').classList.remove('hidden');

    if (rows.length === 0) {
        document.getElementById('importConfirmBtn').disabled = true;
        return;
    }

    const columns = Object.keys(rows[0]);
    const thead = document.getElementById('importPreviewHead');
    thead.innerHTML = '<tr>' + columns.map(c => `<th class="px-3 py-2 text-left font-semibold text-slate-500 whitespace-nowrap">${c}</th>`).join('') + '</tr>';

    const tbody = document.getElementById('importPreviewBody');
    tbody.innerHTML = rows.slice(0, 10).map(row => {
        return '<tr>' + columns.map(c => `<td class="px-3 py-2 text-slate-600 whitespace-nowrap">${row[c] ?? ''}</td>`).join('') + '</tr>';
    }).join('');

    document.getElementById('importConfirmBtn').disabled = false;
}
</script>
@endpush
@endsection