<button
    type="{{ $type ?? 'button' }}"
    {{ $attributes->merge([
        'class' => 'inline-flex items-center justify-center gap-2 rounded-lg bg-red-600/90 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-500/50'
    ]) }}
>
    {{ $slot }}
</button>