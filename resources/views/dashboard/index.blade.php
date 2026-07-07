@extends('layouts.app')
@section('title', 'Beranda')
@section('content')

@if(auth()->user()->isOperator())
{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- OPERATOR DASHBOARD --}}
{{-- ═══════════════════════════════════════════════════════════ --}}

{{-- Hero Banner --}}
<div class="relative overflow-hidden rounded-2xl mb-6 shadow-lg" style="min-height: 220px;">
    {{-- Background gradient overlay --}}
    <div class="absolute inset-0 bg-gradient-to-r from-primary-dark/95 via-primary/90 to-primary/70 z-10"></div>
    {{-- Decorative pattern --}}
    <div class="absolute inset-0 z-[5]" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.05\'%3E%3Ccircle cx=\'30\' cy=\'30\' r=\'2\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    {{-- Right decorative circles --}}
    <div class="absolute -right-10 -top-10 w-56 h-56 bg-accent-gold/15 rounded-full blur-3xl z-[6]"></div>
    <div class="absolute right-20 bottom-0 w-32 h-32 bg-white/5 rounded-full blur-2xl z-[6]"></div>
    {{-- Content --}}
    <div class="relative z-20 p-8 sm:p-10">
        <h1 class="text-2xl sm:text-3xl font-extrabold text-white leading-tight">
            Ringkasan Bidang {{ auth()->user()->bidang->nama_bidang ?? 'Bidang Saya' }}
        </h1>
        <p class="mt-3 text-white/70 text-sm sm:text-base max-w-2xl leading-relaxed">
            Kelola arsip strategis pembangunan {{ strtolower(auth()->user()->bidang->nama_bidang ?? '') }} Provinsi Lampung dengan sistem terintegrasi.
        </p>
    </div>
</div>

