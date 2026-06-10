@props(['tabs' => [], 'activeKey' => '', 'onChange' => null])

<div>
    <nav class="flex items-center gap-1 border-b border-slate-800 pb-px">
        @foreach($tabs as $tab)
            <button
                type="button"
                @if($onChange)
                    wire:click="{{ $onChange }}('{{ $tab['key'] }}')"
                @endif
                @class([
                    'px-3 py-2 text-sm font-medium border-b-2 transition-colors',
                    'border-emerald-500 text-emerald-400' => $activeKey === $tab['key'],
                    'border-transparent text-slate-400 hover:text-slate-200' => $activeKey !== $tab['key'],
                ])
            >
                {{ $tab['label'] }}
            </button>
        @endforeach
    </nav>

    <div class="mt-4">{{ $slot }}</div>
</div>