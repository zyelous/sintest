<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - SINTARA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])    
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
</head>
<body class="font-sans antialiased bg-slate-100 text-slate-800">
    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        @include('components.sidebar')

        {{-- Main --}}
        <div class="flex-1 ml-0 lg:ml-[260px] min-h-screen flex flex-col transition-all duration-300">
            @include('components.navbar')
            @include('components.alert')
            <main class="flex-1 p-4 sm:p-6 lg:p-8 bg-slate-50">
                @hasSection('breadcrumb')
                <div class="flex items-center gap-1.5 text-xs text-slate-500 mb-4">@yield('breadcrumb')</div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>

    {{-- Delete Modal --}}
    <div id="deleteModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[9999] hidden items-center justify-center p-6">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-md animate-fade-in-up">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200">
                <h3 class="text-base font-semibold text-slate-800">Konfirmasi Hapus</h3>
                <button onclick="closeDeleteModal()" class="text-slate-400 hover:text-slate-700 text-2xl leading-none">&times;</button>
            </div>
            <div class="p-6 text-center">
                <svg class="mx-auto mb-4" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#EF4444" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <p class="font-semibold text-slate-800">Apakah Anda yakin ingin menghapus data ini?</p>
                <p class="text-sm text-slate-500 mt-1">Data yang dihapus tidak dapat dikembalikan.</p>
            </div>
            <div class="flex justify-end gap-3 px-6 py-4 border-t border-slate-100">
                <button onclick="closeDeleteModal()" class="px-4 py-2 text-sm font-semibold rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-50 transition">Batal</button>
                <form id="deleteForm" method="POST" class="inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="px-4 py-2 text-sm font-semibold rounded-lg bg-red-500 text-white hover:bg-red-600 transition">Hapus</button>
                </form>
            </div>
        </div>
    </div>

    <script>
    // Sidebar toggle
    function toggleSidebar() {
        const s = document.getElementById('sidebar');
        const o = document.getElementById('sidebarOverlay');
        s.classList.toggle('-translate-x-full');
        o.classList.toggle('hidden');
        document.body.classList.toggle('overflow-hidden');
    }
    // Delete modal
    function confirmDelete(url) {
        document.getElementById('deleteForm').action = url;
        const m = document.getElementById('deleteModal');
        m.classList.remove('hidden');
        m.classList.add('flex');
    }
    function closeDeleteModal() {
        const m = document.getElementById('deleteModal');
        m.classList.add('hidden');
        m.classList.remove('flex');
    }
    // Auto-dismiss flash
    setTimeout(() => { document.getElementById('flashMsg')?.remove(); }, 5000);
    </script>
    @stack('scripts')
</body>
</html>
