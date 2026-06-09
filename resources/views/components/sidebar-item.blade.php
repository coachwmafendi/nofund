@props([
    'href',
    'icon' => null,
    'label',
    'active' => false,
])

<a
    href="{{ $href }}"
    @class([
        'flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors duration-150',
        'bg-slate-800/60 text-emerald-400 border-r-2 border-emerald-500' => $active,
        'text-slate-400 hover:text-slate-50 hover:bg-slate-800' => !$active,
    ])
>
    @if($icon)
        <x-dynamic-component
            :component="'heroicon-o-' . $icon"
            class="w-5 h-5 flex-shrink-0"
        />
    @endif
    <span>{{ $label }}</span>
</a>