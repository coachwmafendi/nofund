<div>
    <x-page-header title="Transactions" description="Immutable financial ledger for all organization activity." />

    <!-- Filter Bar -->
    <div class="mb-6 flex flex-col sm:flex-row gap-3">
        <x-form.input type="search" wire:model.live.debounce.300ms="search" placeholder="Search transactions..." class="sm:w-64" />
        <x-form.select wire:model.live="typeFilter">
            <option value="">All Types</option>
            @foreach($types as $type)
                <option value="{{ $type->value }}">{{ ucfirst($type->value) }}</option>
            @endforeach
        </x-form.select>
    </div>

    <x-card>
        <x-table.data-table>
            <x-slot:header>
                <x-table.header>Date</x-table.header>
                <x-table.header>Type</x-table.header>
                <x-table.header>Description</x-table.header>
                <x-table.header class="text-right">Amount</x-table.header>
                <x-table.header class="text-right">Balance</x-table.header>
            </x-slot:header>
            <x-slot:rows>
                @forelse($transactions as $tx)
                    <x-table.row>
                        <x-table.cell class="text-slate-500">{{ $tx->created_at->format('M d, Y H:i') }}</x-table.cell>
                        <x-table.cell>
                            <x-badge variant="{{ match($tx->type->value) {
                                'donation' => 'success',
                                'refund' => 'warning',
                                'payout' => 'info',
                                'fee' => 'danger',
                                'adjustment' => 'default',
                                default => 'default'
                            } }}">
                                {{ ucfirst($tx->type->value) }}
                            </x-badge>
                        </x-table.cell>
                        <x-table.cell class="font-medium text-slate-200">{{ $tx->description }}</x-table.cell>
                        <x-table.cell class="text-right font-medium {{ $tx->amount >= 0 ? 'text-emerald-400' : 'text-red-400' }}">
                            {{ $tx->amount >= 0 ? '+' : '' }}RM {{ number_format(abs($tx->amount), 2) }}
                        </x-table.cell>
                        <x-table.cell class="text-right text-slate-200">RM {{ number_format($tx->balance_after, 2) }}</x-table.cell>
                    </x-table.row>
                @empty
                    <x-table.row>
                        <x-table.empty colspan="5">
                            <x-empty-state icon="document-text" title="No transactions yet" description="Financial transactions will appear here once donations and payouts are processed." />
                        </x-table.empty>
                    </x-table.row>
                @endforelse
            </x-slot:rows>
        </x-table.data-table>

        <div class="mt-4">
            {{ $transactions->links('components.pagination') }}
        </div>
    </x-card>
</div>
