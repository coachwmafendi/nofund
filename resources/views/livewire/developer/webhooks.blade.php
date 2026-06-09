<div>
    <x-page-header title="Webhooks" description="Subscribe to events and receive real-time notifications.">
        <x-slot:actions>
            <x-buttons.primary wire:click="openCreateModal">
                Create Webhook
            </x-buttons.primary>
        </x-slot:actions>
    </x-page-header>

    @if(session('success'))
        <div class="mb-6 rounded-xl border border-emerald-500/30 bg-emerald-500/10 px-4 py-3">
            <p class="text-sm text-emerald-400">{{ session('success') }}</p>
        </div>
    @endif

    <x-card>
        <x-table.data-table>
            <x-slot:header>
                <x-table.header>URL</x-table.header>
                <x-table.header>Events</x-table.header>
                <x-table.header>Status</x-table.header>
                <x-table.header>Last Triggered</x-table.header>
                <x-table.header>Actions</x-table.header>
            </x-slot:header>

            @forelse($webhooks as $webhook)
                <x-table.row>
                    <x-table.cell class="max-w-xs truncate font-mono text-xs text-slate-400">
                        {{ $webhook->url }}
                    </x-table.cell>
                    <x-table.cell>
                        @if(is_array($webhook->events))
                            @php
                                $eventCount = count($webhook->events);
                                $displayEvents = array_slice($webhook->events, 0, 2);
                            @endphp
                            <div class="flex flex-wrap gap-1">
                                @foreach($displayEvents as $event)
                                    <span class="inline-flex items-center rounded-full bg-slate-700/50 px-2 py-0.5 text-xs text-slate-400">
                                        {{ $event }}
                                    </span>
                                @endforeach
                                @if($eventCount > 2)
                                    <span class="inline-flex items-center rounded-full bg-slate-700/50 px-2 py-0.5 text-xs text-slate-500">
                                        +{{ $eventCount - 2 }} more
                                    </span>
                                @endif
                            </div>
                        @else
                            <span class="text-slate-500">No events</span>
                        @endif
                    </x-table.cell>
                    <x-table.cell>
                        <x-badge variant="{{ $webhook->status->value === 'active' ? 'success' : 'warning' }}">
                            {{ ucfirst($webhook->status->value) }}
                        </x-badge>
                    </x-table.cell>
                    <x-table.cell class="text-slate-500">
                        {{ $webhook->last_triggered_at?->format('M d, Y H:i') ?? 'Never' }}
                    </x-table.cell>
                    <x-table.cell>
                        <div class="flex items-center gap-2">
                            <x-buttons.ghost wire:click="editWebhook({{ $webhook->id }})">Edit</x-buttons.ghost>
                            <x-buttons.ghost wire:click="toggleStatus({{ $webhook->id }})">
                                {{ $webhook->status->value === 'active' ? 'Pause' : 'Resume' }}
                            </x-buttons.ghost>
                            <x-buttons.ghost wire:click="deleteWebhook({{ $webhook->id }})" class="text-red-400 hover:text-red-300">
                                Delete
                            </x-buttons.ghost>
                        </div>
                    </x-table.cell>
                </x-table.row>
            @empty
                <x-table.row>
                    <x-table.cell colspan="5">
                        <x-empty-state
                            icon="globe-alt"
                            title="No webhooks yet"
                            description="Create a webhook to receive real-time notifications when events occur."
                        />
                    </x-table.cell>
                </x-table.row>
            @endforelse
        </x-table.data-table>
    </x-card>

    <!-- Create/Edit Modal -->
    <x-modal show="{{ $showModal }}" title="{{ $editingWebhook ? 'Edit Webhook' : 'Create Webhook' }}">
        <div class="space-y-4">
            <x-form.field>
                <x-form.label for="webhookUrl">Webhook URL</x-form.label>
                <x-form.input wire:model="url" id="webhookUrl" placeholder="https://your-app.com/webhooks/nofund" type="url" />
                <x-form.error for="url" />
            </x-form.field>

            <x-form.field>
                <x-form.label>Events</x-form.label>
                <div class="mt-2 space-y-2">
                    @foreach($availableEvents as $event)
                        <label class="flex items-center gap-3">
                            <input
                                type="checkbox"
                                value="{{ $event }}"
                                wire:model="selectedEvents"
                                class="rounded border-slate-600 bg-slate-900 text-emerald-600 focus:ring-emerald-500/50"
                            />
                            <span class="text-sm text-slate-300">{{ $event }}</span>
                        </label>
                    @endforeach
                </div>
                <x-form.error for="selectedEvents" />
            </x-form.field>
        </div>

        <x-slot:footer>
            <x-buttons.ghost wire:click="$set('showModal', false)">Cancel</x-buttons.ghost>
            <x-buttons.primary wire:click="saveWebhook">
                {{ $editingWebhook ? 'Update' : 'Create' }} Webhook
            </x-buttons.primary>
        </x-slot:footer>
    </x-modal>
</div>