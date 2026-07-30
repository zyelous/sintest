@extends('layouts.app')
@section('title', 'Manajemen Bidang & User')
@section('breadcrumb')
<a href="{{ route('admin.dashboard') }}" class="hover:text-primary">Manajemen</a> <span>/</span> <span class="text-slate-700 font-medium">Bidang & User</span>
@endsection
@section('content')

{{-- ═══ Pending Reset Password Requests ═══ --}}
@if(isset($pendingResetRequests) && $pendingResetRequests->count() > 0)
    <div class="mb-6 bg-amber-50/90 border border-amber-200 rounded-xl p-5 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2">
                <span class="flex h-3 w-3 relative">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-3 w-3 bg-amber-500"></span>
                </span>
                <h2 class="text-base font-bold text-amber-900">Permintaan Reset Password Operator ({{ $pendingResetRequests->count() }})</h2>
            </div>
            <span class="text-xs font-semibold px-2.5 py-1 bg-amber-200 text-amber-900 rounded-full">Perlu Konfirmasi Admin</span>
        </div>
        <div class="overflow-x-auto bg-white rounded-lg border border-amber-200/60 shadow-xs">
            <table class="w-full text-left text-sm">
                <thead class="bg-amber-100/50 text-amber-900 font-semibold border-b border-amber-200/60">
                    <tr>
                        <th class="px-4 py-3">Nama Operator</th>
                        <th class="px-4 py-3">Username</th>
                        <th class="px-4 py-3">Bidang</th>
                        <th class="px-4 py-3">Alasan / Catatan</th>
                        <th class="px-4 py-3">Waktu Pengajuan</th>
                        <th class="px-4 py-3 text-center w-40">Aksi Konfirmasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-amber-100">
                    @foreach($pendingResetRequests as $req)
                        <tr class="hover:bg-amber-50/50">
                            <td class="px-4 py-3 font-semibold text-slate-800">{{ $req->user->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-slate-600 font-mono text-xs">{{ $req->username }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $req->user->bidang->nama_bidang ?? '-' }}</td>
                            <td class="px-4 py-3 text-slate-600 italic">{{ $req->alasan ?? 'Tidak ada catatan' }}</td>
                            <td class="px-4 py-3 text-slate-500 text-xs">{{ $req->created_at->diffForHumans() }}</td>
                            <td class="px-4 py-3 text-center">
                                <div class="inline-flex items-center justify-center gap-2">
                                    <form method="POST" action="{{ route('admin.users.reset-requests.approve', $req->id) }}" onsubmit="return confirm('Setujui reset password untuk {{ $req->username }}? Password akan direset ke \'password123\'.')">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg shadow-xs transition">
                                            Setujui & Reset
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.users.reset-requests.reject', $req->id) }}" onsubmit="return confirm('Tolak permintaan reset password dari {{ $req->username }}?')">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="px-3 py-1.5 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-semibold rounded-lg transition">
                                            Tolak
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif

{{-- ═══ Page Header ═══ --}}
<div class="flex items-start justify-between mb-6 flex-wrap gap-3">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Manajemen Bidang & User</h1>
        <p class="text-sm text-slate-500 mt-1">Kelola struktur organisasi, unit kerja, dan akun pengguna Bappeda Provinsi Lampung.</p>
    </div>
</div>

{{-- ═══ Tab Navigation ═══ --}}
<div class="mb-6">

    {{-- Tab Buttons --}}
    <div class="flex gap-1 bg-slate-100 p-1 rounded-xl w-fit mb-6">
        <button onclick="switchTab('bidang')" id="tab-btn-bidang" class="px-5 py-2.5 text-sm font-semibold rounded-lg transition-all duration-200 flex items-center gap-2 bg-white text-primary shadow-sm">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="2" width="16" height="20" rx="1"/><line x1="9" y1="8" x2="15" y2="8"/><line x1="9" y1="12" x2="15" y2="12"/><line x1="9" y1="16" x2="12" y2="16"/></svg>
            Daftar Bidang
            <span id="tab-badge-bidang" class="text-[0.65rem] px-1.5 py-0.5 rounded-full bg-primary/10 text-primary">{{ $bidangList->total() }}</span>
        </button>
        <button onclick="switchTab('user')" id="tab-btn-user" class="px-5 py-2.5 text-sm font-semibold rounded-lg transition-all duration-200 flex items-center gap-2 text-slate-500 hover:text-slate-700">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            Daftar User
            <span id="tab-badge-user" class="text-[0.65rem] px-1.5 py-0.5 rounded-full bg-slate-200 text-slate-500">{{ $userList->total() }}</span>
        </button>
    </div>

    {{-- ═══ TAB 1: BIDANG ═══ --}}
    <div id="tab-content-bidang">

        <div class="flex items-center justify-between mb-5 flex-wrap gap-3">
            <form method="GET" action="{{ route('admin.bidang.index') }}">
                <input type="hidden" name="tab" value="bidang">
                <div class="relative max-w-sm">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau kode bidang..." class="w-full bg-white px-3.5 py-2.5 rounded-lg border-slate-300 pl-9 text-sm focus:ring-primary focus:border-primary">
                </div>
            </form>
            <a href="{{ route('admin.bidang.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-lg bg-primary text-white hover:bg-primary-light transition shadow-sm">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Tambah Bidang Baru
            </a>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 mb-6 flex items-center justify-between">
            <div>
                <p class="text-[0.7rem] font-semibold text-slate-400 uppercase tracking-wide">Total Bidang</p>
                <p class="text-3xl font-bold text-slate-800 mt-1">{{ str_pad($bidangList->total(), 2, '0', STR_PAD_LEFT) }}</p>
            </div>
            <div class="w-11 h-11 rounded-lg bg-primary/10 flex items-center justify-center">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1B3A5C" stroke-width="2"><rect x="3" y="7" width="18" height="14" rx="2"/><path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            @forelse($bidangList as $b)
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                <div class="w-11 h-11 rounded-lg bg-primary/10 flex items-center justify-center mb-4">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1B3A5C" stroke-width="2"><rect x="4" y="2" width="16" height="20" rx="1"/><line x1="9" y1="8" x2="15" y2="8"/><line x1="9" y1="12" x2="15" y2="12"/><line x1="9" y1="16" x2="12" y2="16"/></svg>
                </div>
                <h3 class="font-bold text-slate-800 text-lg">{{ $b->nama_bidang }}</h3>
                <p class="text-xs text-slate-400 mb-2"># KODE: {{ $b->kode_bidang }}</p>
                <p class="text-xs text-slate-600 mb-4"><strong>Kepala Bidang:</strong> {{ $b->kepala_bidang ?? 'Belum ditentukan' }}</p>
                <div class="flex items-center justify-between pt-4 border-t border-slate-100 mb-4">
                    <div>
                        <p class="text-[0.65rem] font-semibold text-slate-400 uppercase">Akun Operator</p>
                        <p class="text-xs font-semibold text-slate-700 mt-0.5">
                            @if($b->operator)
                                <span class="text-blue-600">{{ $b->operator->name }}</span> <span class="text-slate-400">({{ $b->operator->username }})</span>
                            @else
                                <span class="text-red-500">Belum dibuat</span>
                            @endif
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-[0.65rem] font-semibold text-slate-400 uppercase">Total Arsip</p>
                        <p class="text-xs font-semibold text-slate-700 mt-0.5">{{ number_format($b->arsip_count) }} Dokumen</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.bidang.edit', $b) }}" class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 text-sm font-semibold rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-50 transition">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4z"/></svg>
                        Edit
                    </a>
                    <button type="button" onclick="confirmDelete('{{ route('admin.bidang.destroy', $b) }}')" class="inline-flex items-center justify-center px-3 py-2 rounded-lg border border-red-200 text-red-500 hover:bg-red-50 transition">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                    </button>
                </div>
            </div>
            @empty
            <p class="text-slate-400 col-span-2 text-center py-10">Belum ada data bidang.</p>
            @endforelse

            <a href="{{ route('admin.bidang.create') }}" class="border-2 border-dashed border-slate-300 rounded-xl flex flex-col items-center justify-center py-10 text-slate-400 hover:border-primary hover:text-primary hover:bg-primary/5 transition">
                <div class="w-11 h-11 rounded-full border-2 border-current flex items-center justify-center mb-3">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                </div>
                <p class="font-semibold text-sm">Tambah Unit</p>
                <p class="text-xs mt-1 text-center px-6">Daftarkan sub-bidang atau unit kerja baru ke dalam sistem.</p>
            </a>
        </div>

        <div class="mt-6">{{ $bidangList->links('components.pagination') }}</div>
    </div>

    {{-- ═══ TAB 2: USER ═══ --}}
    <div id="tab-content-user" style="display: none;">

        <div class="flex items-center justify-between mb-5 flex-wrap gap-3">
            <form method="GET" action="{{ route('admin.bidang.index') }}">
                <input type="hidden" name="tab" value="user">
                <div class="relative max-w-sm">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" name="search_user" value="{{ request('search_user') }}" placeholder="Cari nama, username, atau email..." class="w-full bg-white px-3.5 py-2.5 rounded-lg border-slate-300 pl-9 text-sm focus:ring-primary focus:border-primary">
                </div>
            </form>
            <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-lg transition bg-gradient-to-r from-primary to-primary-light text-white shadow-sm hover:-translate-y-0.5">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah User
            </a>
        </div>

        {{-- User Table Card --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                <h2 class="text-base font-semibold text-slate-700">Daftar User</h2>
                <span class="text-xs text-slate-400">Total: {{ $userList->total() }} user</span>
            </div>

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
    </div>

</div>
@endsection

@push('scripts')
<script>
    function switchTab(tab) {
        // Toggle content visibility
        document.getElementById('tab-content-bidang').style.display = tab === 'bidang' ? '' : 'none';
        document.getElementById('tab-content-user').style.display = tab === 'user' ? '' : 'none';

        // Toggle button styles
        const bidangBtn = document.getElementById('tab-btn-bidang');
        const userBtn = document.getElementById('tab-btn-user');
        const bidangBadge = document.getElementById('tab-badge-bidang');
        const userBadge = document.getElementById('tab-badge-user');

        if (tab === 'bidang') {
            bidangBtn.className = 'px-5 py-2.5 text-sm font-semibold rounded-lg transition-all duration-200 flex items-center gap-2 bg-white text-primary shadow-sm';
            userBtn.className = 'px-5 py-2.5 text-sm font-semibold rounded-lg transition-all duration-200 flex items-center gap-2 text-slate-500 hover:text-slate-700';
            bidangBadge.className = 'text-[0.65rem] px-1.5 py-0.5 rounded-full bg-primary/10 text-primary';
            userBadge.className = 'text-[0.65rem] px-1.5 py-0.5 rounded-full bg-slate-200 text-slate-500';
        } else {
            userBtn.className = 'px-5 py-2.5 text-sm font-semibold rounded-lg transition-all duration-200 flex items-center gap-2 bg-white text-primary shadow-sm';
            bidangBtn.className = 'px-5 py-2.5 text-sm font-semibold rounded-lg transition-all duration-200 flex items-center gap-2 text-slate-500 hover:text-slate-700';
            userBadge.className = 'text-[0.65rem] px-1.5 py-0.5 rounded-full bg-primary/10 text-primary';
            bidangBadge.className = 'text-[0.65rem] px-1.5 py-0.5 rounded-full bg-slate-200 text-slate-500';
        }

        // Sync tab parameter into URL without page reload
        if (window.history && window.history.replaceState) {
            const url = new URL(window.location);
            url.searchParams.set('tab', tab);
            window.history.replaceState({}, '', url);
        }
    }

    // Auto-switch to user tab if URL has ?tab=user or search_user or user_page
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('tab') === 'user' || urlParams.has('search_user') || urlParams.has('user_page')) {
            switchTab('user');
        }
    });
</script>
@endpush
