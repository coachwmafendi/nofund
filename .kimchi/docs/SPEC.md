# Factory and Seeder Specification

## Context
Create Laravel Factories and Seeders for the nofund project. The project is a donation platform with organizations, campaigns, donors, donations, and related entities.

## Models and Enums Summary

### Enums
- **OrganizationType**: MOSQUE, NGO, CHARITY, COMMUNITY, INDIVIDUAL
- **OrganizationStatus**: ACTIVE, SUSPENDED, DEACTIVATED
- **UserRole**: SUPER_ADMIN, ADMIN, MANAGER, VIEWER
- **UserStatus**: ACTIVE, INVITED, DEACTIVATED
- **CampaignStatus**: DRAFT, ACTIVE, PAUSED, COMPLETED
- **CampaignVisibility**: PUBLIC, UNLISTED, PRIVATE
- **DonationStatus**: PENDING, SUCCEEDED, FAILED, REFUNDED
- **PaymentMethod**: CARD, BANK_TRANSFER, EWALLET, CASH
- **RecurringFrequency**: WEEKLY, MONTHLY, YEARLY
- **RecurringPlanStatus**: ACTIVE, PAUSED, CANCELLED, EXPIRED
- **BankAccountType**: SAVINGS, CURRENT
- **BankAccountStatus**: ACTIVE, INACTIVE
- **PayoutStatus**: PENDING, PROCESSING, PAID, FAILED
- **RefundStatus**: PENDING, SUCCEEDED, FAILED
- **TransactionType**: DONATION, REFUND, FEE, PAYOUT, ADJUSTMENT
- **WebhookStatus**: ACTIVE, PAUSED

### Models (with key fields)
1. **Organization**: name, slug, type, logo_url, description, contact_email, contact_phone, address(array), timezone, currency, status, plan_id
2. **User**: name, email, password, avatar_url, role, status, organization_id, last_login_at, email_verified_at
3. **Campaign** (extends OrganizationBaseModel): organization_id, title, slug, description, cover_image_url, target_amount, raised_amount, donor_count, status, visibility, category, start_date, end_date, embed_code, meta, created_by
4. **Donor** (extends OrganizationBaseModel): organization_id, name, email, phone, is_anonymous_preference, total_donated, donation_count, first_donation_at, last_donation_at, notes, tags(array)
5. **Donation** (extends OrganizationBaseModel): organization_id, campaign_id, donor_id, amount, currency, status, payment_method, payment_gateway, gateway_transaction_id, gateway_response(array), is_anonymous, donor_name, donor_email, donor_phone, donor_message, refunded_amount, refund_reason, receipt_sent, receipt_url, ip_address, user_agent, source_url, meta(array)
6. **RecurringPlan** (extends OrganizationBaseModel): organization_id, donor_id, campaign_id, amount, currency, frequency, status, start_date, end_date, next_charge_date, total_charges, total_amount, payment_method_token, gateway_subscription_id, meta(array)
7. **BankAccount** (uses BelongsToOrganization trait, not OrganizationBaseModel): account_name, account_number, bank_name, bank_code, type, is_default, status
8. **Payout** (extends OrganizationBaseModel): organization_id, amount, currency, status, bank_account_id, gateway_payout_id, donations(array), failure_reason, paid_at
9. **Transaction** (uses BelongsToOrganization, not OrganizationBaseModel): organization_id, transactionable_type, transactionable_id, type, amount, currency, balance_after, description, meta(array) - IMMUTABLE
10. **Refund** (extends OrganizationBaseModel): organization_id, donation_id, amount, currency, reason, status, gateway_refund_id, processed_by - IMMUTABLE
11. **ApiKey** (extends OrganizationBaseModel): organization_id, name, key_hash, last_used_at, scopes(array), revoked_at
12. **Webhook** (extends OrganizationBaseModel): organization_id, url, secret, events(array), status, last_triggered_at
13. **WebhookDelivery** (uses BelongsToOrganization): webhook_id, event, payload(array), response_status, response_body, attempt_count, delivered_at
14. **ActivityLog** (uses BelongsToOrganization): organization_id, user_id, action, subject_type, subject_id, properties(array), ip_address - IMMUTABLE

### Traits Used
- **HasUuid**: Auto-generates UUID v7 on creation (key is id, not uuid column)
- **HasPublicId**: Auto-generates public_id with prefix on creation (e.g., org_xxxxx)
- **BelongsToOrganization**: Adds organization() belongsTo relationship
- **OrganizationBaseModel**: Combines HasUuid, BelongsToOrganization, HasPublicId + global scope

## Factories to Create

All factories should be in `database/factories/`

### 1. OrganizationFactory
```php
- name: mix of Malay/English org names (Masjid, yayasan, NGO names)
- slug: derived from name (Str::slug)
- type: random OrganizationType
- logo_url: nullable faker->imageUrl
- description: faker->paragraph
- contact_email: faker->companyEmail
- contact_phone: faker->optional()->phoneNumber
- address: json_encode(['street' => faker->address, 'city' => faker->city, 'state' => faker->state, 'postal' => faker->postcode])
- timezone: 'Asia/Kuala_Lumpur'
- currency: 'MYR'
- status: OrganizationStatus::ACTIVE
- plan_id: null
```

