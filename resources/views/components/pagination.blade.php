@if($paginator->hasPages())
<nav class="flex justify-center mt-4">
    <ul class="flex gap-1">
        @if($paginator->onFirstPage())
            <li><span class="flex items-center justify-center w-9 h-9 rounded-md text-sm text-slate-400 bg-white border border-slate-200 cursor-not-allowed">&laquo;</span></li>
        @else
            <li><a href="{{ $paginator->previousPageUrl() }}" class="flex items-center justify-center w-9 h-9 rounded-md text-sm text-slate-600 bg-white border border-slate-200 hover:bg-blue-50 hover:border-blue-400 hover:text-blue-600 transition">&laquo;</a></li>
        @endif
        @foreach($elements as $element)
            @if(is_string($element))
                <li><span class="flex items-center justify-center w-9 h-9 rounded-md text-sm text-slate-400 bg-white border border-slate-200">{{ $element }}</span></li>
            @endif
            @if(is_array($element))
                @foreach($element as $page => $url)
                    @if($page == $paginator->currentPage())
                        <li><span class="flex items-center justify-center w-9 h-9 rounded-md text-sm font-semibold text-white bg-primary border border-primary">{{ $page }}</span></li>
                    @else
                        <li><a href="{{ $url }}" class="flex items-center justify-center w-9 h-9 rounded-md text-sm text-slate-600 bg-white border border-slate-200 hover:bg-blue-50 hover:border-blue-400 hover:text-blue-600 transition">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach
        @if($paginator->hasMorePages())
            <li><a href="{{ $paginator->nextPageUrl() }}" class="flex items-center justify-center w-9 h-9 rounded-md text-sm text-slate-600 bg-white border border-slate-200 hover:bg-blue-50 hover:border-blue-400 hover:text-blue-600 transition">&raquo;</a></li>
        @else
            <li><span class="flex items-center justify-center w-9 h-9 rounded-md text-sm text-slate-400 bg-white border border-slate-200 cursor-not-allowed">&raquo;</span></li>
        @endif
    </ul>
</nav>
@endif
