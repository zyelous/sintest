@extends('layouts.app')
@section('title', 'Detail Peminjaman')
@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="hover:text-primary">Dashboard</a>
    <span>/</span>
    <a href="{{ route('admin.peminjaman.index') }}" class="hover:text-primary">Peminjaman Arsip</a>
    <span>/</span>
    <span class="text-slate-700 font-medium">Detail</span>
@endsection

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-xl font-bold text-slate-800">Detail Peminjaman</h1>
    <a href="{{ route('admin.peminjaman.index') }}" class="px-4 py-2.5 text-sm font-semibold rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-50 transition">Kembali</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100">
            <h3 class="font-semibold text-slate-800">Data Peminjam</h3>
        </div>
        <div class="p-5 space-y-4">
            <div>
                <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide">Nama Peminjam</p>
                <p class="text-sm text-slate-700 mt-0.5">{{ $peminjaman->nama_peminjam }}</p>
            </div>
            <div>
                <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide">Unit Kerja/Bidang Peminjam</p>
                <p class="text-sm text-slate-700 mt-0.5">{{ $peminjaman->bidang_peminjam }}</p>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide">Tanggal Pinjam</p>
                    <p class="text-sm text-slate-700 mt-0.5">{{ $peminjaman->tanggal_pinjam->format('d F Y') }}</p>
                </div>
                <div>
                    <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide">Rencana Kembali</p>
                    <p class="text-sm text-slate-700 mt-0.5">{{ $peminjaman->tanggal_rencana_kembali?->format('d F Y') ?? '-' }}</p>
                </div>
            </div>
            <div>
                <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide">Jumlah</p>
                <p class="text-sm text-slate-700 mt-0.5">{{ $peminjaman->jumlah ?? '-' }}</p>
            </div>
            <div>
                <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide">Tanggal Kembali (Aktual)</p>
                <p class="text-sm text-slate-700 mt-0.5">{{ $peminjaman->tanggal_kembali?->format('d F Y') ?? '-' }}</p>
            </div>
            <div>
                <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide">Status</p>
                <p class="mt-1">
                    @if($peminjaman->status === 'menunggu_persetujuan')
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700">MENUNGGU PERSETUJUAN</span>
                    @elseif($peminjaman->status === 'ditolak')
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-slate-200 text-slate-600">DITOLAK</span>
                    @elseif($peminjaman->status === 'dikembalikan')
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-sky-100 text-sky-700">DIKEMBALIKAN</span>
                    @elseif($peminjaman->terlambat)
                        @php
                            $hariTerlambat = (int) \Carbon\Carbon::now()->diff($peminjaman->tanggal_rencana_kembali)->days;
                        @endphp
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">TERLAMBAT ({{ $hariTerlambat }} hari dari rencana)</span>
                    @else
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">DISETUJUI ({{ $peminjaman->durasi_pinjam }})</span>
                    @endif
                </p>
            </div>
            @if($peminjaman->keterangan)
            <div>
                <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide">Keterangan</p>
                <p class="text-sm text-slate-700 mt-0.5">{{ $peminjaman->keterangan }}</p>
            </div>
            @endif
            <div>
                <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide">Diajukan Oleh</p>
                <p class="text-sm text-slate-700 mt-0.5">{{ $peminjaman->creator->name ?? '-' }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100">
            <h3 class="font-semibold text-slate-800">Data Arsip</h3>
        </div>
        <div class="p-5 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide">Kode Klasifikasi</p>
                    <p class="text-sm font-semibold text-primary mt-0.5">{{ $peminjaman->arsip->kode_klasifikasi }}</p>
                </div>
                <div>
                    <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide">No. Berkas</p>
                    <p class="text-sm text-slate-700 mt-0.5">{{ $peminjaman->arsip->no_berkas }}</p>
                </div>
            </div>
            <div>
                <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide">Uraian</p>
                <p class="text-sm text-slate-700 mt-0.5">{{ $peminjaman->arsip->uraian_berkas }}</p>
            </div>
            <div>
                <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide">Bidang Arsip</p>
                <p class="text-sm text-slate-700 mt-0.5">{{ $peminjaman->arsip->bidang->nama_bidang ?? '-' }}</p>
            </div>
        </div>
    </div>
</div>

{{-- Paraf Digital --}}
<div class="mt-6 bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-slate-100">
        <h3 class="font-semibold text-slate-800">Paraf</h3>
    </div>
    <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div>
            <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide mb-2">Paraf Peminjam</p>
            @if($peminjaman->paraf_peminjam)
                <div class="border border-slate-200 rounded-lg bg-slate-50 p-3 inline-block">
                    <img src="{{ $peminjaman->paraf_peminjam }}" alt="Paraf peminjam" class="h-24 bg-white rounded">
                </div>
            @else
                <p class="text-sm text-slate-400 italic">Belum ada paraf.</p>
            @endif
        </div>
        <div>
            <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide mb-2">Paraf Petugas/Arsiparis</p>
            @if($peminjaman->paraf_petugas)
                <div class="border border-slate-200 rounded-lg bg-slate-50 p-3 inline-block">
                    <img src="{{ $peminjaman->paraf_petugas }}" alt="Paraf petugas" class="h-24 bg-white rounded">
                </div>
            @else
                <p class="text-sm text-slate-400 italic">Belum diparaf. Akan diisi Admin saat menyetujui peminjaman.</p>
            @endif
        </div>
    </div>
</div>

@if($peminjaman->status === 'menunggu_persetujuan')
<div class="mt-6 flex gap-3">
    <button type="button" onclick="openApproveModal()" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold rounded-lg bg-emerald-500 text-white hover:bg-emerald-600 transition shadow-sm">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
        Setujui Peminjaman
    </button>
    <form method="POST" action="{{ route('admin.peminjaman.reject', $peminjaman) }}">
        @csrf @method('PUT')
        <button type="submit" onclick="return confirm('Tolak peminjaman ini?')" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold rounded-lg border border-red-300 text-red-600 hover:bg-red-50 transition">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            Tolak
        </button>
    </form>
</div>

{{-- Modal Approve + Paraf Petugas --}}
<div id="approveModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[9999] hidden items-center justify-center p-6">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200">
            <h3 class="text-base font-semibold text-slate-800">Setujui Peminjaman</h3>
            <button type="button" onclick="closeApproveModal()" class="text-slate-400 hover:text-slate-700 text-2xl leading-none">&times;</button>
        </div>
        <form method="POST" action="{{ route('admin.peminjaman.approve', $peminjaman) }}" id="approveForm">
            @csrf @method('PUT')
            <div class="p-6">
                <p class="text-sm text-slate-600 mb-4">Bubuhkan paraf sebagai Petugas/Arsiparis untuk menyetujui peminjaman ini.</p>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Paraf Petugas/Arsiparis <span class="text-red-500">*</span></label>
                <div class="rounded-xl border-2 border-dashed border-slate-300 bg-slate-50/60 p-2 relative">
                    <canvas id="sigPetugasModal" class="w-full h-40 bg-white rounded-lg touch-none"></canvas>
                    <button type="button" onclick="clearApproveSig()" class="absolute top-3 right-3 w-8 h-8 flex items-center justify-center rounded-lg bg-white border border-slate-200 text-slate-400 hover:text-slate-600 hover:bg-slate-50" title="Hapus paraf">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M8 6V4a1 1 0 011-1h6a1 1 0 011 1v2m3 0-1 14a2 2 0 01-2 2H7a2 2 0 01-2-2L4 6h16z"/></svg>
                    </button>
                </div>
                <input type="hidden" name="paraf_petugas" id="paraf_petugas_modal" value="">
            </div>
            <div class="flex justify-end gap-3 px-6 py-4 border-t border-slate-100">
                <button type="button" onclick="closeApproveModal()" class="px-4 py-2 text-sm font-semibold rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-50 transition">Batal</button>
                <button type="submit" class="px-4 py-2 text-sm font-semibold rounded-lg bg-emerald-500 text-white hover:bg-emerald-600 transition">Setujui</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/signature_pad/4.1.6/signature_pad.umd.min.js"></script>
<script>
    let approveSigPad = null;

    function openApproveModal() {
        const m = document.getElementById('approveModal');
        m.classList.remove('hidden');
        m.classList.add('flex');

        setTimeout(() => {
            const canvas = document.getElementById('sigPetugasModal');
            if (!canvas) return;
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            const rect = canvas.getBoundingClientRect();
            if (rect.width > 0 && rect.height > 0) {
                canvas.width = rect.width * ratio;
                canvas.height = rect.height * ratio;
                canvas.getContext('2d').scale(ratio, ratio);
            }
            if (!approveSigPad) {
                approveSigPad = new SignaturePad(canvas, {
                    backgroundColor: 'rgb(255,255,255)',
                    penColor: 'rgb(15,23,42)'
                });
            }
        }, 50);
    }

    function closeApproveModal() {
        const m = document.getElementById('approveModal');
        m.classList.add('hidden');
        m.classList.remove('flex');
    }

    function clearApproveSig() {
        approveSigPad?.clear();
        document.getElementById('paraf_petugas_modal').value = '';
    }

    document.getElementById('approveForm').addEventListener('submit', function (e) {
        if (!approveSigPad || approveSigPad.isEmpty()) {
            e.preventDefault();
            alert('Paraf petugas wajib diisi sebelum menyetujui peminjaman.');
            return;
        }
        document.getElementById('paraf_petugas_modal').value = approveSigPad.toDataURL('image/png');
    });
</script>
@endpush
@elseif($peminjaman->status === 'dipinjam')
<div class="mt-6">
    <form method="POST" action="{{ route('admin.peminjaman.kembalikan', $peminjaman) }}">
        @csrf @method('PUT')
        <button type="submit" onclick="return confirm('Konfirmasi pengembalian arsip?')" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold rounded-lg bg-emerald-500 text-white hover:bg-emerald-600 transition shadow-sm">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
            Proses Pengembalian
        </button>
    </form>
</div>
@endif
@endsection