<div>
    <x-page-header title="Settings" description="Manage your organization preferences." />

    <!-- Settings Tabs -->
    <div class="flex items-center gap-1 border-b border-slate-800 pb-px mb-6">
        <a href="{{ route('settings.general') }}" @class([
            'px-3 py-2 text-sm font-medium border-b-2 transition-colors',
            'border-emerald-500 text-emerald-400' => false,
            'border-transparent text-slate-400 hover:text-slate-200' => true,
        ])>
            General
        </a>
        <a href="{{ route('settings.members') }}" @class([
            'px-3 py-2 text-sm font-medium border-b-2 transition-colors',
            'border-emerald-500 text-emerald-400' => false,
            'border-transparent text-slate-400 hover:text-slate-200' => true,
        ])>
            Members
        </a>
        <a href="{{ route('settings.billing') }}" @class([
            'px-3 py-2 text-sm font-medium border-b-2 transition-colors',
            'border-emerald-500 text-emerald-400' => true,
            'border-transparent text-slate-400 hover:text-slate-200' => false,
        ])>
            Billing
        </a>
        <a href="{{ route('settings.receipts') }}" @class([
            'px-3 py-2 text-sm font-medium border-b-2 transition-colors',
            'border-emerald-500 text-emerald-400' => false,
            'border-transparent text-slate-400 hover:text-slate-200' => true,
        ])>
            Receipts
        </a>
        <a href="{{ route('settings.security') }}" @class([
            'px-3 py-2 text-sm font-medium border-b-2 transition-colors',
            'border-emerald-500 text-emerald-400' => false,
            'border-transparent text-slate-400 hover:text-slate-200' => true,
        ])>
            Security
        </a>
    </div>

    <x-card>
        <x-empty-state
            icon="credit-card"
            title="Billing"
            description="Billing and subscription management will be available soon."
        />
    </x-card>
</div>