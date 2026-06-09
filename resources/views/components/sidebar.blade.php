<div
    x-data="{ open: false }"
    @toggle-sidebar.window="open = !open"
>
    <div
        x-show="open"
        x-cloak
        class="fixed inset-0 z-40 bg-black/60 lg:hidden"
        @click="open = false"
    ></div>

    <aside
        x-show="open"
        x-cloak
        x-transition:enter="transition-transform duration-200"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition-transform duration-200"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 border-r border-slate-800 flex flex-col lg:hidden"
    >
        <div class="flex items-center justify-between h-16 px-4 border-b border-slate-800">
            <a href="/dashboard" class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-emerald-600 flex items-center justify-center">
                    <x-heroicon-s-heart class="w-5 h-5 text-white" />
                </div>
                <span class="text-lg font-semibold text-slate-100">nofund</span>
            </a>
            <button
                @click="open = false"
                class="p-2 rounded-lg text-slate-400 hover:text-slate-200 hover:bg-slate-800 transition-colors duration-150"
            >
                <x-heroicon-o-x-mark class="w-5 h-5" />
            </button>
        </div>

        <nav class="flex-1 overflow-y-auto py-4">
            <x-sidebar-group label="Main">
                <x-sidebar-item href="/dashboard" icon="home" label="Dashboard" :active="request()->is('dashboard')" />
                <x-sidebar-item href="/campaigns" icon="megaphone" label="Campaigns" :active="request()->is('campaigns*')" />
                <x-sidebar-item href="/donations" icon="banknotes" label="Donations" :active="request()->is('donations*')" />
                <x-sidebar-item href="/donors" icon="users" label="Donors" :active="request()->is('donors*')" />
                <x-sidebar-item href="/recurring-plans" icon="arrow-path" label="Recurring Plans" :active="request()->is('recurring-plans*')" />
            </x-sidebar-group>

            <x-sidebar-group label="Finance">
                <x-sidebar-item href="/payouts" icon="wallet" label="Payouts" :active="request()->is('payouts*')" />
                <x-sidebar-item href="/transactions" icon="document-text" label="Transactions" :active="request()->is('transactions*')" />
                <x-sidebar-item href="/refunds" icon="receipt-refund" label="Refunds" :active="request()->is('refunds*')" />
                <x-sidebar-item href="/reports" icon="chart-bar" label="Reports" :active="request()->is('reports*')" />
            </x-sidebar-group>

            <x-sidebar-group label="Organization">
                <x-sidebar-item href="/members" icon="user-group" label="Members" :active="request()->is('members*')" />
                <x-sidebar-item href="/teams" icon="rectangle-group" label="Teams" :active="request()->is('teams*')" />
                <x-sidebar-item href="/settings/general" icon="cog-6-tooth" label="Settings" :active="request()->is('settings*')" />
            </x-sidebar-group>

            <x-sidebar-group label="Developer">
                <x-sidebar-item href="/developer/api-keys" icon="code-bracket" label="API Keys" :active="request()->is('developer/api-keys*')" />
                <x-sidebar-item href="/developer/webhooks" icon="globe-alt" label="Webhooks" :active="request()->is('developer/webhooks*')" />
                <x-sidebar-item href="/developer/embed-forms" icon="window" label="Embed Forms" :active="request()->is('developer/embed-forms*')" />
            </x-sidebar-group>
        </nav>
    </aside>

    <aside class="hidden lg:flex lg:flex-col lg:w-64 lg:fixed lg:inset-y-0 bg-slate-900 border-r border-slate-800">
        <div class="flex items-center h-16 px-4 border-b border-slate-800">
            <a href="/dashboard" class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-emerald-600 flex items-center justify-center">
                    <x-heroicon-s-heart class="w-5 h-5 text-white" />
                </div>
                <span class="text-lg font-semibold text-slate-100">nofund</span>
            </a>
        </div>

        <nav class="flex-1 overflow-y-auto py-4">
            <x-sidebar-group label="Main">
                <x-sidebar-item href="/dashboard" icon="home" label="Dashboard" :active="request()->is('dashboard')" />
                <x-sidebar-item href="/campaigns" icon="megaphone" label="Campaigns" :active="request()->is('campaigns*')" />
                <x-sidebar-item href="/donations" icon="banknotes" label="Donations" :active="request()->is('donations*')" />
                <x-sidebar-item href="/donors" icon="users" label="Donors" :active="request()->is('donors*')" />
                <x-sidebar-item href="/recurring-plans" icon="arrow-path" label="Recurring Plans" :active="request()->is('recurring-plans*')" />
            </x-sidebar-group>

            <x-sidebar-group label="Finance">
                <x-sidebar-item href="/payouts" icon="wallet" label="Payouts" :active="request()->is('payouts*')" />
                <x-sidebar-item href="/transactions" icon="document-text" label="Transactions" :active="request()->is('transactions*')" />
                <x-sidebar-item href="/refunds" icon="receipt-refund" label="Refunds" :active="request()->is('refunds*')" />
                <x-sidebar-item href="/reports" icon="chart-bar" label="Reports" :active="request()->is('reports*')" />
            </x-sidebar-group>

            <x-sidebar-group label="Organization">
                <x-sidebar-item href="/members" icon="user-group" label="Members" :active="request()->is('members*')" />
                <x-sidebar-item href="/teams" icon="rectangle-group" label="Teams" :active="request()->is('teams*')" />
                <x-sidebar-item href="/settings/general" icon="cog-6-tooth" label="Settings" :active="request()->is('settings*')" />
            </x-sidebar-group>

            <x-sidebar-group label="Developer">
                <x-sidebar-item href="/developer/api-keys" icon="code-bracket" label="API Keys" :active="request()->is('developer/api-keys*')" />
                <x-sidebar-item href="/developer/webhooks" icon="globe-alt" label="Webhooks" :active="request()->is('developer/webhooks*')" />
                <x-sidebar-item href="/developer/embed-forms" icon="window" label="Embed Forms" :active="request()->is('developer/embed-forms*')" />
            </x-sidebar-group>
        </nav>
    </aside>
</div>