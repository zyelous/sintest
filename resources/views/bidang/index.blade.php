@extends('layouts.app')

@section('title', 'Manajemen Bidang')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}" class="text-slate-500 hover:text-blue-500 font-medium">Dashboard</a>
    <span class="text-slate-300">/</span>
    <span class="text-slate-800 font-semibold">Manajemen Bidang</span>
@endsection

@section('content')
    {{-- Page Header --}}
    <div class="flex items-center justify-between mb-6 flex-wrap gap-3">
        <h1 class="text-2xl font-bold text-slate-800">Manajemen Bidang</h1>
        <a href="{{ route('bidang.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-lg transition bg-gradient-to-r from-primary to-primary-light text-white shadow-sm hover:-translate-y-0.5">
            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Tambah Bidang
        </a>
    </div>

    {{-- Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <h2 class="text-base font-semibold text-slate-700">Daftar Bidang</h2>
            <span class="text-xs text-slate-400">Total: {{ $bidangList->total() }} bidang</span>
        </div>

        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr>
                            <th class="th-sintara w-12">No</th>
                            <th class="th-sintara">Nama Bidang</th>
                            <th class="th-sintara">Kode</th>
                            <th class="th-sintara text-center">Jumlah User</th>
                            <th class="th-sintara text-center">Jumlah Arsip</th>
                            <th class="th-sintara w-28 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($bidangList as $i => $b)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="td-sintara text-slate-500">{{ $bidangList->firstItem() + $i }}</td>
                                <td class="td-sintara font-semibold text-slate-800">{{ $b->nama_bidang }}</td>
                                <td class="td-sintara">
                                    <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full bg-slate-100 text-slate-600">{{ $b->kode_bidang }}</span>
                                </td>
                                <td class="td-sintara text-center text-slate-600">{{ $b->users_count }}</td>
                                <td class="td-sintara text-center text-slate-600">{{ $b->arsip_count }}</td>
                                <td class="td-sintara text-center">
                                    <div class="inline-flex items-center gap-1">
                                        <a href="{{ route('bidang.edit', $b->id) }}"
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition"
                                           title="Edit">
                                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125" />
                                            </svg>
                                        </a>
                                        <button type="button"
                                                onclick="confirmDelete('{{ route('bidang.destroy', $b->id) }}')"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition"
                                                title="Hapus">
                                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="td-sintara text-center text-slate-400 py-8">
                                    <div class="flex flex-col items-center gap-2">
                                        <svg class="w-10 h-10 text-slate-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9.776c.112-.017.227-.026.344-.026h15.812c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 0 0-1.883 2.542l.857 6a2.25 2.25 0 0 0 2.227 1.932H19.05a2.25 2.25 0 0 0 2.227-1.932l.857-6a2.25 2.25 0 0 0-1.883-2.542m-16.5 0V6A2.25 2.25 0 0 1 6 3.75h3.879a1.5 1.5 0 0 1 1.06.44l2.122 2.12a1.5 1.5 0 0 0 1.06.44H18A2.25 2.25 0 0 1 20.25 9v.776" />
                                        </svg>
                                        <span class="text-sm">Belum ada data bidang.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($bidangList->hasPages())
                <div class="mt-6">
                    {{ $bidangList->links('components.pagination') }}
                </div>
            @endif
        </div>
    </div>
@endsection