### 2. UserFactory (update existing)
```php
- name: faker->name
- email: faker->unique()->safeEmail
- email_verified_at: now()
- password: Hash::make('password')
- avatar_url: faker->optional()->imageUrl
- role: weighted random - 60% VIEWER, 25% MANAGER, 10% ADMIN, 5% SUPER_ADMIN
- status: UserStatus::ACTIVE
- organization_id: resolved via factory relationship
- last_login_at: faker->optional()->dateTimeBetween('-1 month', 'now')
- remember_token: Str::random(10)
```

### 3. CampaignFactory
```php
- organization_id: resolved via relationship
- title: realistic Malaysian campaign names (Wakaf, banjir, pendidikan, etc.)
- slug: derived from title
- description: faker->paragraphs(3, true)
- cover_image_url: faker->optional()->imageUrl
- target_amount: random between 1000 and 500000
- raised_amount: 0 (will be updated by donations)
- donor_count: 0
- status: weighted - 30% DRAFT, 50% ACTIVE, 10% PAUSED, 10% COMPLETED
- visibility: weighted - 70% PUBLIC, 20% UNLISTED, 10% PRIVATE
- category: random from [Wakaf, Zakat, Sedekah, Dana Pendidikan, Bantuan Kecemasan, Pembangunan]
- start_date: faker->dateTimeBetween('-6 months', '+1 month')
- end_date: 30% null, otherwise start_date + 30-90 days
- embed_code: null
- meta: faker->optional()->randomElements([...]) as array
- created_by: resolved via relationship to a user in same org
```

### 4. DonorFactory
```php
- organization_id: resolved via relationship
- name: 15% "Anonymous", else faker->name
- email: 85% faker->unique()->safeEmail, 15% null (for anonymous)
- phone: faker->optional(0.7)->phoneNumber
- is_anonymous_preference: faker->boolean(0.1)
- total_donated: 0 (updated by donations)
- donation_count: 0
- first_donation_at: null
- last_donation_at: null
- notes: faker->optional(0.3)->sentence
- tags: faker->optional(0.4)->randomElements(['recurring', 'major', 'corporate', 'first-time', 'vip'], random_int(1,3))
```

### 5. DonationFactory
```php
- organization_id: resolved via relationship
- campaign_id: 90% existing campaign in same org, 10% null
- donor_id: 80% existing donor in same org, 20% null (guest donation)
- amount: weighted random - [10=>30%, 20=>25%, 50=>20%, 100=>12%, 200=>6%, 500=>3%, 1000=>2%, 2000=>1%, 5000=>1%]
- currency: 'MYR'
- status: weighted - 75% SUCCEEDED, 10% PENDING, 10% FAILED, 5% REFUNDED
- payment_method: weighted - 50% CARD, 30% BANK_TRANSFER, 15% EWALLET, 5% CASH
- payment_gateway: weighted - 60% stripe, 25% billplz, 15% toyyibpay
- gateway_transaction_id: faker->uuid
- gateway_response: faker->optional()->randomElements([...]) as array
- is_anonymous: faker->boolean(0.2)
- donor_name: snapshot from donor->name or faker->name
- donor_email: snapshot from donor->email or faker->email
- donor_phone: faker->optional()->phoneNumber
- donor_message: faker->optional(0.4)->sentence
- refunded_amount: 0
- refund_reason: null
- receipt_sent: faker->boolean
- receipt_url: faker->optional()->url
- ip_address: faker->ipv4
- user_agent: faker->userAgent
- source_url: faker->optional(0.6)->url
- meta: faker->optional()->randomElements([...]) as array
```

### 6. RecurringPlanFactory
```php
- organization_id: resolved via relationship
- donor_id: existing donor in same org
- campaign_id: 70% existing campaign in same org, 30% null
- amount: faker->randomElement([30, 50, 100, 200, 500])
- currency: 'MYR'
- frequency: weighted - 10% WEEKLY, 85% MONTHLY, 5% YEARLY
- status: weighted - 70% ACTIVE, 10% PAUSED, 10% CANCELLED, 10% EXPIRED
- start_date: faker->dateTimeBetween('-12 months', '-1 month')
- end_date: faker->optional(0.3)->dateTimeBetween('now', '+6 months')
- next_charge_date: calculated based on start_date + frequency
- total_charges: calculated based on start_date to now
- total_amount: total_charges * amount
- payment_method_token: null
- gateway_subscription_id: faker->optional()->uuid
- meta: null
```

