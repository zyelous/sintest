@extends('layouts.app')
@section('title', 'Beranda')
@section('breadcrumb')
    <span class="text-slate-700 font-medium">Beranda</span>
@endsection

@section('content')

{{-- Hero Banner --}}
<div class="grid gap-6 mb-6">
    <div class="relative overflow-hidden rounded-[2rem] bg-gradient-to-br from-slate-900 via-slate-900 to-slate-800 text-white shadow-2xl">
        <div class="absolute inset-0 opacity-20 bg-[radial-gradient(circle_at_top_left,_rgba(59,130,246,0.35),_transparent_25%)]"></div>
        <div class="absolute -right-8 top-10 h-48 w-48 rounded-full bg-slate-700/40 blur-3xl"></div>
        <div class="relative p-8 sm:p-10 lg:p-12">
            <p class="text-sm uppercase tracking-[0.28em] text-sky-300/80 font-semibold mb-3">Ringkasan Bidang</p>
            <h1 class="text-3xl sm:text-4xl font-extrabold leading-tight">
                {{ auth()->user()->bidang->nama_bidang ?? 'Bidang Saya' }}
            </h1>
            <p class="mt-3 max-w-2xl text-slate-300 text-sm sm:text-base">
                Selamat datang, <span class="font-semibold text-white">{{ auth()->user()->name }}</span>.
                Kelola arsip dan peminjaman bidang Anda dengan data terkini.
            </p>

            <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-[1.5rem] bg-slate-950/60 border border-white/10 p-4 sm:p-5">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-400">Total Arsip</p>
                    <p class="mt-2 text-2xl sm:text-3xl font-bold">{{ number_format($totalArsip) }}</p>
                </div>
                <div class="rounded-[1.5rem] bg-slate-950/60 border border-white/10 p-4 sm:p-5">
                    <p class="text-xs uppercase tracking-[0.2em] text-emerald-400">Aktif / Inaktif</p>
                    <p class="mt-2 text-2xl sm:text-3xl font-bold">
                        {{ number_format($arsipAktif) }}
                        <span class="text-sm font-normal text-slate-400">/ {{ number_format($arsipInaktif) }}</span>
                    </p>
                </div>
                <div class="rounded-[1.5rem] bg-slate-950/60 border border-white/10 p-4 sm:p-5">
                    <p class="text-xs uppercase tracking-[0.2em] text-amber-400">Sedang Dipinjam</p>
                    <p class="mt-2 text-2xl sm:text-3xl font-bold">{{ number_format($arsipDipinjam) }}</p>
                </div>
                <div class="rounded-[1.5rem] bg-slate-950/60 border border-white/10 p-4 sm:p-5">
                    <p class="text-xs uppercase tracking-[0.2em] text-red-400">Pinjaman Terlambat</p>
                    <p class="mt-2 text-2xl sm:text-3xl font-bold {{ $peminjamanTerlambat > 0 ? 'text-red-400' : 'text-white' }}">
                        {{ number_format($peminjamanTerlambat) }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Two Column Layout: Chart + Activity --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    {{-- Chart --}}
    <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 shadow-sm p-5">
        <h3 class="font-semibold text-slate-800 mb-1">Arsip Masuk 6 Bulan Terakhir</h3>
        <p class="text-xs text-slate-400 mb-4">Analitik bulanan penambahan arsip di bidang Anda.</p>
        <canvas id="growthChart" height="90"></canvas>
    </div>

    {{-- Peminjaman summary --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
        <h3 class="font-semibold text-slate-800 mb-4">Status Peminjaman</h3>
        <div class="space-y-3">
            <div class="flex items-center justify-between text-sm">
                <span class="text-slate-500">Menunggu Persetujuan</span>
                <span class="font-semibold text-amber-600">{{ number_format($totalMenunggu) }}</span>
            </div>
            <div class="flex items-center justify-between text-sm">
                <span class="text-slate-500">Sedang Dipinjam</span>
                <span class="font-semibold text-primary">{{ number_format($totalDipinjamP) }}</span>
            </div>
            <div class="flex items-center justify-between text-sm">
                <span class="text-slate-500">Terlambat</span>
                <span class="font-semibold text-red-600">{{ number_format($totalTerlambat) }}</span>
            </div>
        </div>
        <a href="{{ route('operator.peminjaman.index') }}" class="mt-6 flex items-center justify-center w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 hover:border-slate-300 transition">
            Lihat Semua Peminjaman
        </a>
    </div>
</div>

{{-- Recent peminjaman --}}
<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
        <h3 class="font-semibold text-slate-800">Peminjaman Terbaru</h3>
        <a href="{{ route('operator.peminjaman.create') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-full bg-primary text-white hover:bg-primary-light transition">Pinjam Arsip</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-primary text-white text-xs uppercase tracking-wide">
                    <th class="px-5 py-3 text-left font-semibold">Peminjam</th>
                    <th class="px-5 py-3 text-left font-semibold">Arsip</th>
                    <th class="px-5 py-3 text-left font-semibold">Status</th>
                    <th class="px-5 py-3 text-left font-semibold">Tgl Pinjam</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($recentPeminjaman as $p)
                <tr class="hover:bg-slate-50">
                    <td class="px-5 py-3.5 text-slate-600">{{ $p->nama_peminjam }}</td>
                    <td class="px-5 py-3.5 text-primary font-medium">{{ $p->arsip->no_berkas ?? '-' }}</td>
                    <td class="px-5 py-3.5">
                        @if($p->status === 'menunggu_persetujuan')
                            <span class="px-2 py-1 rounded-full text-[0.7rem] font-semibold bg-amber-100 text-amber-700">Menunggu Persetujuan</span>
                        @elseif($p->status === 'ditolak')
                            <span class="px-2 py-1 rounded-full text-[0.7rem] font-semibold bg-slate-200 text-slate-600">Ditolak</span>
                        @elseif($p->status === 'dikembalikan')
                            <span class="px-2 py-1 rounded-full text-[0.7rem] font-semibold bg-sky-100 text-sky-700">Dikembalikan</span>
                        @else
                            <span class="px-2 py-1 rounded-full text-[0.7rem] font-semibold bg-emerald-100 text-emerald-700">Dipinjam</span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5 text-slate-500">{{ $p->tanggal_pinjam?->translatedFormat('d M Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-5 py-8 text-center text-slate-400">Belum ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
new Chart(document.getElementById('growthChart'), {
    type: 'bar',
    data: {
        labels: {!! json_encode(array_column($monthlyGrowth, 'label')) !!},
        datasets: [{
            label: 'Arsip',
            data: {!! json_encode(array_column($monthlyGrowth, 'count')) !!},
            backgroundColor: '#1B3A5C',
            borderRadius: 6,
            maxBarThickness: 48,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
    }
});
</script>
@endpush
@endsection