<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApiKey extends OrganizationBaseModel
{
    protected static $publicIdPrefix = 'key';

    protected $fillable = [
        'organization_id',
        'name',
        'key_hash',
        'last_used_at',
        'scopes',
        'revoked_at',
    ];

    protected $hidden = [
        'key_hash',
    ];

    protected $casts = [
        'scopes' => 'array',
        'last_used_at' => 'datetime',
        'revoked_at' => 'datetime',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}