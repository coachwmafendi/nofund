<div>
    <x-page-header
        title="Webhook Deliveries"
        description="{{ $webhook->url }}"
    />

    <x-card>
        <x-table.data-table>
            <x-slot:header>
                <x-table.header>Event</x-table.header>
                <x-table.header>Status</x-table.header>
                <x-table.header>Attempts</x-table.header>
                <x-table.header>Delivered At</x-table.header>
                <x-table.header>Response</x-table.header>
            </x-slot:header>

            @forelse($deliveries as $delivery)
                <x-table.row>
                    <x-table.cell class="font-mono text-xs text-slate-300">
                        {{ $delivery->event }}
                    </x-table.cell>
                    <x-table.cell>
                        <x-badge variant="{{ $delivery->response_status === 200 ? 'success' : 'danger' }}">
                            {{ $delivery->response_status ?? 'Failed' }}
                        </x-badge>
                    </x-table.cell>
                    <x-table.cell class="text-slate-500">
                        {{ $delivery->attempt_count }}
                    </x-table.cell>
                    <x-table.cell class="text-slate-500">
                        {{ $delivery->delivered_at?->format('M d, Y H:i:s') ?? '—' }}
                    </x-table.cell>
                    <x-table.cell class="max-w-xs truncate text-xs text-slate-500">
                        {{ Str::limit($delivery->response_body ?? '', 50) }}
                    </x-table.cell>
                </x-table.row>
            @empty
                <x-table.row>
                    <x-table.cell colspan="5">
                        <x-empty-state
                            icon="bell"
                            title="No deliveries yet"
                            description="Webhook delivery attempts will appear here after events are triggered."
                        />
                    </x-table.cell>
                </x-table.row>
            @endforelse
        </x-table.data-table>

        <div class="mt-4">
            {{ $deliveries->links('components.pagination') }}
        </div>
    </x-card>
</div>