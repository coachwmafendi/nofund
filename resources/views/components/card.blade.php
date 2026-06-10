@props([
    'title' => null,
    'description' => null,
    'actions' => null,
    'slot',
    'footer' => null,
])

<div class="rounded-xl border border-slate-800 bg-slate-900/50">
    @if(isset($title) || isset($actions))
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-800">
            <div>
                @if(isset($title))
                    <h3 class="font-semibold text-slate-50">{{ $title }}</h3>
                @endif
                @if(isset($description))
                    <p class="text-sm text-slate-400 mt-0.5">{{ $description }}</p>
                @endif
            </div>
            @if(isset($actions))
                <div class="flex items-center gap-2">{{ $actions }}</div>
            @endif
        </div>
    @endif
    <div class="p-5">{{ $slot }}</div>
    @if(isset($footer))
        <div class="px-5 py-3 border-t border-slate-800">{{ $footer }}</div>
    @endif
</div>