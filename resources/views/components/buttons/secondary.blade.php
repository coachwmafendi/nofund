@props(['type' => 'button'])

<button
    type="{{ $type }}"
    {{ $attributes->merge([
        'class' => 'inline-flex items-center justify-center gap-2 rounded-lg bg-slate-800 px-4 py-2 text-sm font-medium text-slate-200 border border-slate-700 transition-colors hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/50'
    ]) }}
>
    {{ $slot }}
</button>