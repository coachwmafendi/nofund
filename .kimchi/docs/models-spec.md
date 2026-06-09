# Models & Relationships Spec

## Goal
Create comprehensive Laravel Eloquent models for all entities defined in ERD.md, with proper relationships, casts, UUID support, `public_id` handling, multi-tenancy scoping, and soft deletes.

## Architecture

### Directory Structure

```
app/
├── Models/
│   ├── Concerns/
│   │   ├── HasPublicId.php          # Trait: generates public_id on create
│   │   ├── HasOrganization.php      # Trait: auto-belongsTo Organization, adds global scope
│   │   ├── HasUuid.php              # Trait: UUID primary key
│   │   └── ImmutableRecord.php      # Trait: prevents update/delete (for Transaction, ActivityLog)
│   ├── BaseModel.php                # Abstract: UUID PK, timestamps, common functionality
│   ├── Organization.php
│   ├── User.php                     # Extends BaseModel, NOT extends Authenticatable (separate concern)
│   ├── BankAccount.php
│   ├── Campaign.php
│   ├── Donor.php
│   ├── Donation.php
│   ├── RecurringPlan.php
│   ├── Payout.php
│   ├── Transaction.php
│   ├── Refund.php
│   ├── ApiKey.php
│   ├── Webhook.php
│   ├── WebhookDelivery.php
│   └── ActivityLog.php
```

Wait — `User` must extend `Illuminate\Foundation\Auth\User` (or `Authenticatable`). We'll make User extend `Authenticatable` but use the same traits.

Revised:
```
app/
├── Models/
│   ├── Concerns/
│   │   ├── HasPublicId.php
│   │   ├── BelongsToOrganization.php
│   │   └── HasUuid.php
│   ├── OrganizationBaseModel.php     # Abstract for org-scoped models
│   ├── Organization.php
│   ├── User.php
│   ├── BankAccount.php
│   ├── Campaign.php
│   ├── Donor.php
│   ├── Donation.php
│   ├── RecurringPlan.php
│   ├── Payout.php
│   ├── Transaction.php
│   ├── Refund.php
│   ├── ApiKey.php
│   ├── Webhook.php
│   ├── WebhookDelivery.php
│   └── ActivityLog.php
```

## Detailed Specs

### Trait: HasUuid
```php
trait HasUuid
{
    protected $keyType = 'string';
    public $incrementing = false;

    protected static function bootHasUuid(): void
    {
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) \Illuminate\Support\Str::uuid7();
            }
        });
    }
}
```

### Trait: HasPublicId
```php
trait HasPublicId
{
    public static string $publicIdPrefix = '';

    protected static function bootHasPublicId(): void
    {
        static::creating(function ($model) {
            if (empty($model->public_id)) {
                $model->public_id = static::generatePublicId();
            }
        });
    }

    public static function generatePublicId(): string
    {
        return static::$publicIdPrefix . '_' . \Illuminate\Support\Str::lower(\Illuminate\Support\Str::random(26));
    }

    public static function findByPublicId(string $publicId): ?static
    {
        return static::where('public_id', $publicId)->first();
    }
}
```

### Trait: BelongsToOrganization
```php
trait BelongsToOrganization
{
    use Illuminate\Database\Eloquent\Relations\BelongsTo;

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}
```

### OrganizationBaseModel (abstract)
```php
abstract class OrganizationBaseModel extends Model
{
    use HasUuid, BelongsToOrganization, HasPublicId, SoftDeletes;

    protected $guarded = []; // or specific fillable per model
}
```

Wait — we shouldn't force SoftDeletes on all org models. Not all need it.
Revised:
```php
abstract class OrganizationBaseModel extends Model
{
    use HasUuid, BelongsToOrganization, HasPublicId;

    protected $guarded = [];
}
```

Individual models opt into SoftDeletes if needed.

## Model Specs

