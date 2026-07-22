@extends('layouts.app')

@section('title', 'Manajemen User')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="text-slate-500 hover:text-blue-500 font-medium">Dashboard</a>
    <span class="text-slate-300">/</span>
    <span class="text-slate-800 font-semibold">Manajemen User</span>
@endsection

@section('content')
    {{-- Page Header --}}
    <div class="flex items-center justify-between mb-6 flex-wrap gap-3">
        <h1 class="text-2xl font-bold text-slate-800">Manajemen User</h1>
        <a href="{{ route('admin.users.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-lg transition bg-gradient-to-r from-primary to-primary-light text-white shadow-sm hover:-translate-y-0.5">
            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Tambah User
        </a>
    </div>

    {{-- Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        {{-- Card Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <h2 class="text-base font-semibold text-slate-700">Daftar User</h2>
            <span class="text-xs text-slate-400">Total: {{ $userList->total() }} user</span>
        </div>

        {{-- Card Body --}}
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr>
                            <th class="th-sintara w-12">No</th>
                            <th class="th-sintara">Nama</th>
                            <th class="th-sintara">Username</th>
                            <th class="th-sintara">Email</th>
                            <th class="th-sintara">Role</th>
                            <th class="th-sintara">Bidang</th>
                            <th class="th-sintara">Status</th>
                            <th class="th-sintara w-28 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($userList as $i => $user)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="td-sintara text-slate-500">{{ $userList->firstItem() + $i }}</td>
                                <td class="td-sintara font-medium text-slate-800">{{ $user->name }}</td>
                                <td class="td-sintara text-slate-600">{{ $user->username }}</td>
                                <td class="td-sintara text-slate-600">{{ $user->email }}</td>
                                <td class="td-sintara">
                                    @if ($user->role === 'admin')
                                        <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">Admin</span>
                                    @else
                                        <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full bg-cyan-100 text-cyan-700">Operator</span>
                                    @endif
                                </td>
                                <td class="td-sintara text-slate-600">{{ $user->bidang->nama_bidang ?? '-' }}</td>
                                <td class="td-sintara">
                                    @if ($user->is_active)
                                        <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">Aktif</span>
                                    @else
                                        <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full bg-red-100 text-red-700">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="td-sintara text-center">
                                    <div class="inline-flex items-center gap-1">
                                        <form method="POST" action="{{ route('admin.users.reset-password', $user->id) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin mereset password {{ $user->name }} ke \'password123\'?')">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit"
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 hover:text-amber-600 hover:bg-amber-50 transition"
                                                    title="Reset Password">
                                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" />
                                                </svg>
                                            </button>
                                        </form>
                                        <a href="{{ route('admin.users.edit', $user->id) }}"
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition"
                                           title="Edit">
                                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125" />
                                            </svg>
                                        </a>
                                        <button type="button"
                                                onclick="confirmDelete('{{ route('admin.users.destroy', $user->id) }}')"
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
                                <td colspan="8" class="td-sintara text-center text-slate-400 py-8">
                                    <div class="flex flex-col items-center gap-2">
                                        <svg class="w-10 h-10 text-slate-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                                        </svg>
                                        <span class="text-sm">Belum ada data user.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($userList->hasPages())
                <div class="mt-6">
                    {{ $userList->links('components.pagination') }}
                </div>
            @endif
        </div>
    </div>
@endsection
