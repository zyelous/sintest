@extends('layouts.app')

@section('title', 'Registrasi Surat Masuk')

@section('breadcrumb')
<span class="font-semibold text-[#073B63]">Arsip Surat</span>
@endsection

@section('content')
<div class="mx-auto max-w-[1180px]">
    <header class="mb-8">
        <h1 class="text-[30px] font-bold leading-tight tracking-tight text-[#073B63]">Registrasi Surat Masuk</h1>
        <p class="mt-1 text-sm text-slate-600">Silakan lengkapi detail surat dan tentukan lokasi penyimpanan fisik.</p>
    </header>

    <form method="POST" action="{{ route('arsip.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 items-start gap-7 xl:grid-cols-[minmax(0,2fr)_minmax(300px,0.95fr)]">
            {{-- Kolom kiri --}}
            <div class="space-y-7">
                <section class="rounded-lg border border-slate-300 bg-white p-6 shadow-sm">
                    <div class="mb-6 flex items-center gap-2.5 border-b border-slate-200 pb-5">
                        <svg class="h-5 w-5 text-[#074876]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path stroke-linecap="round" stroke-linejoin="round" d="M14 2v6h6M8 13h8M8 17h8"/>
                        </svg>
                        <h2 class="text-xl font-bold text-slate-800">Identitas Berkas &amp; Surat</h2>
                    </div>

                    <div class="grid grid-cols-1 gap-x-6 gap-y-6 md:grid-cols-2">
                        <div>
                            <label for="kode_klasifikasi" class="mb-2 block text-xs font-bold tracking-wide text-slate-700">Kode Klasifikasi <span class="text-red-500">*</span></label>
                            <input id="kode_klasifikasi" type="text" name="kode_klasifikasi" value="{{ old('kode_klasifikasi') }}" required placeholder="Contoh: 000.3" class="h-12 w-full rounded border-slate-300 bg-slate-50 px-3 text-sm text-slate-700 placeholder:text-slate-400 focus:border-[#074876] focus:ring-[#074876] {{ $errors->has('kode_klasifikasi') ? 'border-red-400' : '' }}">
                            @if($errors->has('kode_klasifikasi'))<p class="mt-1 text-xs text-red-500">{{ $errors->first('kode_klasifikasi') }}</p>@endif
                        </div>

                        <div>
                            <label for="no_berkas" class="mb-2 block text-xs font-bold tracking-wide text-slate-700">Nomor Berkas <span class="text-red-500">*</span></label>
                            <input id="no_berkas" type="text" name="no_berkas" value="{{ old('no_berkas') }}" required placeholder="Contoh: 000/7/VI.01/2026" class="h-12 w-full rounded border-slate-300 bg-slate-50 px-3 text-sm text-slate-700 placeholder:text-slate-400 focus:border-[#074876] focus:ring-[#074876] {{ $errors->has('no_berkas') ? 'border-red-400' : '' }}">
                            @if($errors->has('no_berkas'))<p class="mt-1 text-xs text-red-500">{{ $errors->first('no_berkas') }}</p>@endif
                        </div>

                        <div>
                            <label for="jumlah_halaman_bundle" class="mb-2 block text-xs font-bold tracking-wide text-slate-700">Jumlah Hal/Map/Bundle</label>
                            <input id="jumlah_halaman_bundle" type="text" name="jumlah_halaman_bundle" value="{{ old('jumlah_halaman_bundle') }}" placeholder="e.g. 5 hal" class="h-12 w-full rounded border-slate-300 bg-slate-50 px-3 text-sm text-slate-700 placeholder:text-slate-400 focus:border-[#074876] focus:ring-[#074876]">
                        </div>

                        <div>
                            <label for="jumlah_berkas" class="mb-2 block text-xs font-bold tracking-wide text-slate-700">Jumlah Berkas</label>
                            <div class="flex gap-2">
                                <input id="jumlah_berkas" type="number" min="1" name="jumlah_berkas" value="{{ old('jumlah_berkas', 1) }}" class="h-12 min-w-0 flex-1 rounded border-slate-300 bg-slate-50 px-3 text-sm text-slate-700 focus:border-[#074876] focus:ring-[#074876]">
                                <span class="flex h-12 items-center rounded bg-[#E8EEF9] px-4 text-sm text-slate-700">berkas</span>
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <label for="no_item_arsip" class="mb-2 block text-xs font-bold tracking-wide text-slate-700">Nomor Item Arsip</label>
                            <input id="no_item_arsip" type="text" name="no_item_arsip" value="{{ old('no_item_arsip') }}" placeholder="Masukkan nomor item" class="h-12 w-full rounded border-slate-300 bg-slate-50 px-3 text-sm text-slate-700 placeholder:text-slate-400 focus:border-[#074876] focus:ring-[#074876]">
                        </div>
                    </div>
                </section>

                <section class="rounded-lg border border-slate-300 bg-white p-6 shadow-sm">
                    <div class="mb-6 flex items-center gap-2.5 border-b border-slate-200 pb-5">
                        <svg class="h-5 w-5 text-[#074876]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M12 11v5M12 8h.01"/>
                        </svg>
                        <h2 class="text-xl font-bold text-slate-800">Isi Informasi</h2>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label for="uraian_berkas" class="mb-2 block text-xs font-bold tracking-wide text-slate-700">Uraian Informasi Berkas <span class="text-red-500">*</span></label>
                            <textarea id="uraian_berkas" name="uraian_berkas" rows="3" required placeholder="Tuliskan uraian informasi berkas..." class="w-full resize-none rounded border-slate-300 bg-slate-50 px-3 py-3 text-sm text-slate-700 placeholder:text-slate-400 focus:border-[#074876] focus:ring-[#074876] {{ $errors->has('uraian_berkas') ? 'border-red-400' : '' }}">{{ old('uraian_berkas') }}</textarea>
                            @if($errors->has('uraian_berkas'))<p class="mt-1 text-xs text-red-500">{{ $errors->first('uraian_berkas') }}</p>@endif
                        </div>

                        <div>
                            <label for="uraian_arsip" class="mb-2 block text-xs font-bold tracking-wide text-slate-700">Uraian Informasi Arsip <span class="text-red-500">*</span></label>
                            <textarea id="uraian_arsip" name="uraian_arsip" rows="3" placeholder="e.g. Umum" class="w-full resize-none rounded border-slate-300 bg-slate-50 px-3 py-3 text-sm text-slate-700 placeholder:text-slate-400 focus:border-[#074876] focus:ring-[#074876]">{{ old('uraian_arsip') }}</textarea>
                        </div>

                        <div>
                            <label for="tanggal_diarsipkan" class="mb-2 block text-xs font-bold tracking-wide text-slate-700">Tanggal Diarsipkan <span class="text-red-500">*</span></label>
                            <input id="tanggal_diarsipkan" type="date" name="tanggal_diarsipkan" value="{{ old('tanggal_diarsipkan', date('Y-m-d')) }}" required class="h-12 w-full rounded border-slate-300 bg-slate-50 px-3 text-sm text-slate-700 focus:border-[#074876] focus:ring-[#074876] {{ $errors->has('tanggal_diarsipkan') ? 'border-red-400' : '' }}">
                            @if($errors->has('tanggal_diarsipkan'))<p class="mt-1 text-xs text-red-500">{{ $errors->first('tanggal_diarsipkan') }}</p>@endif
                        </div>
                    </div>
                </section>
            </div>

            {{-- Kolom kanan --}}
            <aside class="space-y-6">
                <section class="rounded-lg border border-slate-300 border-l-4 border-l-slate-300 bg-white p-6 shadow-sm">
                    <div class="mb-6 flex items-center gap-2.5 border-b border-slate-200 pb-5">
                        <svg class="h-5 w-5 text-[#9B7800]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 10c0 7-9 12-9 12S3 17 3 10a9 9 0 1118 0z"/><circle cx="12" cy="10" r="3"/>
                        </svg>
                        <h2 class="text-xl font-bold text-slate-800">Lokasi Simpan Fisik</h2>
                    </div>