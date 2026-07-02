@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex items-center justify-center p-4 sm:p-6 md:p-12 relative overflow-hidden bg-slate-950">
    {{-- Blurred Background Photo --}}
    <div class="absolute inset-0 w-full h-full z-0 select-none pointer-events-none">
        <img src="{{ asset('images/login_bg.png') }}" class="w-full h-full object-cover blur-md scale-105 opacity-90" alt="Blurred Background">
        <div class="absolute inset-0 bg-black/15"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/30 via-transparent to-transparent"></div>
    </div>

    {{-- Main Centered Card Container --}}
    <div class="w-full max-w-[960px] bg-white rounded-2xl shadow-2xl flex flex-col md:flex-row overflow-hidden relative z-10 animate-fade-in-up">
        
        {{-- Left Column: Image Banner (Visible on Desktop) --}}
        <div class="hidden md:block md:w-[45%] relative min-h-[520px]">
            {{-- Background Image --}}
            <img src="{{ asset('images/login_bg.png') }}" class="absolute inset-0 w-full h-full object-cover" alt="SINTARA Background">
            
            {{-- Dark Blue-Teal Gradient Overlay --}}
            <div class="absolute inset-0 bg-gradient-to-tr from-[#0A1930]/95 via-[#1B3A5C]/85 to-[#3B82F6]/30 z-10 mix-blend-multiply"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-[#0A1930]/90 via-[#1B3A5C]/50 to-transparent z-10"></div>
            
            {{-- Branding Text Overlay --}}
            <div class="absolute inset-0 p-10 flex flex-col justify-start z-20">
                <span class="text-white text-xs font-bold tracking-widest uppercase">SINTARA</span>
                <h1 class="text-white text-xl lg:text-2xl font-extrabold tracking-wide uppercase leading-snug mt-4">
                    Sistem Informasi Monitoring<br>dan Tracking Arsip
                </h1>
            </div>
        </div>

        {{-- Right Column: Login Form --}}
        <div class="w-full md:w-[55%] flex flex-col justify-center p-8 sm:p-12 bg-white">
            <div class="max-w-[360px] w-full mx-auto">
                
                {{-- Lampung Logo --}}
                <div class="mb-6">
                    <img src="{{ asset('images/logo_lampung.png') }}" class="h-16 w-auto drop-shadow-sm" alt="Logo Provinsi Lampung">
                </div>

                <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Selamat Datang</h2>
                <p class="text-sm text-slate-500 mt-1.5 leading-relaxed">
                    Silakan masuk dengan akun resmi Anda untuk melanjutkan ke dashboard sistem.
                </p>

                <form method="POST" action="{{ route('login.submit') }}" class="space-y-5 mt-6">
                    @csrf

                    {{-- Username Field --}}
                    <div>
                        <label for="username" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Username</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </span>
                            <input type="text" id="username" name="username" value="{{ old('username') }}" placeholder="username" autofocus required
                                class="w-full pl-12 pr-4 py-2.5 border border-slate-200 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200 placeholder:text-slate-400 {{ $errors->has('username') ? 'border-red-400 focus:ring-red-500/20 focus:border-red-500' : 'border-slate-200' }}">
                        </div>
                        @error('username')
                            <p class="mt-1.5 text-xs font-medium text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password Field --}}
                    <div>
                        <label for="password" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Kata Sandi</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </span>
                            <input type="password" id="password" name="password" placeholder="••••••••" required
                                class="w-full pl-12 pr-12 py-2.5 border border-slate-200 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all duration-200 placeholder:text-slate-400">
                            <button type="button" onclick="togglePassword()" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition p-1">
                                {{-- Eye Open Icon --}}
                                <svg id="eyeOpen" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                {{-- Eye Closed Icon --}}
                                <svg id="eyeClosed" width="18" height="18" class="hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1.5 text-xs font-medium text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Remember Checkbox --}}
                    <div class="flex items-center">
                        <label class="flex items-center cursor-pointer group">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-primary focus:ring-primary/20 accent-primary">
                            <span class="text-sm text-slate-500 ml-2.5 select-none group-hover:text-slate-700 transition-colors">Ingat Saya</span>
                        </label>
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit" class="w-full flex items-center justify-center gap-2 py-3 px-4 bg-[#0B2545] hover:bg-[#06182c] text-white font-semibold rounded-lg shadow-md hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 text-sm">
                        <span>Masuk Ke Sistem</span>
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword() {
    const input = document.getElementById('password');
    const eyeOpen = document.getElementById('eyeOpen');
    const eyeClosed = document.getElementById('eyeClosed');
    
    if (input.type === 'password') {
        input.type = 'text';
        eyeOpen.classList.add('hidden');
        eyeClosed.classList.remove('hidden');
    } else {
        input.type = 'password';
        eyeOpen.classList.remove('hidden');
        eyeClosed.classList.add('hidden');
    }
}
</script>
@endsection
