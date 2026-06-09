<?php

namespace App\Models;

use App\Enums\DonationStatus;
use App\Enums\PaymentMethod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Donation extends OrganizationBaseModel
{
    protected static $publicIdPrefix = 'dnt';

    protected $fillable = [
        'organization_id',
        'campaign_id',
        'donor_id',
        'amount',
        'currency',
        'status',
        'payment_method',
        'payment_gateway',
        'gateway_transaction_id',
        'gateway_response',
        'is_anonymous',
        'donor_name',
        'donor_email',
        'donor_phone',
        'donor_message',
        'refunded_amount',
        'refund_reason',
        'receipt_sent',
        'receipt_url',
        'ip_address',
        'user_agent',
        'source_url',
        'meta',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'refunded_amount' => 'decimal:2',
        'is_anonymous' => 'boolean',
        'receipt_sent' => 'boolean',
        'status' => DonationStatus::class,
        'payment_method' => PaymentMethod::class,
        'gateway_response' => 'array',
        'meta' => 'array',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function donor(): BelongsTo
    {
        return $this->belongsTo(Donor::class);
    }

    public function refund(): HasOne
    {
        return $this->hasOne(Refund::class);
    }

    public function transactions(): MorphMany
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }
}