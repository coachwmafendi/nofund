<div>
    <x-page-header title="Recurring Plans" description="Manage subscription-style recurring donations.">
        <x-slot:actions>
            <x-buttons.secondary wire:click="$set('showCreateModal', true)">
                New Plan
            </x-buttons.secondary>
        </x-slot:actions>
    </x-page-header>

    <!-- Filter Bar -->
    <div class="mb-6 flex flex-col sm:flex-row gap-3">
        <x-form.input type="search" wire:model.live.debounce.300ms="search" placeholder="Search by donor name..." class="sm:w-64" />
        <x-form.select wire:model.live="statusFilter">
            <option value="">All Status</option>
            @foreach($statuses as $status)
                <option value="{{ $status->value }}">{{ ucfirst($status->value) }}</option>
            @endforeach
        </x-form.select>
        <x-form.select wire:model.live="frequencyFilter">
            <option value="">All Frequencies</option>
            @foreach($frequencies as $frequency)
                <option value="{{ $frequency->value }}">{{ ucfirst($frequency->value) }}</option>
            @endforeach
        </x-form.select>
    </div>

    <x-card>
        <x-table.data-table>
            <x-slot:header>
                <x-table.header>Donor</x-table.header>
                <x-table.header>Campaign</x-table.header>
                <x-table.header class="text-right">Amount</x-table.header>
                <x-table.header>Frequency</x-table.header>
                <x-table.header>Status</x-table.header>
                <x-table.header>Next Charge</x-table.header>
                <x-table.header>Charged</x-table.header>
            </x-slot:header>
            <x-slot:rows>
                @forelse($plans as $plan)
                    <x-table.row>
                        <x-table.cell class="font-medium text-slate-200">{{ $plan->donor?->name ?? 'Unknown' }}</x-table.cell>
                        <x-table.cell>{{ $plan->campaign?->title ?? 'General' }}</x-table.cell>
                        <x-table.cell class="text-right font-medium">RM {{ number_format($plan->amount, 2) }}</x-table.cell>
                        <x-table.cell>{{ ucfirst($plan->frequency->value) }}</x-table.cell>
                        <x-table.cell>
                            <x-badge variant="{{ match($plan->status->value) {
                                'active' => 'success',
                                'paused' => 'warning',
                                'cancelled' => 'danger',
                                'expired' => 'default',
                                default => 'default'
                            } }}">
                                {{ ucfirst($plan->status->value) }}
                            </x-badge>
                        </x-table.cell>
                        <x-table.cell class="text-slate-500">{{ $plan->next_charge_date?->format('M d, Y') ?? '—' }}</x-table.cell>
                        <x-table.cell class="text-slate-500">{{ $plan->total_charges }}</x-table.cell>
                    </x-table.row>
                @empty
                    <x-table.row>
                        <x-table.empty colspan="7">
                            <x-empty-state icon="arrow-path" title="No recurring plans yet" description="Recurring donation plans will appear here once donors subscribe." />
                        </x-table.empty>
                    </x-table.row>
                @endforelse
            </x-slot:rows>
        </x-table.data-table>

        <div class="mt-4">
            {{ $plans->links('components.pagination') }}
        </div>
    </x-card>
</div>
