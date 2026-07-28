@extends('layouts.app')
@section('title', 'Pinjam Arsip')
@section('breadcrumb')
    <a href="{{ route('operator.dashboard') }}" class="hover:text-primary">Dashboard</a>
    <span>/</span>
    <a href="{{ route('operator.peminjaman.index') }}" class="hover:text-primary">Peminjaman Arsip</a>
    <span>/</span>
    <span class="text-slate-700 font-medium">Pinjam</span>
@endsection

@section('content')
<div class="mb-6">
    <h1 class="text-xl font-bold text-slate-800">Pinjam Arsip</h1>
    <p class="text-sm text-slate-500 mt-0.5">Catat peminjaman arsip fisik sesuai Buku Peminjaman Arsip.</p>
</div>

<div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 max-w-3xl">
    <form method="POST" action="{{ route('operator.peminjaman.store') }}" id="pinjamForm">
        @csrf
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div class="sm:col-span-2">
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Pilih Arsip <span class="text-red-500">*</span></label>
                <select name="arsip_id" required class="w-full px-3.5 py-2.5 rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary @error('arsip_id') border-red-400 @enderror">
                    <option value="">-- Pilih Arsip yang Tersedia --</option>
                    @foreach($arsipTersedia as $a)
                    <option value="{{ $a->id }}" {{ old('arsip_id') == $a->id ? 'selected' : '' }}>[{{ $a->kode_klasifikasi }}] {{ $a->no_berkas }} - {{ Str::limit($a->uraian_berkas, 60) }}</option>
                    @endforeach
                </select>
                @error('arsip_id')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                @if($arsipTersedia->isEmpty())<p class="text-xs text-slate-400 mt-1">Tidak ada arsip yang tersedia untuk dipinjam.</p>@endif
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nama Peminjam <span class="text-red-500">*</span></label>
                <input type="text" name="nama_peminjam" value="{{ old('nama_peminjam') }}" required class="w-full px-3.5 py-2.5 rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary @error('nama_peminjam') border-red-400 @enderror">
                @error('nama_peminjam')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Unit Kerja/Bidang Peminjam <span class="text-red-500">*</span></label>
                <input type="text" name="bidang_peminjam" value="{{ old('bidang_peminjam') }}" required class="w-full px-3.5 py-2.5 rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary @error('bidang_peminjam') border-red-400 @enderror">
                @error('bidang_peminjam')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Tanggal Peminjaman <span class="text-red-500">*</span></label>
                <input type="date" name="tanggal_pinjam" value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" required class="w-full px-3.5 py-2.5 rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary @error('tanggal_pinjam') border-red-400 @enderror">
                @error('tanggal_pinjam')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Tanggal Rencana Kembali <span class="text-red-500">*</span></label>
                <input type="date" name="tanggal_rencana_kembali" value="{{ old('tanggal_rencana_kembali') }}" required class="w-full px-3.5 py-2.5 rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary @error('tanggal_rencana_kembali') border-red-400 @enderror">
                @error('tanggal_rencana_kembali')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Jumlah <span class="text-red-500">*</span></label>
                <input type="number" name="jumlah" min="1" value="{{ old('jumlah', 1) }}" required class="w-full px-3.5 py-2.5 rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary @error('jumlah') border-red-400 @enderror">
                @error('jumlah')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="sm:col-span-2">
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Paraf Peminjam <span class="text-red-500">*</span></label>
                <div class="rounded-xl border-2 border-dashed border-slate-300 bg-slate-50/60 p-2 relative">
                    <canvas id="sigPeminjam" class="w-full h-40 bg-white rounded-lg touch-none"></canvas>
                    <button type="button" onclick="clearSig('sigPeminjam','paraf_peminjam')" class="absolute top-3 right-3 w-8 h-8 flex items-center justify-center rounded-lg bg-white border border-slate-200 text-slate-400 hover:text-slate-600 hover:bg-slate-50" title="Hapus paraf">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M8 6V4a1 1 0 011-1h6a1 1 0 011 1v2m3 0-1 14a2 2 0 01-2 2H7a2 2 0 01-2-2L4 6h16z"/></svg>
                    </button>
                </div>
                <p class="text-xs text-slate-400 mt-1">Tanda tangan/paraf peminjam langsung di area di atas.</p>
                <input type="hidden" name="paraf_peminjam" id="paraf_peminjam" value="{{ old('paraf_peminjam') }}">
                @error('paraf_peminjam')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="sm:col-span-2">
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Paraf Petugas/Arsiparis</label>
                <div class="rounded-xl border-2 border-dashed border-slate-200 bg-slate-50 p-6 flex items-center justify-center">
                    <p class="text-xs text-slate-400 text-center">Paraf petugas akan diisi oleh Admin saat memproses persetujuan peminjaman ini.</p>
                </div>
            </div>

            <div class="sm:col-span-2">
                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Keterangan</label>
                <textarea name="keterangan" rows="2" class="w-full px-3.5 py-2.5 rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">{{ old('keterangan') }}</textarea>
            </div>
        </div>

        <div class="flex justify-end gap-3 mt-6 pt-5 border-t border-slate-100">
            <a href="{{ route('operator.peminjaman.index') }}" class="px-5 py-2.5 text-sm font-semibold rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-50 transition">Batal</a>
            <button type="submit" class="px-5 py-2.5 text-sm font-semibold rounded-lg bg-primary text-white hover:bg-primary-light transition">Simpan Peminjaman</button>
        </div>
    </form>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/signature_pad/4.1.6/signature_pad.umd.min.js"></script>
<script>
    const sigPads = {};

    function initSignaturePad(canvasId) {
        const canvas = document.getElementById(canvasId);
        const ratio = Math.max(window.devicePixelRatio || 1, 1);
        canvas.width = canvas.offsetWidth * ratio;
        canvas.height = canvas.offsetHeight * ratio;
        canvas.getContext('2d').scale(ratio, ratio);
        sigPads[canvasId] = new SignaturePad(canvas, {
            backgroundColor: 'rgb(255,255,255)',
            penColor: 'rgb(15,23,42)'
        });
    }

    function clearSig(canvasId, hiddenInputId) {
        sigPads[canvasId].clear();
        document.getElementById(hiddenInputId).value = '';
    }

    document.addEventListener('DOMContentLoaded', function () {
        initSignaturePad('sigPeminjam');

        document.getElementById('pinjamForm').addEventListener('submit', function (e) {
            const peminjamPad = sigPads['sigPeminjam'];

            if (peminjamPad.isEmpty()) {
                e.preventDefault();
                alert('Paraf peminjam wajib diisi.');
                return;
            }

            document.getElementById('paraf_peminjam').value = peminjamPad.toDataURL('image/png');
        });
    });
</script>
@endpush
@endsection