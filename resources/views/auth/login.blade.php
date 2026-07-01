@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex items-center justify-center p-6 relative">
    {{-- Subtle pattern overlay --}}
    <div class="absolute inset-0 opacity-[0.03]" style="background-image: url(&quot;data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none'%3E%3Cg fill='%23ffffff' fill-opacity='1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E&quot;);"></div>

    <div class="w-full max-w-[440px] bg-white/[0.97] backdrop-blur-xl rounded-2xl shadow-2xl shadow-blue-500/10 p-10 sm:p-12 relative z-10 animate-fade-in-up">
        {{-- Header --}}
        <div class="text-center mb-8">
            <div class="mx-auto mb-4 w-16 h-16 flex items-center justify-center bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl">
                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#3B82F6" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
            </div>
            <h1 class="text-3xl font-extrabold bg-gradient-to-r from-primary to-blue-500 bg-clip-text text-transparent tracking-tight">SINTARA</h1>
            <p class="text-sm text-slate-500 mt-1">Sistem Informasi Tata Naskah dan Arsip</p>
            <p class="text-xs text-slate-400 font-medium uppercase tracking-wider mt-0.5">Bappeda Provinsi Lampung</p>
        </div>

        {{-- Form --}}
        <form method="POST" action="{{ route('login.submit') }}" class="space-y-5">
            @csrf

            {{-- Username --}}
            <div>
                <label for="username" class="block text-sm font-semibold text-slate-700 mb-1.5">Username</label>
                <div class="relative">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <input type="text" id="username" name="username" value="{{ old('username') }}" placeholder="Masukkan username" autofocus required
                        class="w-full pl-11 pr-4 py-2.5 border-[1.5px] rounded-lg text-sm outline-none transition focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 {{ $errors->has('username') ? 'border-red-400 focus:ring-red-500/20 focus:border-red-500' : 'border-slate-200' }}">
                </div>
                @error('username')
                    <p class="mt-1 text-xs font-medium text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div>
                <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">Password</label>
                <div class="relative">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    <input type="password" id="password" name="password" placeholder="Masukkan password" required
                        class="w-full pl-11 pr-11 py-2.5 border-[1.5px] border-slate-200 rounded-lg text-sm outline-none transition focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                    <button type="button" onclick="togglePassword()" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition p-1">
                        <svg id="eyeIcon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                </div>
                @error('password')
                    <p class="mt-1 text-xs font-medium text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Remember --}}
            <div>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-blue-500 focus:ring-blue-500/30 accent-blue-500">
                    <span class="text-sm text-slate-500">Ingat saya</span>
                </label>
            </div>

            {{-- Submit --}}
            <button type="submit" class="w-full flex items-center justify-center gap-2 py-3 px-4 bg-gradient-to-r from-primary to-primary-light text-white font-semibold rounded-lg shadow-lg shadow-primary/25 hover:shadow-primary/40 hover:-translate-y-0.5 transition-all duration-200 text-sm">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                Masuk
            </button>
        </form>

        {{-- Footer --}}
        <div class="mt-8 pt-5 border-t border-slate-100 text-center">
            <p class="text-xs text-slate-400">&copy; {{ date('Y') }} SINTARA — Bappeda Provinsi Lampung</p>
        </div>
    </div>
</div>

<script>
function togglePassword() {
    const input = document.getElementById('password');
    input.type = input.type === 'password' ? 'text' : 'password';
}
</script>
@endsection
