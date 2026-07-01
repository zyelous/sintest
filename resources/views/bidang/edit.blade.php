@extends('layouts.app')

@section('title', 'Edit Bidang')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}" class="text-slate-500 hover:text-blue-500 font-medium">Dashboard</a>
    <span class="text-slate-300">/</span>
    <a href="{{ route('bidang.index') }}" class="text-slate-500 hover:text-blue-500 font-medium">Manajemen Bidang</a>
    <span class="text-slate-300">/</span>
    <span class="text-slate-800 font-semibold">Edit Bidang</span>
@endsection

@section('content')
    {{-- Page Header --}}
    <div class="flex items-center justify-between mb-6 flex-wrap gap-3">
        <h1 class="text-2xl font-bold text-slate-800">Edit Bidang</h1>
    </div>

    {{-- Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <h2 class="text-base font-semibold text-slate-700">Formulir Edit Bidang</h2>
        </div>

        <div class="p-6">
            <form action="{{ route('bidang.update', $bidang->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    {{-- Nama Bidang --}}
                    <div>
                        <label for="nama_bidang" class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Bidang</label>
                        <input type="text"
                               id="nama_bidang"
                               name="nama_bidang"
                               value="{{ old('nama_bidang', $bidang->nama_bidang) }}"
                               placeholder="Masukkan nama bidang"
                               class="w-full px-3 py-2.5 border-[1.5px] border-slate-200 rounded-lg text-sm outline-none transition focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 @error('nama_bidang') border-red-400 focus:ring-red-500/20 focus:border-red-500 @enderror"
                               required>
                        @error('nama_bidang')
                            <p class="text-xs font-medium text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Kode Bidang --}}
                    <div>
                        <label for="kode_bidang" class="block text-sm font-semibold text-slate-700 mb-1.5">Kode Bidang</label>
                        <input type="text"
                               id="kode_bidang"
                               name="kode_bidang"
                               value="{{ old('kode_bidang', $bidang->kode_bidang) }}"
                               placeholder="Masukkan kode bidang"
                               class="w-full px-3 py-2.5 border-[1.5px] border-slate-200 rounded-lg text-sm outline-none transition focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 @error('kode_bidang') border-red-400 focus:ring-red-500/20 focus:border-red-500 @enderror"
                               required>
                        @error('kode_bidang')
                            <p class="text-xs font-medium text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-end gap-3 mt-8 pt-5 border-t border-slate-100">
                    <a href="{{ route('bidang.index') }}"
                       class="px-4 py-2 text-sm font-semibold rounded-lg transition border border-slate-300 text-slate-600 hover:bg-slate-50">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-4 py-2 text-sm font-semibold rounded-lg transition bg-gradient-to-r from-primary to-primary-light text-white shadow-sm hover:-translate-y-0.5">
                        Perbarui
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
