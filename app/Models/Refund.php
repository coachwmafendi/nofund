<?php

namespace App\Models;

use App\Enums\RefundStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Refund extends OrganizationBaseModel
{
    protected static $publicIdPrefix = 'rfd';

    protected $fillable = [
        'organization_id',
        'donation_id',
        'amount',
        'currency',
        'reason',
        'status',
        'gateway_refund_id',
        'processed_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'status' => RefundStatus::class,
    ];

    public function donation(): BelongsTo
    {
        return $this->belongsTo(Donation::class);
    }

    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function transactions(): MorphMany
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }
}