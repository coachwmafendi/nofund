<?php

namespace App\Livewire\Donations;

use App\Enums\DonationStatus;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';
    public string $campaignFilter = '';

    public function render()
    {
        $org = Auth::user()->organization;

        $donations = $org->donations()
            ->with('campaign', 'donor')
            ->when($this->search, fn ($q) => $q->where('donor_name', 'like', "%{$this->search}%"))
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->when($this->campaignFilter, fn ($q) => $q->where('campaign_id', $this->campaignFilter))
            ->latest()
            ->paginate(20);

        return view('livewire.donations.index', [
            'donations' => $donations,
            'statuses' => DonationStatus::cases(),
            'campaigns' => $org->campaigns()->pluck('title', 'id'),
        ])->layout('layouts.app');
    }
}