<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Donor extends OrganizationBaseModel
{
    protected static $publicIdPrefix = 'dnr';

    protected $fillable = [
        'organization_id',
        'name',
        'email',
        'phone',
        'is_anonymous_preference',
        'total_donated',
        'donation_count',
        'first_donation_at',
        'last_donation_at',
        'notes',
        'tags',
    ];

    protected $casts = [
        'total_donated' => 'decimal:2',
        'donation_count' => 'integer',
        'first_donation_at' => 'datetime',
        'last_donation_at' => 'datetime',
        'is_anonymous_preference' => 'boolean',
        'tags' => 'array',
    ];

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }

    public function recurringPlans(): HasMany
    {
        return $this->hasMany(RecurringPlan::class);
    }
}