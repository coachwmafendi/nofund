<?php

namespace App\Livewire\Refunds;

use App\Enums\RefundStatus;
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
        $org = Auth::user()->organization;

        $refunds = $org->refunds()
            ->with('donation', 'processor')
            ->when($this->search, fn ($q) => $q->whereHas('donation', fn ($dq) => $dq->where('donor_name', 'like', "%{$this->search}%")))
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->latest()
            ->paginate(20);

        return view('livewire.refunds.index', [
            'refunds' => $refunds,
            'statuses' => RefundStatus::cases(),
        ])->layout('layouts.app');
    }
}
