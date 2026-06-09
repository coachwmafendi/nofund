<?php

namespace App\Models;

use App\Enums\WebhookStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Webhook extends OrganizationBaseModel
{
    protected static $publicIdPrefix = 'whk';

    protected $fillable = [
        'organization_id',
        'url',
        'secret',
        'events',
        'status',
        'last_triggered_at',
    ];

    protected $casts = [
        'events' => 'array',
        'last_triggered_at' => 'datetime',
        'status' => WebhookStatus::class,
    ];

    protected $hidden = [
        'secret',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function deliveries(): HasMany
    {
        return $this->hasMany(WebhookDelivery::class);
    }
}