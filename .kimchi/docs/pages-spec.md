# Pages Spec — nofund Dashboard

## Context
Build the core dashboard pages using the UI component system.

## Routes
```php
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/campaigns', \App\Livewire\Campaigns\Index::class)->name('campaigns.index');
    Route::get('/campaigns/{campaign:public_id}', \App\Livewire\Campaigns\Show::class)->name('campaigns.show');
    Route::get('/donations', \App\Livewire\Donations\Index::class)->name('donations.index');
    Route::get('/donors', \App\Livewire\Donors\Index::class)->name('donors.index');
});
```

## Layout
Create `resources/views/layouts/app.blade.php`:
```blade
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'nofund') }} — @yield('title', 'Dashboard')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-950 text-slate-50 antialiased">
    <x-app-shell>
        {{ $slot }}
    </x-app-shell>
    @livewireScripts
</body>
</html>
```

Set this as the default layout for Livewire components and route views.

## Page 1: Dashboard

**File:** `app/Http/Controllers/DashboardController.php`
**View:** `resources/views/dashboard.blade.php`

### Controller Logic
```php
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
```

### View Structure
```blade
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
    <!-- Recent Donations (2 cols) -->
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
        </x-table.data-table>
    </x-card>

    <!-- Top Campaigns (1 col) -->
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
```

## Page 2: Campaigns Index (Livewire)

**File:** `app/Livewire/Campaigns/Index.php`
**View:** `resources/views/livewire/campaigns/index.blade.php`

### Livewire Logic
```php
class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';

    public function render()
    {
        $organization = auth()->user()->organization;

        $campaigns = $organization->campaigns()
            ->when($this->search, fn($q) => $q->where('title', 'like', "%{$this->search}%"))
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->latest()
            ->paginate(15);

        return view('livewire.campaigns.index', [
            'campaigns' => $campaigns,
        ]);
    }
}
```

### View Structure
```blade
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
            @foreach(App\Enums\CampaignStatus::cases() as $status)
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
        </x-table.data-table>

        <div class="mt-4">
            {{ $campaigns->links('components.pagination') }}
        </div>
    </x-card>
</div>
```

## Page 3: Donations Index (Livewire)

**File:** `app/Livewire/Donations/Index.php`
**View:** `resources/views/livewire/donations/index.blade.php`

### Livewire Logic
```php
class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';
    public string $campaignFilter = '';

    public function render()
    {
        $organization = auth()->user()->organization;

        $donations = $organization->donations()
            ->with('campaign', 'donor')
            ->when($this->search, fn($q) => $q->where('donor_name', 'like', "%{$this->search}%"))
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->when($this->campaignFilter, fn($q) => $q->where('campaign_id', $this->campaignFilter))
            ->latest()
            ->paginate(20);

        $campaigns = $organization->campaigns()->pluck('title', 'id');

        return view('livewire.donations.index', compact('donations', 'campaigns'));
    }
}
```

### View Structure
Similar to campaigns table but with donation columns:
- Donor, Campaign, Amount, Status, Method, Date, Actions

## Page 4: Donors Index (Livewire)

**File:** `app/Livewire/Donors/Index.php`
**View:** `resources/views/livewire/donors/index.blade.php`

Simple table with search, showing:
- Name, Email, Total Donated, Donation Count, First Donation, Last Donation

## Verification
- All routes load without errors
- Livewire components mount correctly
- Tables render with seeded data
- Search/filter works on Livewire tables
