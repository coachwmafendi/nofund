<?php

namespace App\Models;

use App\Enums\CampaignStatus;
use App\Enums\CampaignVisibility;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campaign extends OrganizationBaseModel
{
    use SoftDeletes;

    protected static $publicIdPrefix = 'cmp';

    protected $fillable = [
        'organization_id',
        'title',
        'slug',
        'description',
        'cover_image_url',
        'target_amount',
        'raised_amount',
        'donor_count',
        'status',
        'visibility',
        'category',
        'start_date',
        'end_date',
        'embed_code',
        'meta',
        'created_by',
    ];

    protected $casts = [
        'target_amount' => 'decimal:2',
        'raised_amount' => 'decimal:2',
        'donor_count' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
        'status' => CampaignStatus::class,
        'visibility' => CampaignVisibility::class,
        'meta' => 'array',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }

    public function recurringPlans(): HasMany
    {
        return $this->hasMany(RecurringPlan::class);
    }

    public function getRouteKeyName(): string
    {
        return 'public_id';
    }
}