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
            'border-emerald-500 text-emerald-400' => false,
            'border-transparent text-slate-400 hover:text-slate-200' => true,
        ])>
            Billing
        </a>
        <a href="{{ route('settings.receipts') }}" @class([
            'px-3 py-2 text-sm font-medium border-b-2 transition-colors',
            'border-emerald-500 text-emerald-400' => true,
            'border-transparent text-slate-400 hover:text-slate-200' => false,
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

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-emerald-500/10 border border-emerald-500/20 px-4 py-3 text-sm text-emerald-400">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit="save">
        <x-card>
            <x-slot:title>Receipt Templates</x-slot:title>
            <x-slot:description>Customize your donation receipt templates.</x-slot:description>

            <div class="grid gap-5">
                <x-form.field>
                    <x-form.label for="headerText">Receipt Header Text</x-form.label>
                    <x-form.textarea wire:model="headerText" id="headerText" name="headerText" placeholder="Thank you for your generous donation..." rows="2" />
                    <x-form.error name="headerText" />
                </x-form.field>

                <x-form.field>
                    <x-form.label for="footerText">Receipt Footer Text</x-form.label>
                    <x-form.textarea wire:model="footerText" id="footerText" name="footerText" placeholder="This receipt is issued for tax purposes..." rows="2" />
                    <x-form.error name="footerText" />
                </x-form.field>

                <x-form.field>
                    <div class="flex items-center gap-3">
                        <input
                            type="checkbox"
                            wire:model="showLogo"
                            id="showLogo"
                            class="h-4 w-4 rounded border-slate-700 bg-slate-900/50 text-emerald-600 focus:ring-emerald-500/50"
                        />
                        <x-form.label for="showLogo" class="!mb-0">Show Organization Logo on Receipts</x-form.label>
                    </div>
                    <x-form.error name="showLogo" />
                </x-form.field>
            </div>

            <x-slot:footer>
                <x-buttons.primary type="submit">Save Changes</x-buttons.primary>
            </x-slot:footer>
        </x-card>
    </form>
</div>