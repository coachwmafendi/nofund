<?php

namespace App\Livewire\RecurringPlans;

use App\Enums\RecurringFrequency;
use App\Enums\RecurringPlanStatus;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';
    public string $frequencyFilter = '';

    public function render()
    {
        $org = Auth::user()->organization;

        $plans = $org->recurringPlans()
            ->with('donor', 'campaign')
            ->when($this->search, fn ($q) => $q->whereHas('donor', fn ($dq) => $dq->where('name', 'like', "%{$this->search}%")))
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->when($this->frequencyFilter, fn ($q) => $q->where('frequency', $this->frequencyFilter))
            ->latest()
            ->paginate(20);

        return view('livewire.recurring-plans.index', [
            'plans' => $plans,
            'statuses' => RecurringPlanStatus::cases(),
            'frequencies' => RecurringFrequency::cases(),
        ])->layout('layouts.app');
    }
}
