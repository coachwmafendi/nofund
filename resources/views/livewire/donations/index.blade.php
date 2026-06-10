<div>
    <x-page-header title="Donations" description="View and manage all donation transactions." />

    <!-- Filter Bar -->
    <div class="mb-6 flex flex-col sm:flex-row gap-3">
        <x-form.input type="search" wire:model.live.debounce.300ms="search" placeholder="Search by donor name..." class="sm:w-64" />
        <x-form.select wire:model.live="statusFilter">
            <option value="">All Status</option>
            @foreach($statuses as $status)
                <option value="{{ $status->value }}">{{ ucfirst($status->value) }}</option>
            @endforeach
        </x-form.select>
        <x-form.select wire:model.live="campaignFilter">
            <option value="">All Campaigns</option>
            @foreach($campaigns as $id => $title)
                <option value="{{ $id }}">{{ $title }}</option>
            @endforeach
        </x-form.select>
    </div>

    <x-card>
        <x-table.data-table>
            <x-slot:header>
                <x-table.header>Donor</x-table.header>
                <x-table.header>Campaign</x-table.header>
                <x-table.header class="text-right">Amount</x-table.header>
                <x-table.header>Status</x-table.header>
                <x-table.header>Method</x-table.header>
                <x-table.header>Date</x-table.header>
            </x-slot:header>
            @forelse($donations as $donation)
                <x-table.row>
                    <x-table.cell class="font-medium text-slate-200">{{ $donation->donor_name }}</x-table.cell>
                    <x-table.cell>{{ $donation->campaign?->title ?? 'General' }}</x-table.cell>
                    <x-table.cell class="text-right font-medium">RM {{ number_format($donation->amount, 2) }}</x-table.cell>
                    <x-table.cell>
                        <x-badge variant="{{ match($donation->status->value) {
                            'succeeded' => 'success',
                            'pending' => 'warning',
                            'failed' => 'danger',
                            'refunded' => 'default',
                            default => 'default'
                        } }}">
                            {{ ucfirst($donation->status->value) }}
                        </x-badge>
                    </x-table.cell>
                    <x-table.cell>{{ $donation->payment_method?->value ? ucfirst(str_replace('_', ' ', $donation->payment_method->value)) : 'N/A' }}</x-table.cell>
                    <x-table.cell class="text-slate-500">{{ $donation->created_at->format('M d, Y') }}</x-table.cell>
                </x-table.row>
            @empty
                <x-table.row>
                    <x-table.empty colspan="6">
                        <x-empty-state icon="banknotes" title="No donations found" description="Donations will appear here once supporters start contributing." />
                    </x-table.empty>
                </x-table.row>
            @endforelse
        </x-table.data-table>

        <div class="mt-4">
            {{ $donations->links('components.pagination') }}
        </div>
    </x-card>
</div>