### 1. Organization
```php
class Organization extends Model
{
    use HasUuid, HasPublicId, SoftDeletes;

    protected $publicIdPrefix = 'org';
    protected $fillable = [...];

    protected $casts = [
        'address' => 'array',
        'status' => OrgStatus::class, // BackedEnum
        'type' => OrgType::class,
    ];

    // Relationships
    public function users() { return $this->hasMany(User::class); }
    public function campaigns() { return $this->hasMany(Campaign::class); }
    public function donations() { return $this->hasMany(Donation::class); }
    public function donors() { return $this->hasMany(Donor::class); }
    public function recurringPlans() { return $this->hasMany(RecurringPlan::class); }
    public function payouts() { return $this->hasMany(Payout::class); }
    public function bankAccounts() { return $this->hasMany(BankAccount::class); }
    public function apiKeys() { return $this->hasMany(ApiKey::class); }
    public function webhooks() { return $this->hasMany(Webhook::class); }
    public function refunds() { return $this->hasMany(Refund::class); }
    public function transactions() { return $this->hasMany(Transaction::class); }
    public function activityLogs() { return $this->hasMany(ActivityLog::class); }
}
```

### 2. User
```php
class User extends Authenticatable
{
    use HasUuid, HasPublicId, Notifiable;

    protected $publicIdPrefix = 'usr';

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'role' => UserRole::class,
        'status' => UserStatus::class,
    ];

    public function organization() { return $this->belongsTo(Organization::class); }
    public function createdCampaigns() { return $this->hasMany(Campaign::class, 'created_by'); }
    public function processedRefunds() { return $this->hasMany(Refund::class, 'processed_by'); }
}
```

### 3. Campaign
```php
class Campaign extends OrganizationBaseModel
{
    use SoftDeletes;

    protected $publicIdPrefix = 'cmp';

    protected $casts = [
        'target_amount' => 'decimal:2',
        'raised_amount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'status' => CampaignStatus::class,
        'visibility' => CampaignVisibility::class,
        'meta' => 'array',
    ];

    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
    public function donations() { return $this->hasMany(Donation::class); }
    public function recurringPlans() { return $this->hasMany(RecurringPlan::class); }
}
```

### 4. Donor
```php
class Donor extends OrganizationBaseModel
{
    protected $publicIdPrefix = 'dnr';

    protected $casts = [
        'total_donated' => 'decimal:2',
        'first_donation_at' => 'datetime',
        'last_donation_at' => 'datetime',
        'tags' => 'array',
        'is_anonymous_preference' => 'boolean',
    ];

    public function donations() { return $this->hasMany(Donation::class); }
    public function recurringPlans() { return $this->hasMany(RecurringPlan::class); }
}
```

### 5. Donation
```php
class Donation extends OrganizationBaseModel
{
    protected $publicIdPrefix = 'dnt';

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

    public function campaign() { return $this->belongsTo(Campaign::class); }
    public function donor() { return $this->belongsTo(Donor::class); }
    public function refund() { return $this->hasOne(Refund::class); }
    public function transactions() { return $this->morphMany(Transaction::class, 'transactionable'); }
}
```

### 6. RecurringPlan
```php
class RecurringPlan extends OrganizationBaseModel
{
    protected $publicIdPrefix = 'rcp';

    protected $casts = [
        'amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'next_charge_date' => 'date',
        'frequency' => Frequency::class,
        'status' => PlanStatus::class,
        'meta' => 'array',
    ];

    public function donor() { return $this->belongsTo(Donor::class); }
    public function campaign() { return $this->belongsTo(Campaign::class); }
}
```

### 7. Payout
```php
class Payout extends OrganizationBaseModel
{
    protected $publicIdPrefix = 'pyt';

    protected $casts = [
        'amount' => 'decimal:2',
        'donations' => 'array',
        'status' => PayoutStatus::class,
        'paid_at' => 'datetime',
    ];

    public function bankAccount() { return $this->belongsTo(BankAccount::class); }
    public function transactions() { return $this->morphMany(Transaction::class, 'transactionable'); }
}
```

### 8. Transaction
```php
class Transaction extends OrganizationBaseModel
{
    // No HasPublicId trait needed (internal only)

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'type' => TransactionType::class,
        'meta' => 'array',
    ];

    public function transactionable() { return $this->morphTo(); }
}
```

Note: Transaction should NOT be deletable or editable (immutable ledger). Add model-level prevention.

