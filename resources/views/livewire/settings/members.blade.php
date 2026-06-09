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
            'border-emerald-500 text-emerald-400' => true,
            'border-transparent text-slate-400 hover:text-slate-200' => false,
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

    @if(session('error'))
        <div class="mb-4 rounded-lg bg-red-500/10 border border-red-500/20 px-4 py-3 text-sm text-red-400">
            {{ session('error') }}
        </div>
    @endif

    <x-card class="mb-6">
        <x-slot:title>Team Members</x-slot:title>
        <x-slot:description>Manage your organization team members and their roles.</x-slot:description>

        <x-table.data-table>
            <x-slot:header>
                <x-table.header>Name</x-table.header>
                <x-table.header>Email</x-table.header>
                <x-table.header>Role</x-table.header>
                <x-table.header>Status</x-table.header>
                <x-table.header>Actions</x-table.header>
            </x-slot:header>

            <x-slot:rows>
                @forelse($members as $member)
                    <x-table.row @class(['bg-emerald-500/5' => $member['is_current']])>
                        <x-table.cell>
                            <div class="flex items-center gap-3">
                                @if($member['is_current'])
                                    <span class="text-xs text-emerald-400 font-medium">(You)</span>
                                @endif
                                <span class="font-medium text-slate-200">{{ $member['name'] }}</span>
                            </div>
                        </x-table.cell>
                        <x-table.cell class="text-slate-400">{{ $member['email'] }}</x-table.cell>
                        <x-table.cell>
                            @if(!$member['is_current'] && (auth()->user()->role->value === 'admin' || auth()->user()->role->value === 'super_admin'))
                                <select
                                    wire:change="changeRole({{ $member['id'] }}, $event.target.value)"
                                    class="text-xs rounded border border-slate-700 bg-slate-900/50 px-2 py-1 text-slate-200 focus:border-emerald-500/50 focus:outline-none"
                                >
                                    @foreach(\App\Enums\UserRole::cases() as $role)
                                        <option value="{{ $role->value }}" @if($member['role']->value === $role->value) selected @endif>
                                            {{ ucfirst($role->value) }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                <x-badge variant="{{ match($member['role']->value) {
                                    'super_admin' => 'danger',
                                    'admin' => 'info',
                                    'manager' => 'warning',
                                    'viewer' => 'default',
                                    default => 'default'
                                } }}">
                                    {{ ucfirst($member['role']->value) }}
                                </x-badge>
                            @endif
                        </x-table.cell>
                        <x-table.cell>
                            <x-badge variant="{{ $member['status']->value === 'active' ? 'success' : 'default' }}">
                                {{ ucfirst($member['status']->value) }}
                            </x-badge>
                        </x-table.cell>
                        <x-table.cell>
                            @if(!$member['is_current'] && (auth()->user()->role->value === 'admin' || auth()->user()->role->value === 'super_admin'))
                                <x-buttons.danger
                                    wire:click="removeMember({{ $member['id'] }})"
                                    wire:confirm="Are you sure you want to remove this member?"
                                >
                                    Remove
                                </x-buttons.danger>
                            @endif
                        </x-table.cell>
                    </x-table.row>
                @empty
                    <x-table.row>
                        <x-table.empty colspan="5">
                            <x-empty-state icon="user-group" title="No members found" description="Invite team members to collaborate." />
                        </x-table.empty>
                    </x-table.row>
                @endforelse
            </x-slot:rows>
        </x-table.data-table>
    </x-card>

    <x-card>
        <x-slot:title>Invite Member</x-slot:title>
        <x-slot:description>Send an invitation to add a new team member.</x-slot:description>

        <form wire:submit="sendInvite">
            <div class="flex items-end gap-4">
                <x-form.field class="flex-1">
                    <x-form.label for="inviteEmail">Email Address</x-form.label>
                    <x-form.input wire:model="inviteEmail" id="inviteEmail" name="inviteEmail" type="email" placeholder="colleague@example.com" />
                    <x-form.error name="inviteEmail" />
                </x-form.field>

                <x-form.field>
                    <x-form.label for="inviteRole">Role</x-form.label>
                    <x-form.select wire:model="inviteRole" id="inviteRole" name="inviteRole">
                        @foreach(\App\Enums\UserRole::cases() as $role)
                            <option value="{{ $role->value }}">{{ ucfirst($role->value) }}</option>
                        @endforeach
                    </x-form.select>
                    <x-form.error name="inviteRole" />
                </x-form.field>

                <x-buttons.primary type="submit">Send Invite</x-buttons.primary>
            </div>
        </form>
    </x-card>
</div>