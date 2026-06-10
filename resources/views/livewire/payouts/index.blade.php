<div>
    <x-page-header title="Payouts" description="Track and manage fund withdrawals.">
        <x-slot:actions>
            <x-buttons.primary wire:click="requestPayout">
                Request Payout
            </x-buttons.primary>
        </x-slot:actions>
    </x-page-header>

    <!-- Stats Row -->
    <div class="grid gap-4 md:grid-cols-3 mb-6">
        <x-stat-card
            title="Available Balance"
            value="RM {{ number_format($availableBalance, 2) }}"
            description="Ready for withdrawal"
            icon="banknotes"
        />
        <x-stat-card
            title="Pending Payouts"
            value="RM {{ number_format($pendingPayouts, 2) }}"
            description="Processing or pending"
            icon="clock"
        />
        <x-stat-card
            title="Total Paid Out"
            value="RM {{ number_format($totalPaidOut, 2) }}"
            description="All-time successful payouts"
            icon="check-circle"
        />
    </div>

    <x-card>
        <x-table.data-table>
            <x-slot:header>
                <x-table.header>Date</x-table.header>
                <x-table.header class="text-right">Amount</x-table.header>
                <x-table.header>Status</x-table.header>
                <x-table.header>Bank Account</x-table.header>
                <x-table.header>Actions</x-table.header>
            </x-slot:header>

            @forelse($payouts as $payout)
                <x-table.row>
                    <x-table.cell class="text-slate-500">{{ $payout->created_at->format('M d, Y') }}</x-table.cell>
                    <x-table.cell class="text-right font-medium {{ $payout->status->value === 'paid' ? 'text-emerald-400' : 'text-slate-200' }}">
                        RM {{ number_format($payout->amount, 2) }}
                    </x-table.cell>
                    <x-table.cell>
                        <x-badge variant="{{ match($payout->status->value) {
                            'pending' => 'warning',
                            'processing' => 'info',
                            'paid' => 'success',
                            'failed' => 'danger',
                            default => 'default'
                        } }}">
                            {{ ucfirst($payout->status->value) }}
                        </x-badge>
                    </x-table.cell>
                    <x-table.cell>
                        {{ $payout->bankAccount?->bank_name ?? 'N/A' }}
                    </x-table.cell>
                    <x-table.cell>
                        <x-buttons.ghost wire:click="$dispatch('toast', {message: 'Payout detail coming soon.', type: 'info'})">View</x-buttons.ghost>
                    </x-table.cell>
                </x-table.row>
            @empty
                <x-table.row>
                    <x-table.cell colspan="5">
                        <x-empty-state
                            icon="wallet"
                            title="No payouts yet"
                            description="Payout records will appear here once you request withdrawals from your balance."
                        />
                    </x-table.cell>
                </x-table.row>
            @endforelse
        </x-table.data-table>

        <div class="mt-4">
            {{ $payouts->links('components.pagination') }}
        </div>
    </x-card>
</div>