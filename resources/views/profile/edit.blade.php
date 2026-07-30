@extends('layouts.app')
@section('title', 'Profil Saya & Pengaturan Akun')
@section('breadcrumb')
<a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('operator.dashboard') }}" class="hover:text-primary">Beranda</a> <span>/</span> <span class="text-slate-700 font-medium">Profil Saya</span>
@endsection

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-800">Pengaturan Profil & Keamanan</h1>
    <p class="text-sm text-slate-500 mt-1">Kelola data profil pribadi dan perbarui password akun Anda.</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Main Column (2 cols) --}}
    <div class="lg:col-span-2 space-y-6">
        
        {{-- Card 1: Informasi Profil --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-800">Informasi Pribadi</h2>
                        <p class="text-xs text-slate-400">Perbarui nama lengkap, username, dan alamat email Anda.</p>
                    </div>
                </div>
            </div>

            @if(session('success_profile'))
            <div class="mb-5 flex items-center gap-2 p-3.5 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                <span>{{ session('success_profile') }}</span>
            </div>
            @endif

            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-3.5 py-2.5 rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary @error('name') border-red-400 @enderror">
                        @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Username</label>
                        <input type="text" name="username" value="{{ old('username', $user->username) }}" required class="w-full px-3.5 py-2.5 rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary @error('username') border-red-400 @enderror">
                        @error('username')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Alamat Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full px-3.5 py-2.5 rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary @error('email') border-red-400 @enderror">
                        @error('email')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="flex justify-end pt-4 border-t border-slate-100">
                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold rounded-lg bg-primary text-white hover:bg-primary-light transition shadow-sm">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        Simpan Profil
                    </button>
                </div>
            </form>
        </div>

        {{-- Card 2: Ganti Password --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center text-amber-700">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-800">Ganti Password Mandiri</h2>
                        <p class="text-xs text-slate-400">Pastikan akun Anda menggunakan password yang aman dan sulit ditebak.</p>
                    </div>
                </div>
            </div>

            @if(session('success_password'))
            <div class="mb-5 flex items-center gap-2 p-3.5 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                <span>{{ session('success_password') }}</span>
            </div>
            @endif

            <form action="{{ route('profile.password') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-4 mb-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Password Saat Ini</label>
                        <div class="relative">
                            <input type="password" id="input_current_password" name="current_password" required class="w-full px-3.5 py-2.5 pr-10 rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary @error('current_password') border-red-400 @enderror">
                            <button type="button" onclick="togglePassVisibility('input_current_password', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            </button>
                        </div>
                        @error('current_password')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Password Baru</label>
                            <div class="relative">
                                <input type="password" id="input_new_password" name="password" required class="w-full px-3.5 py-2.5 pr-10 rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary @error('password') border-red-400 @enderror">
                                <button type="button" onclick="togglePassVisibility('input_new_password', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </button>
                            </div>
                            @error('password')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Konfirmasi Password Baru</label>
                            <div class="relative">
                                <input type="password" id="input_confirm_password" name="password_confirmation" required class="w-full px-3.5 py-2.5 pr-10 rounded-lg border-slate-300 text-sm focus:ring-primary focus:border-primary">
                                <button type="button" onclick="togglePassVisibility('input_confirm_password', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-4 border-t border-slate-100">
                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold rounded-lg bg-amber-600 text-white hover:bg-amber-700 transition shadow-sm">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Side Column (1 col) --}}
    <div class="space-y-6">
        <div class="bg-gradient-to-br from-slate-900 via-slate-900 to-slate-800 text-white rounded-xl shadow-md p-6 border border-slate-800">
            <div class="flex items-center gap-4 mb-5">
                <div class="w-14 h-14 rounded-full bg-primary/30 border-2 border-primary-light flex items-center justify-center text-xl font-bold text-white shrink-0 shadow-inner">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <h3 class="font-bold text-base text-white truncate">{{ $user->name }}</h3>
                    <p class="text-xs text-slate-400 truncate">@ {{ $user->username }}</p>
                    <span class="inline-flex items-center px-2 py-0.5 mt-1.5 rounded-md text-[0.65rem] font-bold uppercase tracking-wider {{ $user->isAdmin() ? 'bg-amber-500/20 text-amber-300 border border-amber-500/30' : 'bg-sky-500/20 text-sky-300 border border-sky-500/30' }}">
                        {{ $user->isAdmin() ? 'Administrator Utama' : 'Operator Bidang' }}
                    </span>
                </div>
            </div>

            <div class="space-y-3 text-xs pt-4 border-t border-white/10">
                <div class="flex justify-between items-center">
                    <span class="text-slate-400">Unit / Bidang Kerja:</span>
                    <span class="font-semibold text-slate-200">{{ $user->bidang->nama_bidang ?? 'Semua Bidang' }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-slate-400">Status Akun:</span>
                    <span class="inline-flex items-center gap-1 font-semibold text-emerald-400">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        Aktif
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-slate-400">Terdaftar Sejak:</span>
                    <span class="font-semibold text-slate-300">{{ $user->created_at?->translatedFormat('d M Y') ?? '-' }}</span>
                </div>
            </div>
        </div>

        <div class="bg-primary/5 rounded-xl border border-primary/10 p-5">
            <h4 class="font-bold text-sm text-slate-800 mb-2">Keamanan Sesi</h4>
            <p class="text-xs text-slate-500 leading-relaxed">
                Password baru minimal terdiri dari 8 karakter. Hindari penggunaan kata sandi yang mudah ditebak atau sama dengan tanggal lahir.
            </p>
        </div>
    </div>
</div>

@push('scripts')
<script>
function togglePassVisibility(inputId, btn) {
    const input = document.getElementById(inputId);
    if (!input) return;
    if (input.type === 'password') {
        input.type = 'text';
        btn.classList.add('text-primary');
        btn.classList.remove('text-slate-400');
    } else {
        input.type = 'password';
        btn.classList.remove('text-primary');
        btn.classList.add('text-slate-400');
    }
}
</script>
@endpush
@endsection
