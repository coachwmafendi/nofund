<div>
    <x-page-header title="API Keys" description="Manage keys for programmatic access.">
        <x-slot:actions>
            <x-buttons.primary wire:click="$set('showCreateModal', true)">
                Create API Key
            </x-buttons.primary>
        </x-slot:actions>
    </x-page-header>

    <!-- Success Flash -->
    @if(session('success') && $newlyCreatedKey)
        <div class="mb-6 rounded-xl border border-emerald-500/30 bg-emerald-500/10 p-4">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1">
                    <h4 class="text-sm font-medium text-emerald-400">API Key Created</h4>
                    <p class="mt-1 text-xs text-slate-400">Copy this key now — it won't be shown again.</p>
                    <div class="mt-3 flex items-center gap-2">
                        <code class="flex-1 rounded-lg bg-slate-900 px-3 py-2 text-sm font-mono text-emerald-400 break-all">{{ $newlyCreatedKey }}</code>
                        <x-buttons.secondary onclick="navigator.clipboard.writeText('{{ $newlyCreatedKey }}')">
                            Copy
                        </x-buttons.secondary>
                    </div>
                </div>
                <button wire:click="$set('newlyCreatedKey', null)" class="text-slate-500 hover:text-slate-300">
                    <x-heroicon-o-x-mark class="w-5 h-5" />
                </button>
            </div>
        </div>
    @elseif(session('success'))
        <div class="mb-6 rounded-xl border border-emerald-500/30 bg-emerald-500/10 px-4 py-3">
            <p class="text-sm text-emerald-400">{{ session('success') }}</p>
        </div>
    @endif

    <x-card>
        <x-table.data-table>
            <x-slot:header>
                <x-table.header>Name</x-table.header>
                <x-table.header>Key</x-table.header>
                <x-table.header>Created</x-table.header>
                <x-table.header>Last Used</x-table.header>
                <x-table.header>Status</x-table.header>
                <x-table.header>Actions</x-table.header>
            </x-slot:header>

            @forelse($apiKeys as $key)
                <x-table.row>
                    <x-table.cell class="font-medium text-slate-200">{{ $key->name }}</x-table.cell>
                    <x-table.cell>
                        <code class="text-xs font-mono text-slate-500">{{ substr($key->public_id ?? $key->id, 0, 8) }}***</code>
                    </x-table.cell>
                    <x-table.cell class="text-slate-500">{{ $key->created_at->format('M d, Y') }}</x-table.cell>
                    <x-table.cell class="text-slate-500">
                        {{ $key->last_used_at?->format('M d, Y') ?? 'Never' }}
                    </x-table.cell>
                    <x-table.cell>
                        <x-badge variant="{{ $key->revoked_at ? 'danger' : 'success' }}">
                            {{ $key->revoked_at ? 'Revoked' : 'Active' }}
                        </x-badge>
                    </x-table.cell>
                    <x-table.cell>
                        @unless($key->revoked_at)
                            <x-buttons.ghost wire:click="revokeKey({{ $key->id }})" class="text-red-400 hover:text-red-300">
                                Revoke
                            </x-buttons.ghost>
                        @endunless
                    </x-table.cell>
                </x-table.row>
            @empty
                <x-table.row>
                    <x-table.cell colspan="6">
                        <x-empty-state
                            icon="code-bracket"
                            title="No API keys yet"
                            description="Create your first API key to access the nofund API programmatically."
                        />
                    </x-table.cell>
                </x-table.row>
            @endforelse
        </x-table.data-table>
    </x-card>

    <!-- Create Modal -->
    <x-modal show="{{ $showCreateModal }}" title="Create API Key">
        <div class="space-y-4">
            <x-form.field>
                <x-form.label for="keyName">Key Name</x-form.label>
                <x-form.input wire:model="keyName" id="keyName" placeholder="e.g., Production API Key" />
                <x-form.error name="keyName" />
            </x-form.field>
        </div>

        <x-slot:footer>
            <x-buttons.ghost wire:click="$set('showCreateModal', false)">Cancel</x-buttons.ghost>
            <x-buttons.primary wire:click="createKey">Create Key</x-buttons.primary>
        </x-slot:footer>
    </x-modal>
</div>