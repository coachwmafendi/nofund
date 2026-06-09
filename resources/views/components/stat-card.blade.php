<div class="rounded-xl border border-slate-800 bg-slate-900/50 p-5">
    <div class="flex items-center justify-between">
        <h3 class="text-sm font-medium text-slate-400">{{ $title }}</h3>
        @if(isset($icon))
            <x-dynamic-component :component="'heroicon-o-' . $icon" class="w-5 h-5 text-slate-500" />
        @endif
    </div>
    <p class="mt-2 text-2xl font-semibold text-slate-50">{{ $value }}</p>
    @if(isset($description))
        <p class="mt-1 text-xs text-slate-500">{{ $description }}</p>
    @endif
</div>