@extends('layouts.app')
@section('title', 'Edit Peminjaman')
@section('breadcrumb')
    <a href="{{ route('operator.dashboard') }}" class="hover:text-primary">Dashboard</a>
    <span>/</span>
    <a href="{{ route('operator.peminjaman.index') }}" class="hover:text-primary">Peminjaman Arsip</a>
    <span>/</span>
    <span class="text-slate-700 font-medium">Edit</span>
@endsection

@section('content')
<div class="mb-6">
    <h1 class="text-xl font-bold text-slate-800">Edit Peminjaman</h1>
    <p class="text-sm text-slate-500 mt-0.5">Perbarui detail peminjaman arsip.</p>
</div>

<div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 sm:p-6 lg:p-8 w-full">
    <div class="mb-5 pb-5 border-b border-slate-100">
        <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide">Arsip yang Dipinjam</p>
        <p class="text-sm font-semibold text-primary mt-1">{{ $peminjaman->arsip->kode_klasifikasi }} / {{ $peminjaman->arsip->no_berkas }}</p>
        <p class="text-sm text-slate-600">{{ $peminjaman->arsip->uraian_berkas }}</p>
    </div>

    <form method="POST" action="{{ route('operator.peminjaman.update', $peminjaman) }}" id="editForm">
        @csrf @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            <div class="col-span-1">
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nama Peminjam <span class="text-red-500">*</span></label>
                <input type="text" name="nama_peminjam" value="{{ old('nama_peminjam', $peminjaman->nama_peminjam) }}" required class="w-full px-3.5 py-2.5 rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary @error('nama_peminjam') border-red-400 @enderror">
                @error('nama_peminjam')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="col-span-1 md:col-span-1 lg:col-span-2">
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Unit Kerja/Bidang Peminjam <span class="text-red-500">*</span></label>
                <select name="bidang_peminjam" required class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-sm focus:ring-primary focus:border-primary bg-white @error('bidang_peminjam') border-red-400 @enderror">
                    <option value="">-- Pilih Unit/Bidang --</option>
                    @foreach($bidangList as $bidang)
                        <option value="{{ $bidang->nama_bidang }}" {{ old('bidang_peminjam', $peminjaman->bidang_peminjam) == $bidang->nama_bidang ? 'selected' : '' }}>
                            {{ $bidang->nama_bidang }}
                        </option>
                    @endforeach
                </select>
                @error('bidang_peminjam')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="col-span-1">
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Tanggal Peminjaman <span class="text-red-500">*</span></label>
                <input type="date" name="tanggal_pinjam" value="{{ old('tanggal_pinjam', $peminjaman->tanggal_pinjam->format('Y-m-d')) }}" required class="w-full px-3.5 py-2.5 rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary @error('tanggal_pinjam') border-red-400 @enderror">
                @error('tanggal_pinjam')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="col-span-1">
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Tanggal Rencana Kembali <span class="text-red-500">*</span></label>
                <input type="date" name="tanggal_rencana_kembali" value="{{ old('tanggal_rencana_kembali', $peminjaman->tanggal_rencana_kembali?->format('Y-m-d')) }}" required class="w-full px-3.5 py-2.5 rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary @error('tanggal_rencana_kembali') border-red-400 @enderror">
                @error('tanggal_rencana_kembali')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="col-span-1">
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Jumlah <span class="text-red-500">*</span></label>
                <input type="number" name="jumlah" min="1" value="{{ old('jumlah', $peminjaman->jumlah) }}" required class="w-full px-3.5 py-2.5 rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary @error('jumlah') border-red-400 @enderror">
                @error('jumlah')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="md:col-span-2 lg:col-span-3 grid grid-cols-1 lg:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Paraf Peminjam</label>
                    @if($peminjaman->paraf_peminjam)
                    <div class="flex items-center gap-3 mb-2 p-2 rounded-lg bg-slate-50 border border-slate-200 w-fit">
                        <img src="{{ $peminjaman->paraf_peminjam }}" alt="Paraf peminjam saat ini" class="h-14 bg-white rounded border border-slate-200">
                        <span class="text-xs text-slate-400">Paraf saat ini</span>
                    </div>
                    @endif
                    <div class="rounded-xl border-2 border-dashed border-slate-300 bg-slate-50/60 p-2 relative">
                        <canvas id="sigPeminjam" class="w-full h-44 bg-white rounded-lg touch-none"></canvas>
                        <button type="button" onclick="clearSig('sigPeminjam','paraf_peminjam')" class="absolute top-3 right-3 w-8 h-8 flex items-center justify-center rounded-lg bg-white border border-slate-200 text-slate-400 hover:text-slate-600 hover:bg-slate-50 shadow-sm" title="Hapus paraf">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M8 6V4a1 1 0 011-1h6a1 1 0 011 1v2m3 0-1 14a2 2 0 01-2 2H7a2 2 0 01-2-2L4 6h16z"/></svg>
                        </button>
                    </div>
                    <p class="text-xs text-slate-400 mt-1 uppercase tracking-wide font-semibold">Biarkan kosong jika tidak ingin mengubah paraf</p>
                    <input type="hidden" name="paraf_peminjam" id="paraf_peminjam" value="">
                    @error('paraf_peminjam')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5">Paraf Petugas/Arsiparis</label>
                    @if($peminjaman->paraf_petugas)
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-3 inline-block h-44 w-full flex items-center justify-center">
                        <img src="{{ $peminjaman->paraf_petugas }}" alt="Paraf petugas" class="h-28 bg-white rounded object-contain">
                    </div>
                    <p class="text-xs text-slate-400 mt-1">Diisi oleh Admin saat persetujuan. Tidak bisa diubah dari sini.</p>
                    @else
                    <div class="rounded-xl border-2 border-dashed border-slate-200 bg-slate-50 p-6 h-44 flex items-center justify-center">
                        <p class="text-xs text-slate-400 text-center">Paraf petugas akan diisi oleh Admin saat memproses persetujuan peminjaman ini.</p>
                    </div>
                    @endif
                </div>
            </div>

            <div class="md:col-span-2 lg:col-span-3">
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Keterangan</label>
                <textarea name="keterangan" rows="2" class="w-full px-3.5 py-2.5 rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">{{ old('keterangan', $peminjaman->keterangan) }}</textarea>
            </div>
        </div>

        @if($peminjaman->status === 'menunggu_persetujuan')
        <div class="mt-5 bg-emerald-50 border border-emerald-200 rounded-lg p-4">
            <label class="block text-xs font-semibold text-emerald-700 mb-1.5">Paraf Petugas Arsip <span class="text-red-500">*</span></label>
            <input type="text" name="paraf_petugas" value="{{ old('paraf_petugas') }}" required
                placeholder="Ketik nama/inisial petugas sebagai paraf persetujuan"
                class="w-full px-3.5 py-2.5 rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary @error('paraf_petugas') border-red-400 @enderror">
            @error('paraf_petugas')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            <p class="text-xs text-emerald-600 mt-1.5">Isi paraf ini untuk menyetujui peminjaman. Status otomatis jadi "Disetujui" setelah disimpan.</p>
        </div>
        @endif

        @if($peminjaman->status === 'dipinjam')
        <div class="mt-5 bg-sky-50 border border-sky-200 rounded-lg p-4">
            <label class="block text-xs font-semibold text-sky-700 mb-1.5">Paraf Pengembalian Petugas</label>
            <input type="text" name="paraf_pengembalian" value="{{ old('paraf_pengembalian') }}"
                placeholder="Isi hanya jika arsip sudah benar-benar dikembalikan"
                class="w-full px-3.5 py-2.5 rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary @error('paraf_pengembalian') border-red-400 @enderror">
            @error('paraf_pengembalian')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            <p class="text-xs text-sky-600 mt-1.5">Kosongkan jika arsip belum dikembalikan. Jika diisi, status otomatis jadi "Dikembalikan".</p>
        </div>
        @endif

        <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 mt-6 pt-5 border-t border-slate-100">
            <a href="{{ route('operator.peminjaman.index') }}" class="w-full sm:w-auto text-center px-5 py-2.5 text-sm font-semibold rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-50 transition">Batal</a>
            <button type="submit" class="w-full sm:w-auto px-5 py-2.5 text-sm font-semibold rounded-lg bg-primary text-white hover:bg-primary-light transition shadow-sm">Simpan Perubahan</button>
        </div>
    </form>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/signature_pad/4.1.6/signature_pad.umd.min.js"></script>
