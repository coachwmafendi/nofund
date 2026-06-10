<?php

namespace App\Livewire\Transactions;

use App\Enums\TransactionType;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $typeFilter = '';

    public function render()
    {
        $org = Auth::user()->organization;

        $transactions = $org->transactions()
            ->when($this->search, fn ($q) => $q->where('description', 'like', "%{$this->search}%"))
            ->when($this->typeFilter, fn ($q) => $q->where('type', $this->typeFilter))
            ->latest()
            ->paginate(20);

        return view('livewire.transactions.index', [
            'transactions' => $transactions,
            'types' => TransactionType::cases(),
        ])->layout('layouts.app');
    }
}
