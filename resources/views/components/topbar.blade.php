@props([
    'pageTitle' => null,
])

<header class="h-16 border-b border-slate-800 bg-slate-950 flex items-center justify-between px-4 md:px-6">
    <div class="flex items-center gap-4">
        <button
            @click="$dispatch('toggle-sidebar')"
            class="lg:hidden p-2 rounded-lg text-slate-400 hover:text-slate-200 hover:bg-slate-800 transition-colors duration-150"
        >
            <x-heroicon-o-bars-3 class="w-5 h-5" />
        </button>

        @if($pageTitle)
            <h1 class="text-lg font-semibold text-slate-100">{{ $pageTitle }}</h1>
        @endif
    </div>

    <div class="flex items-center gap-2">
        <button class="relative p-2 rounded-lg text-slate-400 hover:text-slate-200 hover:bg-slate-800 transition-colors duration-150">
            <x-heroicon-o-bell class="w-5 h-5" />
            <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-emerald-500 rounded-full"></span>
        </button>

        <x-account-dropdown />
    </div>
</header>