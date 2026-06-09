<select
    {{ $attributes->merge([
        'class' => 'block w-full rounded-lg border border-slate-700 bg-slate-900/50 px-3 py-2 text-sm text-slate-50 focus:border-emerald-500/50 focus:ring-1 focus:ring-emerald-500/50 focus:outline-none transition-colors'
    ]) }}
>
    {{ $slot }}
</select>