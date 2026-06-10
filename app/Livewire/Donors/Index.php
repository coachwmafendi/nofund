<?php

namespace App\Livewire\Donors;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public function render()
    {
        $donors = Auth::user()->organization
            ->donors()
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->orderByDesc('total_donated')
            ->paginate(20);

        return view('livewire.donors.index', compact('donors'))->layout('layouts.app');
    }
}