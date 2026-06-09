<?php

namespace App\Models;

use App\Models\Concerns\BelongsToOrganization;
use App\Models\Concerns\HasPublicId;
use App\Models\Concerns\HasUuid;
use App\Support\OrganizationContext;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

abstract class OrganizationBaseModel extends Model
{
    use HasFactory;
    use HasUuid;
    use BelongsToOrganization;
    use HasPublicId;

    protected $guarded = [];

    protected static function boot(): void
    {
        parent::boot();

        static::addGlobalScope('organization', function (Builder $builder) {
            if ($orgId = OrganizationContext::getId()) {
                $builder->where('organization_id', $orgId);
            }
        });
    }
}