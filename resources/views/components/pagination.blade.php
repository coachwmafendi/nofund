@if($links && $links->lastPage() > 1)
    <nav role="navigation" aria-label="Pagination" class="flex items-center gap-1">
        {{-- Previous --}}
        @if($links->onFirstPage())
            <span class="px-3 py-2 text-sm font-medium text-slate-600 cursor-not-allowed">
                <span class="sr-only">Previous</span>
                <x-heroicon-o-chevron-left class="w-4 h-4" />
            </span>
        @else
            <a
                href="{{ $links->previousPageUrl() }}"
                class="px-3 py-2 text-sm font-medium text-slate-400 hover:bg-slate-800 hover:text-slate-200 rounded-lg transition-colors"
            >
                <span class="sr-only">Previous</span>
                <x-heroicon-o-chevron-left class="w-4 h-4" />
            </a>
        @endif

        {{-- Page Numbers --}}
        @foreach($links->getUrlRange(1, $links->lastPage()) as $page => $url)
            @if($page == $links->currentPage())
                <span
                    class="px-3 py-2 text-sm font-medium bg-slate-800 text-slate-200 border border-slate-700 rounded-lg"
                    aria-current="page"
                >
                    {{ $page }}
                </span>
            @else
                <a
                    href="{{ $url }}"
                    class="px-3 py-2 text-sm font-medium bg-slate-900 border border-slate-800 text-slate-400 hover:bg-slate-800 hover:text-slate-200 rounded-lg transition-colors"
                >
                    {{ $page }}
                </a>
            @endif
        @endforeach

        {{-- Next --}}
        @if($links->hasMorePages())
            <a
                href="{{ $links->nextPageUrl() }}"
                class="px-3 py-2 text-sm font-medium text-slate-400 hover:bg-slate-800 hover:text-slate-200 rounded-lg transition-colors"
            >
                <span class="sr-only">Next</span>
                <x-heroicon-o-chevron-right class="w-4 h-4" />
            </a>
        @else
            <span class="px-3 py-2 text-sm font-medium text-slate-600 cursor-not-allowed">
                <span class="sr-only">Next</span>
                <x-heroicon-o-chevron-right class="w-4 h-4" />
            </span>
        @endif
    </nav>
@endif