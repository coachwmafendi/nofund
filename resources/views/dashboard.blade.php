@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<x-page-header title="Dashboard" description="Overview of your fundraising performance." />

<div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
    <x-stat-card title="Total Donations" value="RM {{ number_format($stats['total_donations'], 2) }}" description="All-time" icon="banknotes" />
    <x-stat-card title="Donors" value="{{ number_format($stats['total_donors']) }}" description="Total unique donors" icon="users" />
    <x-stat-card title="Active Campaigns" value="{{ $stats['active_campaigns'] }}" description="Currently running" icon="megaphone" />
    <x-stat-card title="This Month" value="RM {{ number_format($stats['monthly_revenue'], 2) }}" description="Revenue this month" icon="chart-bar" />
</div>

<div class="mt-6 grid gap-6 lg:grid-cols-3">
    {{-- Recent Donations (2 cols) --}}
    <x-card class="lg:col-span-2">
        <x-slot:title>Recent Donations</x-slot:title>
        <x-slot:description>Latest contributions to your campaigns.</x-slot:description>

        <x-table.data-table>
            <x-slot:header>
                <x-table.header>Donor</x-table.header>
                <x-table.header>Campaign</x-table.header>
                <x-table.header>Amount</x-table.header>
                <x-table.header>Status</x-table.header>
                <x-table.header>Date</x-table.header>
            </x-slot:header>
            <x-slot:rows>
                @forelse($recentDonations as $donation)
                    <x-table.row>
                        <x-table.cell>{{ $donation->donor_name }}</x-table.cell>
                        <x-table.cell>{{ $donation->campaign?->title ?? 'General' }}</x-table.cell>
                        <x-table.cell class="text-right font-medium">RM {{ number_format($donation->amount, 2) }}</x-table.cell>
                        <x-table.cell>
                            <x-badge variant="{{ $donation->status->value === 'succeeded' ? 'success' : ($donation->status->value === 'pending' ? 'warning' : 'danger') }}">
                                {{ ucfirst($donation->status->value) }}
                            </x-badge>
                        </x-table.cell>
                        <x-table.cell class="text-slate-500">{{ $donation->created_at->diffForHumans() }}</x-table.cell>
                    </x-table.row>
                @empty
                    <x-table.row>
                        <x-table.empty colspan="5">
                            <x-empty-state icon="banknotes" title="No donations yet" description="Donations will appear here once supporters start contributing." />
                        </x-table.empty>
                    </x-table.row>
                @endforelse
            </x-slot:rows>
        </x-table.data-table>
    </x-card>

    {{-- Top Campaigns (1 col) --}}
    <x-card>
        <x-slot:title>Top Campaigns</x-slot:title>
        <x-slot:description>By amount raised</x-slot:description>

        <div class="space-y-4">
            @forelse($topCampaigns as $campaign)
                <div class="flex items-center justify-between">
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-slate-200 truncate">{{ $campaign->title }}</p>
                        <p class="text-xs text-slate-500">{{ $campaign->donor_count }} donors</p>
                    </div>
                    <p class="text-sm font-semibold text-emerald-400">RM {{ number_format($campaign->raised_amount, 2) }}</p>
                </div>
                <div class="w-full bg-slate-800 rounded-full h-1.5">
                    <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ min(($campaign->raised_amount / max($campaign->target_amount, 1)) * 100, 100) }}%"></div>
                </div>
            @empty
                <x-empty-state icon="megaphone" title="No active campaigns" description="Create your first campaign to start collecting donations." />
            @endforelse
        </div>
    </x-card>
</div>
@endsection