<?php

namespace App\Models;

use App\Enums\TransactionType;
use App\Models\Concerns\BelongsToOrganization;
use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Transaction extends Model
{
    use HasFactory;
    use HasUuid;
    use BelongsToOrganization;

    protected $fillable = [
        'organization_id',
        'transactionable_type',
        'transactionable_id',
        'type',
        'amount',
        'currency',
        'balance_after',
        'description',
        'meta',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'type' => TransactionType::class,
        'meta' => 'array',
    ];

    public function transactionable(): MorphTo
    {
        return $this->morphTo();
    }

    public function update(array $attributes = [], array $options = []): bool
    {
        throw new \RuntimeException('Transactions are immutable and cannot be updated.');
    }

    public function delete(): ?bool
    {
        throw new \RuntimeException('Transactions are immutable and cannot be deleted.');
    }
}