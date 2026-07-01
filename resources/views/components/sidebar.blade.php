{{-- Sidebar --}}
<aside id="sidebar" class="fixed top-0 left-0 w-[260px] h-screen bg-gradient-to-b from-[#0F2440] via-primary to-primary-light text-white z-[1000] flex flex-col transition-transform duration-300 shadow-xl -translate-x-full lg:translate-x-0 sidebar-scroll overflow-y-auto">
    {{-- Logo --}}
    <div class="px-5 py-6 border-b border-white/10 shrink-0">
        <div class="flex items-center gap-3">
            <div class="p-1.5 bg-blue-400/15 rounded-lg shrink-0">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#60A5FA" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
            </div>
            <div>
                <h2 class="text-lg font-extrabold tracking-wider leading-tight">SINTARA</h2>
                <p class="text-[0.6rem] text-white/40 uppercase tracking-wider">Bappeda Prov. Lampung</p>
            </div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 py-4 overflow-y-auto">
        <div class="mb-2">
            <span class="block text-[0.6rem] font-bold text-white/30 uppercase tracking-[1.5px] px-6 py-2">Menu Utama</span>
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-6 py-2.5 text-sm font-medium transition border-l-[3px] {{ request()->routeIs('dashboard') ? 'text-white bg-white/[0.12] border-l-blue-400' : 'text-white/60 border-l-transparent hover:text-white hover:bg-white/[0.08]' }}">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
                <span>Dashboard</span>
            </a>
        </div>

        <div class="mb-2">
            <span class="block text-[0.6rem] font-bold text-white/30 uppercase tracking-[1.5px] px-6 py-2">Persuratan</span>
            <a href="{{ route('surat-masuk.index') }}" class="flex items-center gap-3 px-6 py-2.5 text-sm font-medium transition border-l-[3px] {{ request()->routeIs('surat-masuk.*') ? 'text-white bg-white/[0.12] border-l-blue-400' : 'text-white/60 border-l-transparent hover:text-white hover:bg-white/[0.08]' }}">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/></svg>
                <span>Surat Masuk</span>
            </a>
            <a href="{{ route('surat-keluar.index') }}" class="flex items-center gap-3 px-6 py-2.5 text-sm font-medium transition border-l-[3px] {{ request()->routeIs('surat-keluar.*') ? 'text-white bg-white/[0.12] border-l-blue-400' : 'text-white/60 border-l-transparent hover:text-white hover:bg-white/[0.08]' }}">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                <span>Surat Keluar</span>
            </a>
        </div>

        <div class="mb-2">
            <span class="block text-[0.6rem] font-bold text-white/30 uppercase tracking-[1.5px] px-6 py-2">Kearsipan</span>
            <a href="{{ route('arsip.index') }}" class="flex items-center gap-3 px-6 py-2.5 text-sm font-medium transition border-l-[3px] {{ request()->routeIs('arsip.*') ? 'text-white bg-white/[0.12] border-l-blue-400' : 'text-white/60 border-l-transparent hover:text-white hover:bg-white/[0.08]' }}">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 8v13H3V8"/><path d="M1 3h22v5H1z"/><path d="M10 12h4"/></svg>
                <span>Data Arsip</span>
            </a>
            <a href="{{ route('peminjaman.index') }}" class="flex items-center gap-3 px-6 py-2.5 text-sm font-medium transition border-l-[3px] {{ request()->routeIs('peminjaman.*') ? 'text-white bg-white/[0.12] border-l-blue-400' : 'text-white/60 border-l-transparent hover:text-white hover:bg-white/[0.08]' }}">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1" ry="1"/></svg>
                <span>Peminjaman Arsip</span>
            </a>
        </div>

        @if(auth()->user()->isAdmin())
        <div class="mb-2">
            <span class="block text-[0.6rem] font-bold text-white/30 uppercase tracking-[1.5px] px-6 py-2">Administrasi</span>
            <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-6 py-2.5 text-sm font-medium transition border-l-[3px] {{ request()->routeIs('users.*') ? 'text-white bg-white/[0.12] border-l-blue-400' : 'text-white/60 border-l-transparent hover:text-white hover:bg-white/[0.08]' }}">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                <span>Manajemen User</span>
            </a>
            <a href="{{ route('bidang.index') }}" class="flex items-center gap-3 px-6 py-2.5 text-sm font-medium transition border-l-[3px] {{ request()->routeIs('bidang.*') ? 'text-white bg-white/[0.12] border-l-blue-400' : 'text-white/60 border-l-transparent hover:text-white hover:bg-white/[0.08]' }}">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg>
                <span>Manajemen Bidang</span>
            </a>
        </div>
        @endif
    </nav>

    {{-- Footer --}}
    <div class="px-5 py-4 border-t border-white/10 shrink-0">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-blue-400 flex items-center justify-center text-sm font-bold shrink-0">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div>
                <p class="text-xs font-semibold text-white/90 leading-tight">{{ auth()->user()->name }}</p>
                <span class="inline-block mt-0.5 px-2 py-0.5 text-[0.6rem] font-semibold rounded-full {{ auth()->user()->isAdmin() ? 'bg-cyan-500/20 text-cyan-300' : 'bg-slate-400/20 text-slate-300' }}">{{ ucfirst(auth()->user()->role) }}</span>
            </div>
        </div>
    </div>
</aside>
<div id="sidebarOverlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[999] hidden lg:hidden" onclick="toggleSidebar()"></div>
