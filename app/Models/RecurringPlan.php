<?php

namespace App\Models;

use App\Enums\RecurringFrequency;
use App\Enums\RecurringPlanStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecurringPlan extends OrganizationBaseModel
{
    protected static $publicIdPrefix = 'rcp';

    protected $fillable = [
        'organization_id',
        'donor_id',
        'campaign_id',
        'amount',
        'currency',
        'frequency',
        'status',
        'start_date',
        'end_date',
        'next_charge_date',
        'total_charges',
        'total_amount',
        'payment_method_token',
        'gateway_subscription_id',
        'meta',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'next_charge_date' => 'date',
        'total_charges' => 'integer',
        'frequency' => RecurringFrequency::class,
        'status' => RecurringPlanStatus::class,
        'meta' => 'array',
    ];

    public function donor(): BelongsTo
    {
        return $this->belongsTo(Donor::class);
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }
}