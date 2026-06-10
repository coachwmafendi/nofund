@props([
    'variant' => 'default',
    'slot',
])

<span @class([
    'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium',
    'bg-emerald-500/10 text-emerald-400' => $variant === 'success',
    'bg-amber-500/10 text-amber-400' => $variant === 'warning',
    'bg-red-500/10 text-red-400' => $variant === 'danger',
    'bg-sky-500/10 text-sky-400' => $variant === 'info',
    'bg-slate-700/50 text-slate-400' => $variant === 'default' || $variant === '',
])>
    {{ $slot }}
</span>