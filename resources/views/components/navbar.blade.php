{{-- Navbar --}}
<header class="sticky top-0 z-50 flex items-center justify-between px-4 sm:px-8 h-16 bg-white/95 backdrop-blur-lg border-b border-slate-200 shadow-sm">
    <div class="flex items-center gap-4">
        <button class="lg:hidden p-2 rounded-lg text-slate-600 hover:bg-slate-100 transition" onclick="toggleSidebar()">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
        </button>
        <div class="flex items-center gap-2 text-sm text-slate-500">
            @yield('breadcrumb')
        </div>
    </div>
    <div class="flex items-center gap-4">
        <div class="hidden sm:flex items-center gap-2">
            <span class="text-sm font-semibold text-slate-700">{{ auth()->user()->name }}</span>
            <span class="px-2 py-0.5 text-[0.65rem] font-semibold rounded-full {{ auth()->user()->isAdmin() ? 'bg-primary/10 text-primary' : 'bg-cyan-100 text-cyan-700' }}">{{ ucfirst(auth()->user()->role) }}</span>
            @if(auth()->user()->bidang)
                <span class="text-xs text-slate-400">· {{ auth()->user()->bidang->nama_bidang }}</span>
            @endif
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-semibold rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-50 transition" title="Logout">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                <span class="hidden sm:inline">Logout</span>
            </button>
        </form>
    </div>
</header>
