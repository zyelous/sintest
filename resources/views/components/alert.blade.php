@if(session('success'))
<div id="flashMsg" class="flex items-center gap-3 mx-4 sm:mx-8 mt-4 px-4 py-3 rounded-lg text-sm font-medium bg-emerald-50 text-emerald-800 border-l-4 border-emerald-500 animate-slide-down">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
    <span class="flex-1">{{ session('success') }}</span>
    <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800 text-lg leading-none">&times;</button>
</div>
@endif

@if(session('error'))
<div id="flashMsg" class="flex items-center gap-3 mx-4 sm:mx-8 mt-4 px-4 py-3 rounded-lg text-sm font-medium bg-red-50 text-red-800 border-l-4 border-red-500 animate-slide-down">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
    <span class="flex-1">{{ session('error') }}</span>
    <button onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-800 text-lg leading-none">&times;</button>
</div>
@endif

@if(session('warning'))
<div id="flashMsg" class="flex items-center gap-3 mx-4 sm:mx-8 mt-4 px-4 py-3 rounded-lg text-sm font-medium bg-amber-50 text-amber-800 border-l-4 border-amber-500 animate-slide-down">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
    <span class="flex-1">{{ session('warning') }}</span>
    <button onclick="this.parentElement.remove()" class="text-amber-600 hover:text-amber-800 text-lg leading-none">&times;</button>
</div>
@endif
