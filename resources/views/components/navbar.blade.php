{{-- Navbar --}}
<header class="sticky top-0 z-50 flex items-center justify-between gap-4 px-4 sm:px-8 h-16 bg-white/95 backdrop-blur-lg border-b border-slate-200 shadow-sm">
    <div class="flex items-center gap-4 flex-1 min-w-0">
        <button class="lg:hidden p-2 rounded-lg text-slate-600 hover:bg-slate-100 transition shrink-0" onclick="toggleSidebar()">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
        </button>
        <form action="{{ route('admin.arsip.index') }}" method="GET" class="hidden sm:block w-full max-w-sm">
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="text" name="search" placeholder="Cari di seluruh arsip..." class="w-full bg-slate-100 border-0 rounded-lg pl-9 pr-3 py-2 text-sm text-slate-700 placeholder:text-slate-400 focus:ring-2 focus:ring-primary/30 focus:bg-white transition">
            </div>
        </form>
    </div>
    <div class="flex items-center gap-3 sm:gap-5 shrink-0">
        <div class="hidden sm:flex items-center gap-1.5">
            <span class="text-sm font-semibold text-slate-700">Bappeda Lampung</span>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="#3B82F6" stroke="white" stroke-width="1"><circle cx="12" cy="12" r="10"/><path d="M9 12l2 2 4-4" stroke="white" stroke-width="2" fill="none"/></svg>
        </div>

        {{-- Bell Notifikasi (hanya untuk admin) --}}
        @if(auth()->check() && auth()->user()->isAdmin())
        @php
            $unreadNotifications = auth()->user()->unreadNotifications()->latest()->take(10)->get();
            $unreadCount = $unreadNotifications->count();
        @endphp
        <div class="relative" id="notifBellWrapper">
            {{-- Bell Button --}}
            <button id="notifBellBtn" onclick="toggleNotifDropdown()"
                class="relative p-2 rounded-lg text-slate-500 hover:bg-slate-100 hover:text-slate-700 transition focus:outline-none"
                title="Notifikasi">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                </svg>
                @if($unreadCount > 0)
                    <span class="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] px-1 flex items-center justify-center bg-red-500 text-white text-[10px] font-bold rounded-full leading-none animate-pulse">
                        {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                    </span>
                @endif
            </button>

            {{-- Dropdown Panel --}}
            <div id="notifDropdown"
                class="hidden absolute right-0 top-full mt-2 w-80 sm:w-96 bg-white rounded-xl shadow-2xl border border-slate-200 overflow-hidden z-[9999]">

                {{-- Header --}}
                <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100 bg-slate-50">
                    <div class="flex items-center gap-2">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="2">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                        </svg>
                        <span class="text-sm font-semibold text-slate-700">Notifikasi</span>
                        @if($unreadCount > 0)
                            <span class="px-1.5 py-0.5 text-[10px] font-bold bg-red-100 text-red-600 rounded-full">{{ $unreadCount }} baru</span>
                        @endif
                    </div>
                    @if($unreadCount > 0)
                    <form action="{{ route('admin.notifications.read-all') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-xs text-indigo-500 hover:text-indigo-700 font-medium transition">Tandai semua dibaca</button>
                    </form>
                    @endif
                </div>

                {{-- Notifikasi List --}}
                <div class="max-h-80 overflow-y-auto divide-y divide-slate-100">
                    @forelse($unreadNotifications as $notif)
                    @php $data = $notif->data; @endphp
                    <form action="{{ route('admin.notifications.read', $notif->id) }}" method="POST" class="block">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-3.5 hover:bg-indigo-50 transition group">
                            <div class="flex items-start gap-3">
                                <div class="mt-0.5 shrink-0 w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="2">
                                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-slate-800 leading-tight truncate group-hover:text-indigo-700">
                                        Pengajuan Peminjaman Baru
                                    </p>
                                    <p class="text-xs text-slate-500 mt-0.5 leading-snug">
                                        <span class="font-medium text-slate-700">{{ $data['nama_peminjam'] ?? '-' }}</span>
                                        dari <span class="font-medium text-slate-700">{{ $data['bidang_peminjam'] ?? '-' }}</span>
                                    </p>
                                    <p class="text-xs text-slate-400 mt-0.5 truncate">
                                        {{ $data['no_berkas'] ?? '' }} — {{ $data['judul_arsip'] ?? '' }}
                                    </p>
                                    <p class="text-[10px] text-slate-400 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                                </div>
                                <span class="mt-1 shrink-0 w-2 h-2 rounded-full bg-indigo-500"></span>
                            </div>
                        </button>
                    </form>
                    @empty
                    <div class="px-4 py-8 text-center text-slate-400">
                        <svg class="mx-auto mb-2 text-slate-300" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                        </svg>
                        <p class="text-xs font-medium">Tidak ada notifikasi baru</p>
                    </div>
                    @endforelse
                </div>

                {{-- Footer --}}
                <div class="px-4 py-2.5 border-t border-slate-100 bg-slate-50 text-center">
                    <a href="{{ route('admin.peminjaman.index', ['status' => 'menunggu_persetujuan']) }}"
                        class="text-xs text-indigo-500 hover:text-indigo-700 font-medium transition">
                        Lihat semua pengajuan peminjaman →
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>
</header>

<script>
function toggleNotifDropdown() {
    const d = document.getElementById('notifDropdown');
    d.classList.toggle('hidden');
}
// Klik luar dropdown = tutup
document.addEventListener('click', function(e) {
    const wrapper = document.getElementById('notifBellWrapper');
    if (wrapper && !wrapper.contains(e.target)) {
        document.getElementById('notifDropdown')?.classList.add('hidden');
    }
});
</script>