{{-- Stat Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    {{-- Total Arsip --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 flex items-start gap-4 hover:shadow-md transition-shadow duration-200">
        <div class="w-11 h-11 rounded-xl bg-primary/10 flex items-center justify-center shrink-0">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#1B3A5C" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        </div>
        <div>
            <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wider">Total Arsip</p>
            <p class="text-3xl font-extrabold text-slate-900 mt-1">{{ number_format($totalArsip) }}</p>
        </div>
    </div>
    {{-- Arsip Aktif --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 flex items-start gap-4 hover:shadow-md transition-shadow duration-200">
        <div class="w-11 h-11 rounded-xl bg-emerald-50 flex items-center justify-center shrink-0">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
        </div>
        <div>
            <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wider">Arsip Aktif</p>
            <p class="text-3xl font-extrabold text-slate-900 mt-1">{{ number_format($arsipAktif) }}</p>
        </div>
    </div>
    {{-- Sedang Dipinjam --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 flex items-start gap-4 hover:shadow-md transition-shadow duration-200">
        <div class="w-11 h-11 rounded-xl bg-amber-50 flex items-center justify-center shrink-0">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#D97706" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
        </div>
        <div>
            <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wider">Sedang Dipinjam</p>
            <p class="text-3xl font-extrabold text-slate-900 mt-1">{{ number_format($arsipDipinjam) }}</p>
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
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">No. Berkas</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Judul</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Tanggal</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Umur</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wide">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentArsip->take(4) as $arsip)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-5 py-4 font-semibold text-primary whitespace-nowrap text-sm">{{ $arsip->no_berkas }}</td>
                        <td class="px-5 py-4">
                            <p class="font-semibold text-slate-800 text-sm">{{ Str::limit($arsip->uraian_berkas, 40) }}</p>
                        </td>
                        <td class="px-5 py-4 text-slate-500 whitespace-nowrap text-sm">{{ $arsip->tanggal_diarsipkan?->translatedFormat('d M Y') }}</td>
                        <td class="px-5 py-4 text-slate-500 whitespace-nowrap text-sm">
                            {{ $arsip->tanggal_diarsipkan ? $arsip->tanggal_diarsipkan->diffForHumans(['parts' => 1, 'short' => true]) : '-' }}
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center justify-center gap-1">
                                <a href="{{ route('arsip.edit', $arsip) }}" class="p-1.5 rounded-md text-slate-400 hover:bg-slate-100 hover:text-amber-600 transition" title="Edit">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4z"/></svg>
                                </a>
                                <a href="{{ route('arsip.show', $arsip) }}" class="p-1.5 rounded-md text-slate-400 hover:bg-slate-100 hover:text-primary transition" title="Lihat">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-5 py-10 text-center text-slate-400">Tidak ada arsip terbaru.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-3.5 border-t border-slate-100 flex items-center justify-between">
            <p class="text-xs text-slate-400">Menampilkan {{ min(4, $recentArsip->count()) }} dari {{ number_format($totalArsip) }} dokumen</p>
            <div class="flex items-center gap-1">
                <a href="{{ route('arsip.index') }}" class="p-1.5 rounded text-slate-400 hover:text-primary hover:bg-slate-100 transition" title="Sebelumnya">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
                </a>
                <a href="{{ route('arsip.index') }}" class="p-1.5 rounded text-slate-400 hover:text-primary hover:bg-slate-100 transition" title="Selanjutnya">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                </a>
            </div>
        </div>
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
                @forelse($recentArsip->take(2) as $idx => $arsip)
                <div class="relative flex gap-3.5 pl-0">
                    <div class="w-9 h-9 rounded-full shrink-0 flex items-center justify-center z-10 {{ $idx === 0 ? 'bg-primary text-white' : 'bg-amber-100 text-amber-600' }}">
                        @if($idx === 0)
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        @else
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="6" y="6" width="12" height="12" rx="1"/></svg>
                        @endif
                    </div>
                    <div class="min-w-0 flex-1 pt-1">
                        <p class="text-sm font-semibold text-slate-800 leading-snug">Operator menambahkan arsip {{ $arsip->no_berkas }}</p>
                        <p class="text-xs text-slate-400 mt-1">{{ $arsip->created_at?->diffForHumans() ?? '-' }}</p>
                    </div>
                </div>
                @empty
                @endforelse

                @forelse($recentSuratMasuk->take(2) as $idx => $surat)
                <div class="relative flex gap-3.5 pl-0">
                    <div class="w-9 h-9 rounded-full shrink-0 flex items-center justify-center z-10 {{ $idx === 0 ? 'bg-sky-100 text-sky-600' : 'bg-violet-100 text-violet-600' }}">
                        @if($idx === 0)
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4z"/></svg>
                        @else
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        @endif
                    </div>
                    <div class="min-w-0 flex-1 pt-1">
                        <p class="text-sm font-semibold text-slate-800 leading-snug">{{ $surat->perihal }}</p>
                        <p class="text-xs text-slate-400 mt-1">{{ $surat->created_at?->diffForHumans() ?? '-' }}</p>
                    </div>
                </div>
                @empty
                @endforelse

                @if($recentArsip->isEmpty() && $recentSuratMasuk->isEmpty())
                <div class="text-center py-6">
                    <p class="text-sm text-slate-400">Belum ada aktivitas terbaru.</p>
                </div>
                @endif
            </div>
        </div>

        <a href="{{ route('arsip.index') }}" class="mt-6 flex items-center justify-center w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 hover:border-slate-300 transition">
            Lihat Semua Aktivitas
        </a>
    </div>
</div>

@else
{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- ADMIN DASHBOARD (unchanged) --}}
{{-- ═══════════════════════════════════════════════════════════ --}}

<div class="grid gap-6 mb-6">
    <div class="relative overflow-hidden rounded-[2rem] bg-gradient-to-br from-slate-900 via-slate-900 to-slate-800 text-white shadow-2xl">
        <div class="absolute inset-0 opacity-20 bg-[radial-gradient(circle_at_top_left,_rgba(59,130,246,0.35),_transparent_25%)]"></div>
        <div class="absolute -right-8 top-10 h-48 w-48 rounded-full bg-slate-700/40 blur-3xl"></div>
        <div class="relative p-8 sm:p-10 lg:p-12">
            <p class="text-sm uppercase tracking-[0.28em] text-sky-300/80 font-semibold mb-3">Ringkasan Bidang</p>
            <h1 class="text-3xl sm:text-4xl font-extrabold leading-tight">{{ auth()->user()->bidang->nama_bidang ?? 'Administrator' }}</h1>
            <p class="mt-3 max-w-2xl text-slate-300 text-sm sm:text-base">Kelola arsip strategis untuk unit kerja Anda dengan data ringkas terkini, status fisik, dan aktivitas harian.</p>

            <div class="mt-8 grid gap-4 sm:grid-cols-3">
                <div class="rounded-[1.75rem] bg-slate-950/60 border border-white/10 p-5">
                    <p class="text-xs uppercase tracking-[0.23em] text-slate-400">Total Arsip</p>
                    <p class="mt-3 text-3xl font-bold">{{ number_format($totalArsip) }}</p>
                </div>
                <div class="rounded-[1.75rem] bg-slate-950/60 border border-white/10 p-5">
                    <p class="text-xs uppercase tracking-[0.23em] text-slate-400">Arsip Aktif</p>
                    <p class="mt-3 text-3xl font-bold">{{ number_format($arsipAktif) }}</p>
                </div>
                <div class="rounded-[1.75rem] bg-slate-950/60 border border-white/10 p-5">
                    <p class="text-xs uppercase tracking-[0.23em] text-slate-400">Sedang Dipinjam</p>
                    <p class="mt-3 text-3xl font-bold">{{ number_format($arsipDipinjam) }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-[1.7fr_1fr]">
        <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 px-6 py-6 border-b border-slate-200">
                <div>
                    <p class="text-xs uppercase tracking-[0.22em] text-slate-500">Arsip Terbaru Bidang {{ auth()->user()->bidang->nama_bidang ?? '' }}</p>
                    <h2 class="text-xl font-semibold text-slate-900 mt-2">Daftar Arsip Baru</h2>
                </div>
                <a href="{{ route('arsip.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-full bg-primary text-white hover:bg-primary-light transition">Tambah Arsip</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-950 text-white text-xs uppercase tracking-wide">
                            <th class="px-5 py-3 text-left">No. Berkas</th>
                            <th class="px-5 py-3 text-left">Judul & Perihal</th>
                            <th class="px-5 py-3 text-left">Tanggal</th>
                            <th class="px-5 py-3 text-left">Lokasi Fisik</th>
                            <th class="px-5 py-3 text-left">Umur Arsip</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($recentArsip->take(4) as $arsip)
                        <tr class="hover:bg-slate-50">
                            <td class="px-5 py-4 font-semibold text-primary">{{ $arsip->no_berkas }}</td>
                            <td class="px-5 py-4">
                                <p class="font-semibold text-slate-800">{{ $arsip->uraian_berkas }}</p>
                                <p class="text-xs text-slate-400 mt-1 truncate max-w-[260px]">{{ Str::limit($arsip->uraian_arsip, 80) }}</p>
                            </td>
                            <td class="px-5 py-4 text-slate-600 whitespace-nowrap">{{ $arsip->tanggal_diarsipkan?->translatedFormat('d M Y') }}</td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-slate-100 text-slate-600 text-xs">Rak {{ $arsip->no_rak ?: '-' }}</span>
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-slate-100 text-slate-600 text-xs">Boks {{ $arsip->no_boks ?: '-' }}</span>
                            </td>
                            <td class="px-5 py-4 text-slate-600 whitespace-nowrap">{{ $arsip->tanggal_diarsipkan ? $arsip->tanggal_diarsipkan->diffForHumans(['parts' => 1, 'short' => true]) : '-' }}</td>
                            <td class="px-5 py-4 text-right">
                                <a href="{{ route('arsip.show', $arsip) }}" class="inline-flex items-center gap-1 px-3 py-2 text-xs font-semibold rounded-full bg-slate-100 text-slate-700 hover:bg-slate-200 transition">Detail</a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="px-5 py-10 text-center text-slate-400">Tidak ada arsip terbaru.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-slate-100 text-xs text-slate-500">Menampilkan 4 dari {{ number_format($recentArsip->count()) }} arsip terbaru bidang Anda.</div>
        </div>

        <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm p-6">
            <div class="flex items-center justify-between gap-4 mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Aktivitas Terakhir</h3>
                    <p class="text-sm text-slate-500 mt-1">Kegiatan surat masuk dan pembaruan arsip terbaru.</p>
                </div>
            </div>
            <div class="space-y-4">
                @forelse($recentSuratMasuk as $surat)
                <div class="rounded-3xl border border-slate-200 p-4">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-3xl bg-primary/10 text-primary grid place-items-center font-bold">{{ strtoupper(substr($surat->bidang->kode_bidang ?? 'NA', 0, 2)) }}</div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-slate-900">{{ $surat->perihal }}</p>
                            <p class="text-xs text-slate-400 mt-1">{{ $surat->bidang->nama_bidang ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center justify-between gap-2 text-xs text-slate-500">
                        <span>{{ $surat->tanggal_diterima?->translatedFormat('d M Y') }}</span>
                        <span class="px-2 py-1 rounded-full bg-emerald-100 text-emerald-700">{{ ucfirst($surat->status) }}</span>
                    </div>
                </div>
                @empty
                <div class="rounded-3xl border border-slate-200 p-6 text-center text-slate-500">Belum ada aktivitas terbaru.</div>
                @endforelse
            </div>
            <a href="{{ route('surat-masuk.index') }}" class="mt-6 inline-flex w-full items-center justify-center rounded-full bg-primary px-4 py-3 text-sm font-semibold text-white hover:bg-primary-light transition">Lihat Semua Aktivitas</a>
        </div>
    </div>
</div>

@push('scripts')
<script>
const growthCtx = document.getElementById('growthChart');
if (growthCtx) {
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
}
</script>
@endpush

@endif
@endsection