### 9. Refund
```php
class Refund extends OrganizationBaseModel
{
    protected $publicIdPrefix = 'rfd';

    protected $casts = [
        'amount' => 'decimal:2',
        'status' => RefundStatus::class,
    ];

    public function donation() { return $this->belongsTo(Donation::class); }
    public function processor() { return $this->belongsTo(User::class, 'processed_by'); }
    public function transactions() { return $this->morphMany(Transaction::class, 'transactionable'); }
}
```

### 10. BankAccount
```php
class BankAccount extends OrganizationBaseModel
{
    // No HasPublicId needed (internal only)

    protected $casts = [
        'is_default' => 'boolean',
        'type' => BankAccountType::class,
        'status' => BankAccountStatus::class,
    ];

    public function payouts() { return $this->hasMany(Payout::class); }
}
```

### 11. ApiKey
```php
class ApiKey extends OrganizationBaseModel
{
    protected $publicIdPrefix = 'key';

    protected $casts = [
        'scopes' => 'array',
        'last_used_at' => 'datetime',
        'revoked_at' => 'datetime',
    ];

    protected $hidden = ['key_hash'];
}
```

Note: ApiKey does NOT use the full HasPublicId auto-generation — its public_id IS the API key prefix shown to users. Needs custom handling.
Actually, keep it simple: `public_id` here serves as the label/key name. The actual secret is `key_hash`. We can still use HasPublicId for the `public_id` column.

### 12. Webhook
```php
class Webhook extends OrganizationBaseModel
{
    protected $publicIdPrefix = 'whk';

    protected $casts = [
        'events' => 'array',
        'last_triggered_at' => 'datetime',
        'status' => WebhookStatus::class,
    ];

    public function deliveries() { return $this->hasMany(WebhookDelivery::class); }
}
```

### 13. WebhookDelivery
```php
class WebhookDelivery extends OrganizationBaseModel
{
    // No HasPublicId (internal)

    protected $casts = [
        'payload' => 'array',
        'delivered_at' => 'datetime',
    ];

    public function webhook() { return $this->belongsTo(Webhook::class); }
}
```

### 14. ActivityLog
```php
class ActivityLog extends Model
{
    use HasUuid, BelongsToOrganization;
    // No HasPublicId
    // No soft deletes

    protected $casts = [
        'properties' => 'array',
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function subject() { return $this->morphTo(); }
}
```

## Enums to Create

Create in `app/Enums/`:

```
app/Enums/
├── OrganizationStatus.php
├── OrganizationType.php
├── UserRole.php
├── UserStatus.php
├── CampaignStatus.php
├── CampaignVisibility.php
├── DonationStatus.php
├── PaymentMethod.php
├── RecurringFrequency.php
├── RecurringPlanStatus.php
├── PayoutStatus.php
├── TransactionType.php
├── RefundStatus.php
├── BankAccountType.php
├── BankAccountStatus.php
├── WebhookStatus.php
```

All as Backed Enums (string values):

```php
enum CampaignStatus: string
{
    case DRAFT = 'draft';
    case ACTIVE = 'active';
    case PAUSED = 'paused';
    case COMPLETED = 'completed';
}
```

## Multi-Tenancy: Global Scope

In `OrganizationBaseModel`, add a global scope that auto-applies `->where('organization_id', ...)` based on the current authenticated user's organization. If no user is authenticated (API calls), check a context/service container binding.

For now, simpler approach: add a static method or middleware that sets the current org context, and the scope reads from that.

```php
// In OrganizationBaseModel boot
static::addGlobalScope('organization', function (Builder $builder) {
    if ($orgId = OrganizationContext::getId()) {
        $builder->where('organization_id', $orgId);
    }
});
```

## Acceptance Criteria

- [ ] All 14 model files exist in `app/Models/`
- [ ] All 3 trait files exist in `app/Models/Concerns/`
- [ ] All 16 enum files exist in `app/Enums/`
- [ ] `OrganizationContext` helper exists
- [ ] `HasUuid` trait generates UUID v7 on create
- [ ] `HasPublicId` trait auto-generates prefixed `public_id` on create
- [ ] All models have correct relationships defined
- [ ] `Transaction` cannot be updated or deleted (model-level throw)
- [ ] `ActivityLog` cannot be updated or deleted
- [ ] `php artisan migrate:fresh --seed` works (seeders not part of this task, just verify migrations still work)
- [ ] `php artisan tinker` allows creating an Organization with auto-generated UUID and public_id
