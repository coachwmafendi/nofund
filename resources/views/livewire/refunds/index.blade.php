<div>
    <x-page-header title="Refunds" description="Track and manage donation refunds." />

    <!-- Filter Bar -->
    <div class="mb-6 flex flex-col sm:flex-row gap-3">
        <x-form.input type="search" wire:model.live.debounce.300ms="search" placeholder="Search by donor..." class="sm:w-64" />
        <x-form.select wire:model.live="statusFilter">
            <option value="">All Status</option>
            @foreach($statuses as $status)
                <option value="{{ $status->value }}">{{ ucfirst($status->value) }}</option>
            @endforeach
        </x-form.select>
    </div>

    <x-card>
        <x-table.data-table>
            <x-slot:header>
                <x-table.header>Donation ID</x-table.header>
                <x-table.header>Donor</x-table.header>
                <x-table.header class="text-right">Amount</x-table.header>
                <x-table.header>Status</x-table.header>
                <x-table.header>Reason</x-table.header>
                <x-table.header>Processed By</x-table.header>
                <x-table.header>Date</x-table.header>
            </x-slot:header>
            <x-slot:rows>
                @forelse($refunds as $refund)
                    <x-table.row>
                        <x-table.cell class="font-mono text-xs text-slate-500">{{ $refund->donation?->public_id ?? 'N/A' }}</x-table.cell>
                        <x-table.cell class="font-medium text-slate-200">{{ $refund->donation?->donor_name ?? 'Unknown' }}</x-table.cell>
                        <x-table.cell class="text-right font-medium">RM {{ number_format($refund->amount, 2) }}</x-table.cell>
                        <x-table.cell>
                            <x-badge variant="{{ match($refund->status->value) {
                                'succeeded' => 'success',
                                'pending' => 'warning',
                                'failed' => 'danger',
                                default => 'default'
                            } }}">
                                {{ ucfirst($refund->status->value) }}
                            </x-badge>
                        </x-table.cell>
                        <x-table.cell class="text-slate-400 max-w-xs truncate">{{ $refund->reason }}</x-table.cell>
                        <x-table.cell class="text-slate-500">{{ $refund->processor?->name ?? 'System' }}</x-table.cell>
                        <x-table.cell class="text-slate-500">{{ $refund->created_at->format('M d, Y') }}</x-table.cell>
                    </x-table.row>
                @empty
                    <x-table.row>
                        <x-table.empty colspan="7">
                            <x-empty-state icon="receipt-refund" title="No refunds yet" description="Refund records will appear here when donations are refunded." />
                        </x-table.empty>
                    </x-table.row>
                @endforelse
            </x-slot:rows>
        </x-table.data-table>

        <div class="mt-4">
            {{ $refunds->links('components.pagination') }}
        </div>
    </x-card>
</div>
