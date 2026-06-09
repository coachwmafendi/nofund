# public_id Addition Spec

## Scope
1. Update `ERD.md` — change entity names to plural table names, add `public_id` column to relevant tables.
2. Create new migration files to add `public_id` columns to existing tables (do NOT modify already-run migrations).

## Tables Requiring public_id

| Table | Prefix | Example | Rationale |
|-------|--------|---------|-----------|
| organizations | org_ | org_8xK3mP | Tenant routing, shareable URLs |
| users | usr_ | usr_aB3dEf | Member profiles, audit references |
| campaigns | cmp_ | cmp_4jK9lM | Public campaign URLs, embeds |
| donors | dnr_ | dnr_2nP5qR | Donor directory, donor detail pages |
| donations | dnt_ | dnt_7mT4vX | Receipt URLs, donor dashboards |
| recurring_plans | rcp_ | rcp_9wQ2zY | Plan management, donor self-service |
| payouts | pyt_ | pyt_3xL8aB | Payout detail pages |
| refunds | rfd_ | rfd_5kM1cD | Refund records |
| api_keys | key_ | key_6tN9eF | API key display (masked prefix) |
| webhooks | whk_ | whk_4hJ7gH | Webhook management |

## Tables NOT Getting public_id

- bank_accounts — internal only, never exposed directly
- transactions — immutable ledger, referenced by polymorphic link
- webhook_deliveries — internal delivery log
- activity_logs — internal audit trail

## Column Definition

```php
$table->string('public_id', 32)->unique()->after('id')->nullable();
```

- 32 chars: prefix (3-4) + underscore + random alphanumeric (22-27 chars)
- Unique index per table
- Nullable initially for migration safety
- Backfilled in a separate step (not required now)

## public_id Format

Generated via `Str::lower(Str::random(26))` with prefix, e.g.:
```
org_ . Str::lower(Str::random(26))  => org_a1b2c3d4e5f6g7h8i9j0k1l2
```

## Migration Files to Create

Create new migrations with current timestamp (use `php artisan make:migration` or write manually):

```
2026_06_09_100001_add_public_id_to_organizations_table.php
2026_06_09_100002_add_public_id_to_users_table.php
2026_06_09_100003_add_public_id_to_campaigns_table.php
2026_06_09_100004_add_public_id_to_donors_table.php
2026_06_09_100005_add_public_id_to_donations_table.php
2026_06_09_100006_add_public_id_to_recurring_plans_table.php
2026_06_09_100007_add_public_id_to_payouts_table.php
2026_06_09_100008_add_public_id_to_refunds_table.php
2026_06_09_100009_add_public_id_to_api_keys_table.php
2026_06_09_100010_add_public_id_to_webhooks_table.php
```

## ERD.md Updates

- Change all entity section headings to plural: `4.1 Organization` → `4.1 Organizations`
- Change Mermaid entity names to plural: ORGANIZATION → ORGANIZATIONS
- Add `public_id` attribute to each relevant entity definition
- Update relationship descriptions to use plural nouns
