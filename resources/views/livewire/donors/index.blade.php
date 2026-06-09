<div>
    <x-page-header title="Donors" description="View and manage all donors." />

    <!-- Filter Bar -->
    <div class="mb-6 flex flex-col sm:flex-row gap-3">
        <x-form.input type="search" wire:model.live.debounce.300ms="search" placeholder="Search by name..." class="sm:w-64" />
    </div>

    <x-card>
        <x-table.data-table>
            <x-slot:header>
                <x-table.header>Name</x-table.header>
                <x-table.header>Email</x-table.header>
                <x-table.header class="text-right">Total Donated</x-table.header>
                <x-table.header class="text-center">Donation Count</x-table.header>
                <x-table.header>First Donation</x-table.header>
                <x-table.header>Last Donation</x-table.header>
            </x-slot:header>
            @forelse($donors as $donor)
                <x-table.row>
                    <x-table.cell class="font-medium text-slate-200">{{ $donor->name }}</x-table.cell>
                    <x-table.cell class="text-slate-400">{{ $donor->email }}</x-table.cell>
                    <x-table.cell class="text-right font-semibold text-emerald-400">RM {{ number_format($donor->total_donated, 2) }}</x-table.cell>
                    <x-table.cell class="text-center">{{ $donor->donation_count }}</x-table.cell>
                    <x-table.cell class="text-slate-500">{{ $donor->first_donation_at?->format('M d, Y') ?? '—' }}</x-table.cell>
                    <x-table.cell class="text-slate-500">{{ $donor->last_donation_at?->diffForHumans() ?? '—' }}</x-table.cell>
                </x-table.row>
            @empty
                <x-table.row>
                    <x-table.empty colspan="6">
                        <x-empty-state icon="users" title="No donors found" description="Donors will appear here once supporters start contributing." />
                    </x-table.empty>
                </x-table.row>
            @endforelse
        </x-table.data-table>

        <div class="mt-4">
            {{ $donors->links('components.pagination') }}
        </div>
    </x-card>
</div>