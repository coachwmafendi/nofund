<?php

namespace App\Models;

use App\Enums\OrganizationStatus;
use App\Enums\OrganizationType;
use App\Models\Concerns\HasPublicId;
use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends \Illuminate\Database\Eloquent\Model
{
    use HasFactory;
    use HasUuid;
    use HasPublicId;
    use SoftDeletes;

    protected static $publicIdPrefix = 'org';

    protected $fillable = [
        'name',
        'slug',
        'type',
        'logo_url',
        'description',
        'contact_email',
        'contact_phone',
        'address',
        'timezone',
        'currency',
        'status',
        'plan_id',
    ];

    protected $casts = [
        'address' => 'array',
        'status' => OrganizationStatus::class,
        'type' => OrganizationType::class,
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function campaigns(): HasMany
    {
        return $this->hasMany(Campaign::class);
    }

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }

    public function donors(): HasMany
    {
        return $this->hasMany(Donor::class);
    }

    public function recurringPlans(): HasMany
    {
        return $this->hasMany(RecurringPlan::class);
    }

    public function payouts(): HasMany
    {
        return $this->hasMany(Payout::class);
    }

    public function bankAccounts(): HasMany
    {
        return $this->hasMany(BankAccount::class);
    }

    public function apiKeys(): HasMany
    {
        return $this->hasMany(ApiKey::class);
    }

    public function webhooks(): HasMany
    {
        return $this->hasMany(Webhook::class);
    }

    public function refunds(): HasMany
    {
        return $this->hasMany(Refund::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }
}