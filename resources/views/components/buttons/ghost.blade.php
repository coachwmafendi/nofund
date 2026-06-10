@props(['type' => 'button'])

<button
    type="{{ $type }}"
    {{ $attributes->merge([
        'class' => 'inline-flex items-center justify-center gap-2 rounded-lg px-4 py-2 text-sm font-medium text-slate-400 transition-colors hover:text-slate-50 hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500/50'
    ]) }}
>
    {{ $slot }}
</button>