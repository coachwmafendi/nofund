<?php

namespace App\Models;

use App\Enums\PayoutStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Payout extends OrganizationBaseModel
{
    protected static $publicIdPrefix = 'pyt';

    protected $fillable = [
        'organization_id',
        'amount',
        'currency',
        'status',
        'bank_account_id',
        'gateway_payout_id',
        'donations',
        'failure_reason',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'donations' => 'array',
        'status' => PayoutStatus::class,
        'paid_at' => 'datetime',
    ];

    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class);
    }

    public function transactions(): MorphMany
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }
}