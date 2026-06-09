<div
    x-data="{ open: {{ $show ? 'true' : 'false' }} }"
    @keydown.escape.window="open = false"
    x-cloak
>
    <div
        x-show="open"
        x-transition
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
    >
        <div
            x-show="open"
            x-transition
            @click.away="open = false"
            class="w-full max-w-md rounded-xl border border-slate-800 bg-slate-900 p-6 shadow-xl"
        >
            @if(isset($title))
                <h3 class="text-lg font-semibold text-slate-50">{{ $title }}</h3>
            @endif

            <div class="mt-4">{{ $slot }}</div>

            @if(isset($footer))
                <div class="mt-6 flex items-center justify-end gap-3">{{ $footer }}</div>
            @endif
        </div>
    </div>
</div>