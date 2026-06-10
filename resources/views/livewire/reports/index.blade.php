<div>
    <x-page-header title="Reports" description="Financial summaries and donation analytics." />

    <!-- Date Range Filter -->
    <div class="mb-6 flex flex-col sm:flex-row gap-3">
        <x-form.select wire:model.live="dateRange">
            <option value="7">Last 7 days</option>
            <option value="30">Last 30 days</option>
            <option value="90">Last 90 days</option>
            <option value="365">Last 12 months</option>
        </x-form.select>
    </div>

    <!-- Stats -->
    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4 mb-6">
        <x-stat-card title="Total Donations" value="RM {{ number_format($stats['total_donations'], 2) }}" description="Successful donations ({{ $days }} days)" icon="banknotes" />
        <x-stat-card title="Donation Count" value="{{ number_format($stats['donation_count']) }}" description="Successful transactions" icon="check-circle" />
        <x-stat-card title="Average Donation" value="RM {{ number_format($stats['avg_donation'], 2) }}" description="Per transaction" icon="chart-bar" />
        <x-stat-card title="Refunded" value="RM {{ number_format($stats['refund_amount'], 2) }}" description="Total refunds issued" icon="receipt-refund" />
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <!-- Donations by Campaign -->
        <x-card>
            <x-slot:title>Top Campaigns</x-slot:title>
            <x-slot:description>By donation amount ({{ $days }} days)</x-slot:description>

            <div class="space-y-4">
                @forelse($donationsByCampaign as $campaign)
                    <div class="flex items-center justify-between">
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-slate-200 truncate">{{ $campaign->title }}</p>
                            <p class="text-xs text-slate-500">{{ $campaign->donations_count }} donations</p>
                        </div>
                        <p class="text-sm font-semibold text-emerald-400">RM {{ number_format($campaign->donations_sum_amount ?? 0, 2) }}</p>
                    </div>
                @empty
                    <x-empty-state icon="megaphone" title="No campaign data" description="Donation data will appear here once campaigns receive contributions." />
                @endforelse
            </div>
        </x-card>

        <!-- Donations by Day -->
        <x-card>
            <x-slot:title>Daily Trend</x-slot:title>
            <x-slot:description>Donations over time ({{ $days }} days)</x-slot:description>

            <div class="space-y-3">
                @forelse($donationsByDay as $day)
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-400">{{ \Carbon\Carbon::parse($day->date)->format('M d, Y') }}</span>
                        <div class="flex items-center gap-4">
                            <span class="text-xs text-slate-500">{{ $day->count }} donations</span>
                            <span class="text-sm font-medium text-emerald-400 w-24 text-right">RM {{ number_format($day->total, 2) }}</span>
                        </div>
                    </div>
                @empty
                    <x-empty-state icon="calendar" title="No daily data" description="Daily donation trends will appear here." />
                @endforelse
            </div>
        </x-card>
    </div>
</div>
