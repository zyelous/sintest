@php
    $rp = auth()->user()->isAdmin() ? 'admin.' : 'operator.';
@endphp
{{-- Sidebar --}}
<aside id="sidebar" class="fixed top-0 left-0 w-[260px] h-screen bg-primary-dark text-white z-[1000] flex flex-col transition-transform duration-300 shadow-xl -translate-x-full lg:translate-x-0 sidebar-scroll overflow-y-auto">
    {{-- Logo --}}
    <div class="px-5 py-5 shrink-0">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/logo_lampung.png') }}" alt="Logo Lampung" class="w-14 h-14 object-contain shrink-0">
            <div>
                <h2 class="text-lg font-extrabold tracking-wide leading-tight">SINTARA</h2>
                <p class="text-[0.6rem] text-white/50 uppercase tracking-wider leading-tight">Bappeda Provinsi<br>Lampung</p>
            </div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 py-3 overflow-y-auto px-3">
        <a href="{{ route($rp.'dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 mb-1 rounded-lg text-sm font-semibold transition {{ request()->routeIs('admin.dashboard', 'operator.dashboard') ? 'bg-accent-gold text-primary-dark' : 'text-white/70 hover:text-white hover:bg-white/[0.08]' }}">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
            <span>Beranda</span>
        </a>

        <a href="{{ route($rp.'arsip.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 mb-1 rounded-lg text-sm font-semibold transition {{ request()->routeIs('admin.arsip.*', 'operator.arsip.*') ? 'bg-accent-gold text-primary-dark' : 'text-white/70 hover:text-white hover:bg-white/[0.08]' }}">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 8v11a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V8"/><path d="M22 8H2l1.5-4h17z"/><path d="M12 12v4"/><path d="M10 14h4"/></svg>
            <span>Manajemen Arsip</span>
        </a>

        <a href="{{ route($rp.'peminjaman.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 mb-1 rounded-lg text-sm font-semibold transition {{ request()->routeIs('admin.peminjaman.*', 'operator.peminjaman.*') ? 'bg-accent-gold text-primary-dark' : 'text-white/70 hover:text-white hover:bg-white/[0.08]' }}">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/><line x1="10" y1="8" x2="15" y2="8"/></svg>
            <span>Peminjaman Arsip</span>
        </a>

        @if(auth()->user()->isAdmin())
        <a href="{{ route('admin.laporan.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 mb-1 rounded-lg text-sm font-semibold transition {{ request()->routeIs('admin.laporan.*') ? 'bg-accent-gold text-primary-dark' : 'text-white/70 hover:text-white hover:bg-white/[0.08]' }}">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
            <span>Laporan</span>
        </a>

        <a href="{{ route('admin.bidang.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 mb-1 rounded-lg text-sm font-semibold transition {{ request()->routeIs('admin.bidang.*') ? 'bg-accent-gold text-primary-dark' : 'text-white/70 hover:text-white hover:bg-white/[0.08]' }}">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 3.5-6 8-6s8 2 8 6"/><circle cx="18" cy="6" r="2.2" opacity=".6"/></svg>
            <span>Manajemen Bidang</span>
        </a>
        @endif
    </nav>

    {{-- Footer --}}
    <div class="px-5 py-4 border-t border-white/10 shrink-0">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center text-sm font-bold shrink-0 ring-1 ring-white/15">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div class="min-w-0 flex-1">
                <p class="text-xs font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                <p class="text-[0.65rem] text-white/50">{{ auth()->user()->isAdmin() ? 'Administrator Utama' : 'Operator Bidang' }}</p>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" title="Logout" class="p-1.5 rounded-md text-white/50 hover:text-white hover:bg-white/10 transition">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                </button>
            </form>
        </div>
    </div>
</aside>
<div id="sidebarOverlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[999] hidden lg:hidden" onclick="toggleSidebar()"></div>