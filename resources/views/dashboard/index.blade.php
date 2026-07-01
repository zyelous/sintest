@extends('layouts.app')
@section('title', 'Beranda')
@section('content')

<div class="flex items-center justify-between mb-6 flex-wrap gap-3">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">{{ auth()->user()->isAdmin() ? 'Ringkasan Administrator' : 'Ringkasan Bidang' }}</h1>
        <p class="text-sm text-slate-500 mt-1">{{ auth()->user()->isAdmin() ? 'Data agregat seluruh departemen di lingkup Bappeda Provinsi Lampung.' : 'Data arsip untuk bidang ' . (auth()->user()->bidang->nama_bidang ?? '-') . '.' }}</p>
    </div>
    @if(auth()->user()->isAdmin())
    <form action="{{ route('arsip.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
        @csrf
        <input type="file" name="file" id="importFile" accept=".csv,.xls,.xlsx" class="hidden" onchange="document.getElementById('importForm').submit()">
        <button type="button" onclick="document.getElementById('importFile').click()" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-lg border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 transition shadow-sm">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            Excel Import
        </button>
    </form>
    @endif
</div>

{{-- Stat cards --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
        <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center mb-3">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#1B3A5C" stroke-width="2"><path d="M20 8v11a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V8"/><path d="M22 8H2l1.5-4h17z"/></svg>
        </div>
        <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide">Total Arsip</p>
        <p class="text-3xl font-bold text-slate-800 mt-0.5">{{ number_format($totalArsip) }}</p>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
        <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center mb-3">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#1B3A5C" stroke-width="2"><rect x="4" y="4" width="16" height="4" rx="1"/><rect x="4" y="10" width="16" height="10" rx="1"/><line x1="8" y1="14" x2="16" y2="14"/></svg>
        </div>
        <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide">{{ auth()->user()->isAdmin() ? 'Bidang' : 'Surat Masuk' }}</p>
        <p class="text-3xl font-bold text-slate-800 mt-0.5">{{ auth()->user()->isAdmin() ? number_format($arsipPerBidang->count()) : number_format($totalSuratMasuk) }}</p>
    </div>
    <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
        <div class="w-10 h-10 rounded-lg bg-red-50 flex items-center justify-center mb-3">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#EF4444" stroke-width="2"><rect x="3" y="7" width="18" height="13" rx="1"/><path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
        </div>
        <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide">Jumlah Boks</p>
        <p class="text-3xl font-bold text-slate-800 mt-0.5">{{ number_format($totalBoks) }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    {{-- Chart --}}
    <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
        <div class="flex items-center justify-between mb-1">
            <h3 class="font-semibold text-slate-800">Pertumbuhan Arsip</h3>
        </div>
        <p class="text-xs text-slate-400 mb-4">Analitik bulanan penambahan arsip fisik dan digital.</p>
        <canvas id="growthChart" height="90"></canvas>
    </div>

    {{-- Sebaran / status --}}
    <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
        <h3 class="font-semibold text-slate-800 mb-1">{{ auth()->user()->isAdmin() ? 'Sebaran Departemen' : 'Status Arsip' }}</h3>
        <p class="text-xs text-slate-400 mb-4">{{ auth()->user()->isAdmin() ? 'Distribusi volume arsip per bidang.' : 'Ringkasan status arsip bidang Anda.' }}</p>

        @if(auth()->user()->isAdmin())
            @php $maxCount = $arsipPerBidang->max('arsip_count') ?: 1; @endphp
            <div class="space-y-4">
                @foreach($arsipPerBidang->sortByDesc('arsip_count')->take(4) as $b)
                <div>
                    <div class="flex items-center justify-between text-xs mb-1.5">
                        <span class="font-medium text-slate-600">{{ $b->nama_bidang }}</span>
                        <span class="text-slate-400">{{ number_format($b->arsip_count) }} items</span>
                    </div>
                    <div class="h-1.5 bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-primary rounded-full" style="width: {{ $b->arsip_count > 0 ? max(4, round($b->arsip_count / $maxCount * 100)) : 0 }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
            <a href="{{ route('bidang.index') }}" class="mt-5 block text-center text-sm font-semibold text-primary bg-primary/5 hover:bg-primary/10 rounded-lg py-2.5 transition">Lihat Selengkapnya</a>
        @else
            <div class="space-y-4">
                <div class="flex items-center justify-between text-sm"><span class="text-slate-500">Arsip Aktif</span><span class="font-semibold text-slate-800">{{ number_format($arsipAktif) }}</span></div>
                <div class="flex items-center justify-between text-sm"><span class="text-slate-500">Sedang Dipinjam</span><span class="font-semibold text-slate-800">{{ number_format($arsipDipinjam) }}</span></div>
                <div class="flex items-center justify-between text-sm"><span class="text-slate-500">Surat Keluar</span><span class="font-semibold text-slate-800">{{ number_format($totalSuratKeluar) }}</span></div>
            </div>
        @endif
    </div>
</div>

{{-- Log / recent activity --}}
<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-slate-100">
        <h3 class="font-semibold text-slate-800">{{ auth()->user()->isAdmin() ? 'Log Aktivitas Sistem' : 'Surat Masuk Terbaru' }}</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-primary text-white text-xs uppercase tracking-wide">
                    @if(auth()->user()->isAdmin())<th class="px-5 py-3 text-left font-semibold">Bidang</th>@endif
                    <th class="px-5 py-3 text-left font-semibold">{{ auth()->user()->isAdmin() ? 'Aksi' : 'Perihal' }}</th>
                    <th class="px-5 py-3 text-left font-semibold">Entitas</th>
                    <th class="px-5 py-3 text-left font-semibold">Status</th>
                    <th class="px-5 py-3 text-left font-semibold">Waktu</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($recentSuratMasuk as $s)
                <tr class="hover:bg-slate-50">
                    @if(auth()->user()->isAdmin())
                    <td class="px-5 py-3.5">
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-md bg-primary/10 text-primary text-[0.65rem] font-bold">{{ strtoupper(substr($s->bidang->kode_bidang ?? 'NA', 0, 2)) }}</span>
                        <span class="ml-1 font-medium text-slate-700">{{ $s->bidang->nama_bidang ?? '-' }}</span>
                    </td>
                    @endif
                    <td class="px-5 py-3.5 text-slate-600">{{ $s->perihal }}</td>
                    <td class="px-5 py-3.5 text-primary font-medium">{{ $s->nomor_surat }}</td>
                    <td class="px-5 py-3.5"><span class="px-2 py-1 rounded-full text-[0.7rem] font-semibold bg-emerald-100 text-emerald-700">{{ ucfirst($s->status) }}</span></td>
                    <td class="px-5 py-3.5 text-slate-500">{{ $s->tanggal_diterima?->translatedFormat('d M Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-5 py-8 text-center text-slate-400">Belum ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

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
</script>
@endpush
@endsection