### 7. BankAccountFactory
```php
- No organization relationship via factory - handled manually
- account_name: faker->name
- account_number: faker->numerify('##########')
- bank_name: faker->randomElement(['Maybank', 'CIMB Bank', 'RHB Bank', 'Public Bank', 'Bank Islam', 'Hong Leong Bank', 'Bank Rakyat', 'Bank Muamalat'])
- bank_code: derived from bank_name (e.g., MBBM, CIMB, RHBB, PBB, BIMB, HLBB, BABB, BMAL)
- type: random BankAccountType
- is_default: first account = true (handled by seeder)
- status: BankAccountStatus::ACTIVE
```

### 8. PayoutFactory
```php
- organization_id: resolved via relationship
- amount: random between 500 and 50000
- currency: 'MYR'
- status: weighted - 20% PENDING, 10% PROCESSING, 60% PAID, 10% FAILED
- bank_account_id: existing bank account in same org
- gateway_payout_id: faker->optional(0.7)->uuid
- donations: json array of 1-20 random donation IDs (all succeeded, non-refunded)
- failure_reason: null or faker->sentence
- paid_at: set if status is PAID (dateTimeBetween('-1 month', 'now'))
```

### 9. TransactionFactory
```php
- organization_id: resolved via relationship
- transactionable_type: faker->randomElement([Donation::class, Refund::class, Payout::class])
- transactionable_id: faker->uuid
- type: random TransactionType
- amount: faker->randomFloat(2, 10, 50000)
- currency: 'MYR'
- balance_after: faker->randomFloat(2, 0, 100000)
- description: faker->sentence
- meta: faker->optional()->randomElements([...]) as array
```

### 10. RefundFactory
```php
- organization_id: resolved via relationship
- donation_id: existing succeeded donation in same org (not already refunded)
- amount: less than or equal to linked donation amount
- currency: 'MYR'
- reason: faker->sentence
- status: weighted - 20% PENDING, 70% SUCCEEDED, 10% FAILED
- gateway_refund_id: faker->optional(0.7)->uuid
- processed_by: existing user in same org
```

### 11. ApiKeyFactory
```php
- organization_id: resolved via relationship
- name: faker->words(2, true) . ' Key'
- key_hash: bcrypt(Str::random(32))
- last_used_at: faker->optional(0.5)->dateTimeBetween('-1 month', 'now')
- scopes: json array of random subset of ['donations:read', 'donations:write', 'campaigns:read', 'campaigns:write', 'donors:read', 'reports:read']
- revoked_at: 10% have a revoked date
```

### 12. WebhookFactory
```php
- organization_id: resolved via relationship
- url: faker->url
- secret: Str::random(32)
- events: random subset of ['donation.succeeded', 'donation.failed', 'donation.refunded', 'payout.paid', 'payout.failed', 'campaign.completed', 'campaign.created']
- status: weighted - 90% ACTIVE, 10% PAUSED
- last_triggered_at: faker->optional(0.6)->dateTimeBetween('-1 month', 'now')
```

### 13. WebhookDeliveryFactory
```php
- organization_id: resolved via relationship
- webhook_id: existing webhook in same org
- event: faker->randomElement(['donation.succeeded', 'donation.failed', 'donation.refunded', 'payout.paid', 'payout.failed', 'campaign.completed', 'campaign.created'])
- payload: json_encode of fake structured data
- response_status: weighted - 70% 200, 20% 404, 10% 500
- response_body: faker->optional()->sentence
- attempt_count: random 1-3
- delivered_at: set if response_status is 200
```

### 14. ActivityLogFactory
```php
- organization_id: resolved via relationship
- user_id: existing user in same org (50%) or null (50% for system actions)
- action: faker->randomElement(['donation.created', 'donation.failed', 'campaign.updated', 'campaign.created', 'campaign.completed', 'refund.processed', 'refund.requested', 'user.invited', 'user.login', 'payout.approved', 'payout.paid'])
- subject_type: faker->randomElement([Campaign::class, Donation::class, Donor::class, User::class, Refund::class, Payout::class])
- subject_id: faker->uuid
- properties: json_encode of fake change data
- ip_address: faker->ipv4
```

## Seeder to Create

### DatabaseSeeder
Create seeders in correct dependency order:
1. Organizations (5)
2. Users (5-10 per org)
3. Bank Accounts (1-3 per org)
4. Campaigns (3-8 per org, with created_by user)
5. Donors (20-50 per org)
6. Donations (50-200 per org)
7. Recurring Plans (5-15 per org)
8. Refunds (5-10% of succeeded donations per org)
9. Payouts (2-5 per org)
10. Transactions (2-5 per donation/refund/payout)
11. API Keys (1-3 per org)
12. Webhooks (1-2 per org)
13. Webhook Deliveries (0-10 per webhook)
14. Activity Logs (20-50 per org)

## Acceptance Criteria

1. All 14 factories create valid model instances
2. All factories use proper Faker data for Malaysian context
3. Enums are properly cast using Laravel's enum casting
4. Factories handle nullable relationships properly
5. DatabaseSeeder runs without errors
6. `php artisan migrate:fresh --seed` completes successfully
7. Each organization has proper associated records
8. Foreign key relationships are respected