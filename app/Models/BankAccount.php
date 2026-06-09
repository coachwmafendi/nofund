<?php

namespace App\Models;

use App\Enums\BankAccountStatus;
use App\Enums\BankAccountType;
use App\Models\Concerns\BelongsToOrganization;
use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BankAccount extends Model
{
    use HasFactory;
    use HasUuid;
    use BelongsToOrganization;

    protected $guarded = [];

    protected $casts = [
        'is_default' => 'boolean',
        'type' => BankAccountType::class,
        'status' => BankAccountStatus::class,
    ];

    public function payouts(): HasMany
    {
        return $this->hasMany(Payout::class);
    }
}