<script>
    const sigPads = {};

    function initSignaturePad(canvasId) {
        const canvas = document.getElementById(canvasId);
        if (!canvas) return;

        if (!sigPads[canvasId]) {
            sigPads[canvasId] = new SignaturePad(canvas, {
                backgroundColor: 'rgb(255,255,255)',
                penColor: 'rgb(15,23,42)'
            });
        }
        resizeCanvas(canvasId);
    }

    function resizeCanvas(canvasId) {
        const canvas = document.getElementById(canvasId);
        if (!canvas) return;
        const pad = sigPads[canvasId];
        let data = null;
        if (pad && !pad.isEmpty()) {
            data = pad.toData();
        }
        const ratio = Math.max(window.devicePixelRatio || 1, 1);
        const rect = canvas.getBoundingClientRect();
        if (rect.width === 0 || rect.height === 0) return;
        canvas.width = rect.width * ratio;
        canvas.height = rect.height * ratio;
        canvas.getContext('2d').scale(ratio, ratio);
        if (pad) {
            pad.clear();
            if (data) pad.fromData(data);
        }
    }

    function clearSig(canvasId, hiddenInputId) {
        if (sigPads[canvasId]) {
            sigPads[canvasId].clear();
        }
        const input = document.getElementById(hiddenInputId);
        if (input) input.value = '';
    }

    window.addEventListener('resize', function () {
        Object.keys(sigPads).forEach(id => resizeCanvas(id));
    });

    document.addEventListener('DOMContentLoaded', function () {
        initSignaturePad('sigPeminjam');

        document.getElementById('editForm').addEventListener('submit', function () {
            if (sigPads['sigPeminjam'] && !sigPads['sigPeminjam'].isEmpty()) {
                document.getElementById('paraf_peminjam').value = sigPads['sigPeminjam'].toDataURL('image/png');
            }
        });
    });
</script>
@endpush
@endsection