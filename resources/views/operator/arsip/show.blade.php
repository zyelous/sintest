@extends('layouts.app')
@section('title', $arsip->uraian_berkas)
@section('content')

<div class="flex items-start justify-between mb-6 flex-wrap gap-3">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">{{ $arsip->uraian_berkas }}</h1>
        <p class="text-xs text-slate-500 mt-1">Kode: <span class="font-bold text-primary">{{ $arsip->kode_klasifikasi }}</span> | No Berkas: <span class="font-bold text-slate-700">{{ $arsip->no_berkas }}</span></p>
    </div>
    <div class="flex items-center gap-2 flex-wrap">
        <a href="{{ route('operator.arsip.index') }}" class="inline-flex items-center gap-2 px-3.5 py-2 text-sm font-semibold rounded-lg border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 transition shadow-sm">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            Kembali
        </a>
        <a href="{{ route('operator.arsip.print-label', $arsip) }}" target="_blank" class="inline-flex items-center gap-2 px-3.5 py-2 text-sm font-semibold rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 transition shadow-sm">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
            Cetak Label Stiker
        </a>
        <a href="{{ route('operator.arsip.edit', $arsip) }}" class="inline-flex items-center gap-2 px-3.5 py-2 text-sm font-semibold rounded-lg bg-accent-gold text-primary-dark hover:bg-accent-gold-dark transition shadow-sm">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4z"/></svg>
            Edit Data
        </a>
        @if($arsip->file_arsip)
        <button onclick="openPreviewModal()" class="inline-flex items-center gap-2 px-3.5 py-2 text-sm font-semibold rounded-lg bg-slate-800 text-white hover:bg-slate-900 transition shadow-sm">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            Pratinjau Berkas
        </button>
        <a href="{{ route('operator.arsip.download', $arsip) }}" class="inline-flex items-center gap-2 px-3.5 py-2 text-sm font-semibold rounded-lg bg-primary text-white hover:bg-primary-light transition shadow-sm">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            Unduh
        </a>
        @endif
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center gap-2">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#1B3A5C" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                <h3 class="font-semibold text-slate-800 text-sm uppercase tracking-wide">Informasi Utama Dokumen</h3>
            </div>
            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide">Nomor Berkas</p>
                    <p class="font-bold text-primary mt-1">{{ $arsip->no_berkas }}</p>
                </div>
                <div>
                    <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide">Tanggal Diarsipkan</p>
                    <p class="font-bold text-slate-800 mt-1">{{ $arsip->tanggal_diarsipkan?->translatedFormat('d F Y') }}</p>
                </div>
                <div class="sm:col-span-2">
                    <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide">Judul / Uraian Berkas</p>
                    <p class="font-semibold text-slate-800 mt-1">{{ $arsip->uraian_berkas }}</p>
                    @if($arsip->uraian_arsip)<p class="text-sm text-slate-500 mt-1">{{ $arsip->uraian_arsip }}</p>@endif
                </div>
                <div>
                    <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide">Kode Klasifikasi</p>
                    <span class="inline-block mt-1.5 px-3 py-1 rounded-full bg-primary/10 text-primary text-xs font-bold">{{ $arsip->kode_klasifikasi }}</span>
                </div>
                <div>
                    <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide">Bidang Pengolah</p>
                    <p class="font-semibold text-slate-800 mt-1">{{ $arsip->bidang->nama_bidang ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide">Kurun Waktu</p>
                    <p class="font-semibold text-slate-800 mt-1">{{ $arsip->kurun_waktu ?: '-' }}</p>
                </div>
                <div>
                    <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide">Jumlah Halaman / Bundle</p>
                    <p class="font-semibold text-slate-800 mt-1">{{ $arsip->jumlah_halaman_bundle ? $arsip->jumlah_halaman_bundle . ' lembar/hal' : '-' }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                <div class="flex items-center gap-2 mb-4">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#1B3A5C" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    <h3 class="font-semibold text-slate-800 text-sm uppercase tracking-wide">Keamanan & Retensi</h3>
                </div>
                <div class="space-y-3">
                    <div>
                        <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide">Tingkat Keamanan</p>
                        @php
                            $secColor = match($arsip->klasifikasi_keamanan) {
                                'sangat_rahasia' => 'bg-red-50 text-red-600',
                                'rahasia' => 'bg-orange-50 text-orange-600',
                                'terbatas' => 'bg-amber-50 text-amber-600',
                                default => 'bg-emerald-50 text-emerald-600',
                            };
                        @endphp
                        <span class="inline-flex items-center gap-1.5 mt-1.5 px-3 py-1 rounded-lg text-xs font-bold {{ $secColor }}">
                            {{ ucwords(str_replace('_', ' ', $arsip->klasifikasi_keamanan)) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide">Status Retensi</p>
                        <span class="inline-block mt-1.5 px-3 py-1 rounded-full text-xs font-semibold {{ $arsip->status_retensi === 'aktif' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-700' }}">
                            {{ ucfirst($arsip->status_retensi) }} {{ $arsip->nasib_akhir ? '('.$arsip->nasib_akhir.')' : '' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                <div class="flex items-center gap-2 mb-4">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#1B3A5C" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    <h3 class="font-semibold text-slate-800 text-sm uppercase tracking-wide">Lokasi Fisik Simpan</h3>
                </div>
                <div class="grid grid-cols-3 gap-2 text-center">
                    <div class="bg-slate-50 rounded-lg p-2.5">
                        <span class="text-[0.65rem] font-semibold text-slate-400 uppercase block">No Rak</span>
                        <span class="font-bold text-slate-800 text-base">{{ $arsip->no_rak ?: '-' }}</span>
                    </div>
                    <div class="bg-slate-50 rounded-lg p-2.5">
                        <span class="text-[0.65rem] font-semibold text-slate-400 uppercase block">No Boks</span>
                        <span class="font-bold text-slate-800 text-base">{{ $arsip->no_boks ?: '-' }}</span>
                    </div>
                    <div class="bg-slate-50 rounded-lg p-2.5">
                        <span class="text-[0.65rem] font-semibold text-slate-400 uppercase block">Folder</span>
                        <span class="font-bold text-slate-800 text-base">{{ $arsip->no_folder ?: '-' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 text-center">
            <div class="w-full aspect-[4/3] rounded-lg bg-gradient-to-br from-primary/10 to-primary/5 flex flex-col items-center justify-center mb-3 p-4">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#1B3A5C" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                <p class="text-xs font-bold text-primary mt-2">{{ $arsip->file_arsip ? 'Berkas Digital Tersedia' : 'Belum Ada Berkas Digital' }}</p>
            </div>
            <p class="text-xs font-semibold text-slate-700 truncate">{{ $arsip->file_arsip ? basename($arsip->file_arsip) : '-' }}</p>
            <p class="text-[0.7rem] text-slate-400 mt-0.5">Diunggah {{ $arsip->created_at?->translatedFormat('d/m/Y H:i') }}</p>
            @if($arsip->file_arsip)
            <div class="mt-4 flex gap-2">
                <button onclick="openPreviewModal()" class="flex-1 py-2 text-xs font-semibold rounded-lg bg-slate-800 text-white hover:bg-slate-900 transition">Pratinjau</button>
                <a href="{{ route('operator.arsip.download', $arsip) }}" class="flex-1 py-2 text-xs font-semibold rounded-lg bg-primary text-white hover:bg-primary-light transition">Unduh</a>
            </div>
            @endif
        </div>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <h3 class="font-semibold text-slate-800 text-sm mb-4">Log & Status Peminjaman</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between text-xs">
                    <span class="text-slate-500">Status Fisik Arsip:</span>
                    <span class="px-2.5 py-0.5 rounded-full font-bold {{ $arsip->status_arsip === 'dipinjam' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800' }}">
                        {{ strtoupper($arsip->status_arsip) }}
                    </span>
                </div>
                <div class="flex items-center justify-between text-xs">
                    <span class="text-slate-500">Penginput:</span>
                    <span class="font-semibold text-slate-700">{{ $arsip->user->name ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

@if($arsip->file_arsip)
{{-- Preview Modal --}}
<div id="previewModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-[9999] hidden items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl h-[85vh] flex flex-col">
        <div class="flex items-center justify-between px-5 py-3 border-b border-slate-200 bg-slate-800 text-white rounded-t-xl">
            <h3 class="text-sm font-bold truncate">Pratinjau: {{ basename($arsip->file_arsip) }}</h3>
            <button onclick="closePreviewModal()" class="text-slate-300 hover:text-white text-2xl leading-none">&times;</button>
        </div>
        <div class="flex-1 bg-slate-100 p-2 overflow-hidden flex items-center justify-center">
            @php
                $ext = strtolower(pathinfo($arsip->file_arsip, PATHINFO_EXTENSION));
                $fileUrl = asset('storage/' . $arsip->file_arsip);
            @endphp
            @if(in_array($ext, ['jpg', 'jpeg', 'png']))
                <img src="{{ $fileUrl }}" alt="File Preview" class="max-h-full max-w-full object-contain rounded shadow">
            @elseif($ext === 'pdf')
                <iframe src="{{ $fileUrl }}" class="w-full h-full rounded border-0"></iframe>
            @else
                <div class="text-center p-6 bg-white rounded-xl shadow-sm">
                    <p class="text-sm font-bold text-slate-700">Format file ({{ strtoupper($ext) }}) tidak dapat ditampilkan secara langsung.</p>
                    <p class="text-xs text-slate-500 mt-1 mb-4">Silakan unduh file untuk membukanya di komputer Anda.</p>
                    <a href="{{ route('operator.arsip.download', $arsip) }}" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold rounded-lg bg-primary text-white">Unduh File</a>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function openPreviewModal() {
    document.getElementById('previewModal').classList.remove('hidden');
    document.getElementById('previewModal').classList.add('flex');
}
function closePreviewModal() {
    document.getElementById('previewModal').classList.add('hidden');
    document.getElementById('previewModal').classList.remove('flex');
}
</script>
@endif

@endsection
