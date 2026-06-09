<div>
    <x-page-header title="Campaigns" description="Manage all fundraising campaigns.">
        <x-slot:actions>
            <x-buttons.primary>Create Campaign</x-buttons.primary>
        </x-slot:actions>
    </x-page-header>

    <!-- Filter Bar -->
    <div class="mb-6 flex flex-col sm:flex-row gap-3">
        <x-form.input type="search" wire:model.live.debounce.300ms="search" placeholder="Search campaigns..." class="sm:w-64" />
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
                <x-table.header>Title</x-table.header>
                <x-table.header>Status</x-table.header>
                <x-table.header>Raised / Target</x-table.header>
                <x-table.header>Donors</x-table.header>
                <x-table.header>Date</x-table.header>
                <x-table.header>Actions</x-table.header>
            </x-slot:header>

            <x-slot:rows>
                @forelse($campaigns as $campaign)
                    <x-table.row>
                        <x-table.cell>
                            <a href="{{ route('campaigns.show', $campaign) }}" class="font-medium text-slate-200 hover:text-emerald-400">
                                {{ $campaign->title }}
                            </a>
                        </x-table.cell>
                        <x-table.cell>
                            <x-badge variant="{{ match($campaign->status->value) {
                                'active' => 'success',
                                'draft' => 'default',
                                'paused' => 'warning',
                                'completed' => 'info',
                                default => 'default'
                            } }}">
                                {{ ucfirst($campaign->status->value) }}
                            </x-badge>
                        </x-table.cell>
                        <x-table.cell>
                            <div class="text-right">
                                <p class="text-sm text-slate-200">RM {{ number_format($campaign->raised_amount, 2) }}</p>
                                <p class="text-xs text-slate-500">of RM {{ number_format($campaign->target_amount, 2) }}</p>
                            </div>
                        </x-table.cell>
                        <x-table.cell class="text-center">{{ $campaign->donor_count }}</x-table.cell>
                        <x-table.cell class="text-slate-500">{{ $campaign->created_at->format('M d, Y') }}</x-table.cell>
                        <x-table.cell>
                            <x-buttons.ghost>View</x-buttons.ghost>
                        </x-table.cell>
                    </x-table.row>
                @empty
                    <x-table.row>
                        <x-table.empty colspan="6">
                            <x-empty-state icon="megaphone" title="No campaigns found" description="Try adjusting your search or create a new campaign." />
                        </x-table.empty>
                    </x-table.row>
                @endforelse
            </x-slot:rows>
        </x-table.data-table>

        <div class="mt-4">
            {{ $campaigns->links('components.pagination') }}
        </div>
    </x-card>
</div>