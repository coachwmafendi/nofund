<?php

namespace App\Livewire\Campaigns;

use App\Enums\CampaignStatus;
use App\Models\Campaign;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';

    public function render()
    {
        $campaigns = Auth::user()->organization
            ->campaigns()
            ->when($this->search, fn ($q) => $q->where('title', 'like', "%{$this->search}%"))
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->latest()
            ->paginate(15);

        return view('livewire.campaigns.index', [
            'campaigns' => $campaigns,
            'statuses' => CampaignStatus::cases(),
        ])->layout('layouts.app');
    }
}