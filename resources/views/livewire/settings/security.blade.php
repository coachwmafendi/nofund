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
            'border-emerald-500 text-emerald-400' => false,
            'border-transparent text-slate-400 hover:text-slate-200' => true,
        ])>
            Receipts
        </a>
        <a href="{{ route('settings.security') }}" @class([
            'px-3 py-2 text-sm font-medium border-b-2 transition-colors',
            'border-emerald-500 text-emerald-400' => true,
            'border-transparent text-slate-400 hover:text-slate-200' => false,
        ])>
            Security
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-emerald-500/10 border border-emerald-500/20 px-4 py-3 text-sm text-emerald-400">
            {{ session('success') }}
        </div>
    @endif

    <x-card class="mb-6">
        <x-slot:title>Change Password</x-slot:title>
        <x-slot:description>Update your account password.</x-slot:description>

        <form wire:submit="resetPassword">
            <div class="grid gap-5 md:grid-cols-2">
                <x-form.field>
                    <x-form.label for="currentPassword">Current Password</x-form.label>
                    <x-form.input wire:model="currentPassword" id="currentPassword" name="currentPassword" type="password" placeholder="Enter current password" />
                    <x-form.error name="currentPassword" />
                </x-form.field>

                <div></div>

                <x-form.field>
                    <x-form.label for="newPassword">New Password</x-form.label>
                    <x-form.input wire:model="newPassword" id="newPassword" name="newPassword" type="password" placeholder="Min. 8 characters" />
                    <x-form.error name="newPassword" />
                </x-form.field>

                <x-form.field>
                    <x-form.label for="confirmPassword">Confirm Password</x-form.label>
                    <x-form.input wire:model="confirmPassword" id="confirmPassword" name="confirmPassword" type="password" placeholder="Repeat new password" />
                    <x-form.error name="confirmPassword" />
                </x-form.field>
            </div>

            <x-slot:footer>
                <x-buttons.primary type="submit">Update Password</x-buttons.primary>
            </x-slot:footer>
        </form>
    </x-card>

    <x-card>
        <x-slot:title>Two-Factor Authentication</x-slot:title>
        <x-slot:description>Add an extra layer of security to your account.</x-slot:description>

        <x-empty-state
            icon="shield-check"
            title="2FA coming soon"
            description="Two-factor authentication will be available in a future update."
        />
    </x-card>
</div>