@extends('layouts.app')
@section('title', 'Dashboard')
@section('breadcrumb')<span class="text-slate-800 font-semibold">Dashboard</span>@endsection

@section('content')
<div class="flex items-center justify-between mb-6 flex-wrap gap-3">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Dashboard</h1>
        <p class="text-sm text-slate-500">Selamat datang, {{ auth()->user()->name }}!</p>
    </div>
</div>

{{-- Stats --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-5 mb-7">
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-5 flex items-center gap-4 border-l-4 border-l-primary hover:shadow-md hover:-translate-y-0.5 transition-all">
        <div class="w-11 h-11 rounded-lg bg-primary/10 flex items-center justify-center shrink-0"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#1B3A5C" stroke-width="2"><path d="M21 8v13H3V8"/><path d="M1 3h22v5H1z"/><path d="M10 12h4"/></svg></div>
        <div><p class="text-2xl font-extrabold text-slate-800 leading-none">{{ number_format($totalArsip) }}</p><p class="text-xs text-slate-500 mt-1">Total Arsip</p></div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-5 flex items-center gap-4 border-l-4 border-l-emerald-500 hover:shadow-md hover:-translate-y-0.5 transition-all">
        <div class="w-11 h-11 rounded-lg bg-emerald-50 flex items-center justify-center shrink-0"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg></div>
        <div><p class="text-2xl font-extrabold text-slate-800 leading-none">{{ number_format($arsipAktif) }}</p><p class="text-xs text-slate-500 mt-1">Arsip Aktif</p></div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-5 flex items-center gap-4 border-l-4 border-l-amber-500 hover:shadow-md hover:-translate-y-0.5 transition-all">
        <div class="w-11 h-11 rounded-lg bg-amber-50 flex items-center justify-center shrink-0"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#F59E0B" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
        <div><p class="text-2xl font-extrabold text-slate-800 leading-none">{{ number_format($arsipDipinjam) }}</p><p class="text-xs text-slate-500 mt-1">Dipinjam</p></div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-5 flex items-center gap-4 border-l-4 border-l-cyan-500 hover:shadow-md hover:-translate-y-0.5 transition-all">
        <div class="w-11 h-11 rounded-lg bg-cyan-50 flex items-center justify-center shrink-0"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#06B6D4" stroke-width="2"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/></svg></div>
        <div><p class="text-2xl font-extrabold text-slate-800 leading-none">{{ number_format($totalSuratMasuk) }}</p><p class="text-xs text-slate-500 mt-1">Surat Masuk</p></div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-5 flex items-center gap-4 border-l-4 border-l-blue-500 hover:shadow-md hover:-translate-y-0.5 transition-all">
        <div class="w-11 h-11 rounded-lg bg-blue-50 flex items-center justify-center shrink-0"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#3B82F6" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg></div>
        <div><p class="text-2xl font-extrabold text-slate-800 leading-none">{{ number_format($totalSuratKeluar) }}</p><p class="text-xs text-slate-500 mt-1">Surat Keluar</p></div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-5 flex items-center gap-4 border-l-4 border-l-red-500 hover:shadow-md hover:-translate-y-0.5 transition-all">
        <div class="w-11 h-11 rounded-lg bg-red-50 flex items-center justify-center shrink-0"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#EF4444" stroke-width="2"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1" ry="1"/></svg></div>
        <div><p class="text-2xl font-extrabold text-slate-800 leading-none">{{ number_format($totalPeminjaman) }}</p><p class="text-xs text-slate-500 mt-1">Pinjaman Aktif</p></div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    @if($arsipPerBidang)
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100"><h3 class="font-semibold text-slate-800">Arsip per Bidang</h3></div>
        <div class="p-6"><canvas id="arsipChart" height="250"></canvas></div>
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <h3 class="font-semibold text-slate-800">Peminjaman Aktif</h3>
            <a href="{{ route('peminjaman.index') }}" class="text-xs font-semibold text-blue-500 hover:text-blue-700">Lihat Semua →</a>
        </div>
        <div class="p-0">
            @if($recentPeminjaman->isEmpty())
                <p class="text-sm text-slate-400 text-center py-8">Tidak ada peminjaman aktif.</p>
            @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead><tr><th class="th-sintara">Arsip</th><th class="th-sintara">Peminjam</th><th class="th-sintara">Tgl Pinjam</th><th class="th-sintara">Status</th></tr></thead>
                    <tbody>
                        @foreach($recentPeminjaman as $p)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="td-sintara font-medium">{{ $p->arsip->kode_klasifikasi }} - {{ $p->arsip->no_berkas }}</td>
                            <td class="td-sintara">{{ $p->nama_peminjam }}</td>
                            <td class="td-sintara">{{ $p->tanggal_pinjam->format('d/m/Y') }}</td>
                            <td class="td-sintara">
                                @if($p->tanggal_pinjam->diffInDays(now()) > 14)
                                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-red-100 text-red-700">Terlambat</span>
                                @else
                                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-amber-100 text-amber-700">Dipinjam</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden mt-6">
    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
        <h3 class="font-semibold text-slate-800">Surat Masuk Terbaru</h3>
        <a href="{{ route('surat-masuk.index') }}" class="text-xs font-semibold text-blue-500 hover:text-blue-700">Lihat Semua →</a>
    </div>
    <div class="p-0">
        @if($recentSuratMasuk->isEmpty())
            <p class="text-sm text-slate-400 text-center py-8">Belum ada surat masuk.</p>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr><th class="th-sintara">Nomor Surat</th><th class="th-sintara">Pengirim</th><th class="th-sintara">Tanggal</th><th class="th-sintara">Bidang</th><th class="th-sintara">Perihal</th></tr></thead>
                <tbody>
                    @foreach($recentSuratMasuk as $sm)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="td-sintara font-semibold">{{ $sm->nomor_surat }}</td>
                        <td class="td-sintara">{{ $sm->pengirim }}</td>
                        <td class="td-sintara">{{ $sm->tanggal_surat->format('d/m/Y') }}</td>
                        <td class="td-sintara"><span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-cyan-100 text-cyan-700">{{ $sm->bidang->nama_bidang ?? '-' }}</span></td>
                        <td class="td-sintara">{{ Str::limit($sm->perihal, 50) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
@if($arsipPerBidang)
<script>
const ctx = document.getElementById('arsipChart');
if (ctx) {
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($arsipPerBidang->pluck('nama_bidang')) !!},
            datasets: [{
                label: 'Jumlah Arsip',
                data: {!! json_encode($arsipPerBidang->pluck('arsip_count')) !!},
                backgroundColor: ['#3B82F6','#10B981','#F59E0B','#EF4444','#8B5CF6','#06B6D4'],
                borderRadius: 6,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } }, x: { grid: { display: false } } }
        }
    });
}
</script>
@endif
@endpush
