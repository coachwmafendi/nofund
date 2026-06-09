<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $organization = auth()->user()->organization;

        $stats = [
            'total_donations' => $organization->donations()->where('status', 'succeeded')->sum('amount'),
            'total_donors' => $organization->donors()->count(),
            'active_campaigns' => $organization->campaigns()->where('status', 'active')->count(),
            'monthly_revenue' => $organization->donations()
                ->where('status', 'succeeded')
                ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
                ->sum('amount'),
        ];

        $recentDonations = $organization->donations()
            ->with('campaign', 'donor')
            ->latest()
            ->limit(10)
            ->get();

        $topCampaigns = $organization->campaigns()
            ->where('status', 'active')
            ->orderByDesc('raised_amount')
            ->limit(5)
            ->get();

        return view('dashboard', compact('stats', 'recentDonations', 'topCampaigns'));
    }
}