<?php

namespace App\Livewire\Payouts;

use App\Enums\PayoutStatus;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public function requestPayout()
    {
        $this->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        // TODO: Implement payout request logic
    }

    public function render()
    {
        $org = Auth::user()->organization;

        $payouts = $org->payouts()
            ->with('bankAccount')
            ->latest()
            ->paginate(15);

        $availableBalance = $org->donations()
            ->where('status', 'succeeded')
            ->sum('amount') - $org->payouts()
            ->whereIn('status', [PayoutStatus::PENDING->value, PayoutStatus::PROCESSING->value, PayoutStatus::PAID->value])
            ->sum('amount');

        $pendingPayouts = $org->payouts()
            ->whereIn('status', [PayoutStatus::PENDING->value, PayoutStatus::PROCESSING->value])
            ->sum('amount');

        $totalPaidOut = $org->payouts()
            ->where('status', PayoutStatus::PAID->value)
            ->sum('amount');

        return view('livewire.payouts.index', [
            'payouts' => $payouts,
            'availableBalance' => $availableBalance,
            'pendingPayouts' => $pendingPayouts,
            'totalPaidOut' => $totalPaidOut,
        ]);
    }
}