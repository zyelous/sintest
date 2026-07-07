{{-- Navbar --}}
<header class="sticky top-0 z-50 flex items-center justify-between gap-4 px-4 sm:px-8 h-16 bg-white/95 backdrop-blur-lg border-b border-slate-200 shadow-sm">
    <div class="flex items-center gap-4 flex-1 min-w-0">
        <button class="lg:hidden p-2 rounded-lg text-slate-600 hover:bg-slate-100 transition shrink-0" onclick="toggleSidebar()">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
        </button>
        <form action="{{ route('arsip.search') }}" method="GET" class="hidden sm:block w-full max-w-md">
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="text" name="search" placeholder="{{ auth()->user()->isOperator() ? 'Cari dokumen, boks, atau user...' : 'Cari di seluruh arsip...' }}" class="w-full bg-slate-100 border-0 rounded-lg pl-9 pr-3 py-2 text-sm text-slate-700 placeholder:text-slate-400 focus:ring-2 focus:ring-primary/30 focus:bg-white transition">
            </div>
        </form>
    </div>
    <div class="flex items-center gap-3 sm:gap-4 shrink-0">
        {{-- Notification bell --}}
        <button class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 transition relative" title="Notifikasi">
            <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
        </button>
        {{-- Help --}}
        <button class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 transition" title="Bantuan">
            <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        </button>
        {{-- User info --}}
        <div class="hidden sm:flex items-center gap-3 pl-4 border-l border-slate-200">
            <div class="text-right">
                @if(auth()->user()->isOperator())
                    <p class="text-sm font-bold text-slate-800">Operator {{ auth()->user()->bidang->nama_bidang ?? '' }}</p>
                    <p class="text-[0.65rem] font-semibold text-slate-400 uppercase tracking-wider">Bappeda Lampung</p>
                @else
                    <p class="text-sm font-bold text-slate-800">{{ auth()->user()->name }}</p>
                    <p class="text-[0.65rem] font-semibold text-slate-400 uppercase tracking-wider">Administrator</p>
                @endif
            </div>
            <div class="w-9 h-9 rounded-full bg-primary flex items-center justify-center text-white text-xs font-bold ring-2 ring-primary/20">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}{{ strtoupper(substr(auth()->user()->name, strpos(auth()->user()->name, ' ') + 1, 1)) }}
            </div>
        </div>
    </div>
</header>
