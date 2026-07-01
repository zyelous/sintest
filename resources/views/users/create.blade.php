@extends('layouts.app')

@section('title', 'Tambah User')

@section('breadcrumb')
    <a href="{{ route('dashboard') }}" class="text-slate-500 hover:text-blue-500 font-medium">Dashboard</a>
    <span class="text-slate-300">/</span>
    <a href="{{ route('users.index') }}" class="text-slate-500 hover:text-blue-500 font-medium">Manajemen User</a>
    <span class="text-slate-300">/</span>
    <span class="text-slate-800 font-semibold">Tambah User</span>
@endsection

@section('content')
    {{-- Page Header --}}
    <div class="flex items-center justify-between mb-6 flex-wrap gap-3">
        <h1 class="text-2xl font-bold text-slate-800">Tambah User</h1>
    </div>

    {{-- Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <h2 class="text-base font-semibold text-slate-700">Formulir Tambah User</h2>
        </div>

        <div class="p-6">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    {{-- Nama --}}
                    <div>
                        <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Lengkap</label>
                        <input type="text"
                               id="name"
                               name="name"
                               value="{{ old('name') }}"
                               placeholder="Masukkan nama lengkap"
                               class="w-full px-3 py-2.5 border-[1.5px] border-slate-200 rounded-lg text-sm outline-none transition focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 @error('name') border-red-400 focus:ring-red-500/20 focus:border-red-500 @enderror"
                               required>
                        @error('name')
                            <p class="text-xs font-medium text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Username --}}
                    <div>
                        <label for="username" class="block text-sm font-semibold text-slate-700 mb-1.5">Username</label>
                        <input type="text"
                               id="username"
                               name="username"
                               value="{{ old('username') }}"
                               placeholder="Masukkan username"
                               class="w-full px-3 py-2.5 border-[1.5px] border-slate-200 rounded-lg text-sm outline-none transition focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 @error('username') border-red-400 focus:ring-red-500/20 focus:border-red-500 @enderror"
                               required>
                        @error('username')
                            <p class="text-xs font-medium text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">Email</label>
                        <input type="email"
                               id="email"
                               name="email"
                               value="{{ old('email') }}"
                               placeholder="Masukkan alamat email"
                               class="w-full px-3 py-2.5 border-[1.5px] border-slate-200 rounded-lg text-sm outline-none transition focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 @error('email') border-red-400 focus:ring-red-500/20 focus:border-red-500 @enderror"
                               required>
                        @error('email')
                            <p class="text-xs font-medium text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">Password</label>
                        <input type="password"
                               id="password"
                               name="password"
                               placeholder="Masukkan password"
                               class="w-full px-3 py-2.5 border-[1.5px] border-slate-200 rounded-lg text-sm outline-none transition focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 @error('password') border-red-400 focus:ring-red-500/20 focus:border-red-500 @enderror"
                               required>
                        @error('password')
                            <p class="text-xs font-medium text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Role --}}
                    <div>
                        <label for="role" class="block text-sm font-semibold text-slate-700 mb-1.5">Role</label>
                        <select id="role"
                                name="role"
                                onchange="toggleBidang(this.value)"
                                class="w-full px-3 py-2.5 border-[1.5px] border-slate-200 rounded-lg text-sm outline-none transition focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 @error('role') border-red-400 focus:ring-red-500/20 focus:border-red-500 @enderror"
                                required>
                            <option value="" disabled {{ old('role') ? '' : 'selected' }}>Pilih Role</option>
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="operator" {{ old('role') === 'operator' ? 'selected' : '' }}>Operator</option>
                        </select>
                        @error('role')
                            <p class="text-xs font-medium text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Bidang (shown when role=operator) --}}
                    <div id="bidang-wrapper" class="{{ old('role') === 'operator' ? '' : 'hidden' }}">
                        <label for="bidang_id" class="block text-sm font-semibold text-slate-700 mb-1.5">Bidang</label>
                        <select id="bidang_id"
                                name="bidang_id"
                                class="w-full px-3 py-2.5 border-[1.5px] border-slate-200 rounded-lg text-sm outline-none transition focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 @error('bidang_id') border-red-400 focus:ring-red-500/20 focus:border-red-500 @enderror">
                            <option value="" disabled {{ old('bidang_id') ? '' : 'selected' }}>Pilih Bidang</option>
                            @foreach ($bidangList as $bidang)
                                <option value="{{ $bidang->id }}" {{ old('bidang_id') == $bidang->id ? 'selected' : '' }}>
                                    {{ $bidang->nama_bidang }}
                                </option>
                            @endforeach
                        </select>
                        @error('bidang_id')
                            <p class="text-xs font-medium text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div>
                        <label for="is_active" class="block text-sm font-semibold text-slate-700 mb-1.5">Status Aktif</label>
                        <select id="is_active"
                                name="is_active"
                                class="w-full px-3 py-2.5 border-[1.5px] border-slate-200 rounded-lg text-sm outline-none transition focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 @error('is_active') border-red-400 focus:ring-red-500/20 focus:border-red-500 @enderror"
                                required>
                            <option value="1" {{ old('is_active', '1') === '1' ? 'selected' : '' }}>Ya</option>
                            <option value="0" {{ old('is_active') === '0' ? 'selected' : '' }}>Tidak</option>
                        </select>
                        @error('is_active')
                            <p class="text-xs font-medium text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-end gap-3 mt-8 pt-5 border-t border-slate-100">
                    <a href="{{ route('users.index') }}"
                       class="px-4 py-2 text-sm font-semibold rounded-lg transition border border-slate-300 text-slate-600 hover:bg-slate-50">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-4 py-2 text-sm font-semibold rounded-lg transition bg-gradient-to-r from-primary to-primary-light text-white shadow-sm hover:-translate-y-0.5">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function toggleBidang(role) {
        const wrapper = document.getElementById('bidang-wrapper');
        if (role === 'operator') {
            wrapper.classList.remove('hidden');
        } else {
            wrapper.classList.add('hidden');
            document.getElementById('bidang_id').value = '';
        }
    }
</script>
@endpush
