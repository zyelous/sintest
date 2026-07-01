{{-- Sidebar --}}
<aside id="sidebar" class="fixed top-0 left-0 w-[260px] h-screen bg-primary-dark text-white z-[1000] flex flex-col transition-transform duration-300 shadow-xl -translate-x-full lg:translate-x-0 sidebar-scroll overflow-y-auto">
    {{-- Logo --}}
    <div class="px-5 py-5 shrink-0">
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-lg bg-white/10 flex items-center justify-center shrink-0 ring-1 ring-white/15">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#F5B942" stroke-width="2"><path d="M12 2 3 6v6c0 5 4 9 9 10 5-1 9-5 9-10V6z"/><path d="M9 12l2 2 4-4" stroke="#F5B942"/></svg>
            </div>
            <div>
                <h2 class="text-lg font-extrabold tracking-wide leading-tight">SINTARA</h2>
                <p class="text-[0.6rem] text-white/50 uppercase tracking-wider leading-tight">Bappeda Provinsi<br>Lampung</p>
            </div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 py-3 overflow-y-auto px-3">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3.5 py-2.5 mb-1 rounded-lg text-sm font-semibold transition {{ request()->routeIs('dashboard') ? 'bg-accent-gold text-primary-dark' : 'text-white/70 hover:text-white hover:bg-white/[0.08]' }}">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
            <span>Beranda</span>
        </a>

        <a href="{{ route('surat-masuk.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 mb-1 rounded-lg text-sm font-semibold transition {{ request()->routeIs('surat-masuk.*', 'surat-keluar.*') ? 'bg-accent-gold text-primary-dark' : 'text-white/70 hover:text-white hover:bg-white/[0.08]' }}">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/></svg>
            <span>Arsip Surat</span>
        </a>

        <a href="{{ route('arsip.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 mb-1 rounded-lg text-sm font-semibold transition {{ request()->routeIs('arsip.*') ? 'bg-accent-gold text-primary-dark' : 'text-white/70 hover:text-white hover:bg-white/[0.08]' }}">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 8v11a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V8"/><path d="M22 8H2l1.5-4h17z"/><path d="M12 12v4"/><path d="M10 14h4"/></svg>
            <span>Manajemen Arsip</span>
        </a>

        <a href="{{ route('laporan.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 mb-1 rounded-lg text-sm font-semibold transition {{ request()->routeIs('laporan.*') ? 'bg-accent-gold text-primary-dark' : 'text-white/70 hover:text-white hover:bg-white/[0.08]' }}">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
            <span>Laporan</span>
        </a>

        @if(auth()->user()->isAdmin())
        <a href="{{ route('bidang.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 mb-1 rounded-lg text-sm font-semibold transition {{ request()->routeIs('bidang.*') ? 'bg-accent-gold text-primary-dark' : 'text-white/70 hover:text-white hover:bg-white/[0.08]' }}">
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
