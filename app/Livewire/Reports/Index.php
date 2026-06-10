<?php

namespace App\Livewire\Reports;

use App\Enums\DonationStatus;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    public string $dateRange = '30';

    public function render()
    {
        $org = Auth::user()->organization;

        $days = (int) $this->dateRange;
        $startDate = now()->subDays($days);

        $stats = [
            'total_donations' => $org->donations()
                ->where('status', DonationStatus::SUCCEEDED)
                ->where('created_at', '>=', $startDate)
                ->sum('amount'),
            'donation_count' => $org->donations()
                ->where('status', DonationStatus::SUCCEEDED)
                ->where('created_at', '>=', $startDate)
                ->count(),
            'avg_donation' => $org->donations()
                ->where('status', DonationStatus::SUCCEEDED)
                ->where('created_at', '>=', $startDate)
                ->avg('amount') ?? 0,
            'refund_amount' => $org->refunds()
                ->where('status', 'succeeded')
                ->where('created_at', '>=', $startDate)
                ->sum('amount'),
        ];

        $donationsByCampaign = $org->campaigns()
            ->withSum(['donations' => fn ($q) => $q->where('status', DonationStatus::SUCCEEDED)->where('created_at', '>=', $startDate)], 'amount')
            ->withCount(['donations' => fn ($q) => $q->where('status', DonationStatus::SUCCEEDED)->where('created_at', '>=', $startDate)])
            ->orderByDesc('donations_sum_amount')
            ->limit(10)
            ->get();

        $donationsByDay = $org->donations()
            ->where('status', DonationStatus::SUCCEEDED)
            ->where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('livewire.reports.index', compact('stats', 'donationsByCampaign', 'donationsByDay', 'days'))->layout('layouts.app');
    }
}
