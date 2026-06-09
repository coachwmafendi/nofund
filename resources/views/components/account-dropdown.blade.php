@props([
    'user' => null,
])

@php
$userName = $user['name'] ?? 'User Name';
$userEmail = $user['email'] ?? 'user@example.com';
@endphp

<div x-data="{ open: false }" class="relative">
    <button
        @click="open = !open"
        class="flex items-center gap-3 p-1.5 rounded-lg hover:bg-slate-800 transition-colors duration-150"
    >
        <div class="w-8 h-8 rounded-full bg-slate-700 flex items-center justify-center text-sm font-medium text-slate-300">
            {{ substr($userName, 0, 1) }}
        </div>
        <div class="hidden md:block text-left">
            <p class="text-sm font-medium text-slate-200">{{ $userName }}</p>
            <p class="text-xs text-slate-500">{{ $userEmail }}</p>
        </div>
    </button>

    <div
        x-show="open"
        x-cloak
        @click.away="open = false"
        x-transition
        class="absolute right-0 mt-2 w-56 rounded-lg border border-slate-800 bg-slate-900 shadow-lg py-1 z-50"
    >
        <div class="px-4 py-3 border-b border-slate-800">
            <p class="text-sm font-medium text-slate-200">{{ $userName }}</p>
            <p class="text-xs text-slate-500">{{ $userEmail }}</p>
        </div>

        <div class="py-1">
            <a href="/organization" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-400 hover:text-slate-200 hover:bg-slate-800 transition-colors duration-150">
                <x-heroicon-o-building-office class="w-4 h-4" />
                Organization
            </a>
            <a href="/members" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-400 hover:text-slate-200 hover:bg-slate-800 transition-colors duration-150">
                <x-heroicon-o-user-group class="w-4 h-4" />
                Members
            </a>
            <a href="/billing" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-400 hover:text-slate-200 hover:bg-slate-800 transition-colors duration-150">
                <x-heroicon-o-credit-card class="w-4 h-4" />
                Billing
            </a>
        </div>

        <div class="py-1 border-t border-slate-800">
            <a href="/settings" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-400 hover:text-slate-200 hover:bg-slate-800 transition-colors duration-150">
                <x-heroicon-o-cog-6-tooth class="w-4 h-4" />
                Settings
            </a>
            <a href="/developer/api-keys" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-400 hover:text-slate-200 hover:bg-slate-800 transition-colors duration-150">
                <x-heroicon-o-code-bracket class="w-4 h-4" />
                API Keys
            </a>
        </div>

        <div class="py-1 border-t border-slate-800">
            <form method="POST" action="/logout">
                @csrf
                <button type="submit" class="flex items-center gap-2 w-full px-4 py-2 text-sm text-red-400 hover:text-red-300 hover:bg-slate-800 transition-colors duration-150">
                    <x-heroicon-o-arrow-right-on-rectangle class="w-4 h-4" />
                    Logout
                </button>
            </form>
        </div>
    </div>
</div>