# Database Migrations Spec

## Project
Laravel 11+ project at `/Users/wmafendi/Herd/nofund`. Uses PostgreSQL.

## Existing Migrations
- `0001_01_01_000000_create_users_table.php` — default Laravel users + password_reset_tokens + sessions
- `0001_01_01_000001_create_cache_table.php`
- `0001_01_01_000002_create_jobs_table.php`

## Strategy
- DO NOT modify existing Laravel migration files.
- Create new migration files with timestamps.
- Use `uuid` primary keys for all new tables (not `id()`).
- Use `$table->foreignUlid()` or `$table->foreignUuid()` for foreign keys.
- Follow exact schema from `ERD.md`.

## Migration Order & Naming

| # | Filename | Table | Depends On |
|---|----------|-------|------------|
| 1 | `2024_06_09_000001_create_organizations_table.php` | organizations | — |
| 2 | `2024_06_09_000002_add_organization_fields_to_users_table.php` | users (modify) | organizations |
| 3 | `2024_06_09_000003_create_bank_accounts_table.php` | bank_accounts | organizations |
| 4 | `2024_06_09_000004_create_campaigns_table.php` | campaigns | organizations, users |
| 5 | `2024_06_09_000005_create_donors_table.php` | donors | organizations |
| 6 | `2024_06_09_000006_create_donations_table.php` | donations | organizations, campaigns, donors |
| 7 | `2024_06_09_000007_create_recurring_plans_table.php` | recurring_plans | organizations, donors, campaigns |
| 8 | `2024_06_09_000008_create_refunds_table.php` | refunds | organizations, donations, users |
| 9 | `2024_06_09_000009_create_payouts_table.php` | payouts | organizations, bank_accounts |
| 10 | `2024_06_09_000010_create_transactions_table.php` | transactions | organizations |
| 11 | `2024_06_09_000011_create_api_keys_table.php` | api_keys | organizations |
| 12 | `2024_06_09_000012_create_webhooks_table.php` | webhooks | organizations |
| 13 | `2024_06_09_000013_create_webhook_deliveries_table.php` | webhook_deliveries | webhooks |
| 14 | `2024_06_09_000014_create_activity_logs_table.php` | activity_logs | organizations, users |

## Key Patterns
- All tables: `$table->uuid('id')->primary()`
- Soft deletes where specified: `$table->softDeletes()`
- Timestamps: `$table->timestamps()`
- Foreign keys: `$table->foreignUuid('...')->constrained('...')->cascadeOnDelete()` or `restrictOnDelete()` as appropriate
- JSON: `$table->json('...')`
- Decimal: `$table->decimal('amount', 10, 2)->default(0.00)`
- Enums: `$table->enum('status', ['active', '...'])`
- Indexes: `$table->index(['organization_id', 'status', 'created_at'])`

## Critical Constraints
- `campaigns`: unique composite `(organization_id, slug)`
- `refunds`: unique `donation_id`
- `donors`: unique composite `(organization_id, email)`
- `users`: do NOT drop/re-create. Only add columns.
