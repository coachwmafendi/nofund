@props([
    'icon' => null,
    'title' => null,
    'description' => null,
    'actions' => null,
])

<div class="flex flex-col items-center justify-center py-12 text-center">
    <x-dynamic-component :component="'heroicon-o-' . $icon" class="w-12 h-12 text-slate-600 mb-4" />
    <h3 class="text-sm font-semibold text-slate-200">{{ $title }}</h3>
    <p class="mt-1 text-sm text-slate-500 max-w-xs">{{ $description }}</p>
    @if(isset($actions))
        <div class="mt-6">{{ $actions }}</div>
    @endif
</div>