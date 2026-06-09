# PRD.md — Product Requirements Document

## nofund — Fundraising & Donation Management Platform

**Version:** 1.0  
**Date:** 2026-06-09  
**Status:** Draft  
**Author:** Product Team

---

## 1. Product Overview

### 1.1 Vision

nofund is a modern, premium SaaS platform that empowers organizations — NGOs, mosques, tahfiz centers, charities, and campaign owners — to create fundraising campaigns, receive donations, track donor activity, manage payouts, and monitor financial performance through a beautiful, intuitive dashboard.

We believe managing fundraising should feel as polished as using Stripe, Linear, or Vercel — not like a generic admin panel.

### 1.2 Mission

To simplify fundraising operations for organizations by providing:
- A trustworthy, professional interface that builds donor confidence
- Powerful campaign and donation management tools
- Transparent financial tracking and reporting
- Developer-friendly APIs and embeddable forms

### 1.3 Value Proposition

| For Organizations | For Donors |
|------------------|------------|
| Create and manage campaigns in minutes | Clean, trustworthy donation experience |
| Track every donation in real-time | Transparent campaign progress |
| Automate recurring donations | Secure payment processing |
| Generate financial reports effortlessly | Receipts and confirmation emails |
| Payout management with full traceability | |

---

## 2. Target Audience & Personas

### 2.1 Primary Personas

**Ahmad — Mosque Treasurer**
- Age: 45
- Role: Manages mosque fundraising (wakaf, donations, zakat)
- Pain points: Spreadsheet chaos, no visibility into donation trends, difficult to generate reports for auditors
- Needs: Simple campaign creation, donor tracking, automated reports

**Sarah — NGO Operations Manager**
- Age: 34
- Role: Runs multiple fundraising campaigns across regions
- Pain points: Managing many campaigns simultaneously, team coordination, donor communication
- Needs: Multi-campaign dashboard, team member access, donor export, webhook integrations

**Faisal — Tahfiz Center Administrator**
- Age: 38
- Role: Manages student sponsorships and center donations
- Pain points: Manual tracking of recurring student sponsorships, donor follow-up
- Needs: Recurring donation plans, donor profiles, automated receipts

**Irfan — Developer at a Non-Profit**
- Age: 28
- Role: Maintains the organization's website and donation infrastructure
- Pain points: Complex payment gateway integrations, webhook debugging
- Needs: Clean API, embeddable forms, comprehensive webhook support, clear documentation

### 2.2 Organization Types

1. **Religious Organizations** — Mosques, suraus, tahfiz centers
2. **Charities & NGOs** — Social welfare, education, healthcare
3. **Community Groups** — Neighborhood associations, clubs
4. **Individual Campaigners** — Personal causes, emergencies, events

---

## 3. Core Features & Requirements

### 3.1 Campaign Management

| Feature | Priority | Description |
|---------|----------|-------------|
| Create Campaign | P0 | Wizard-style or single-page form to create a campaign with title, description, target amount, cover image, start/end dates |
| Edit Campaign | P0 | Modify campaign details, extend deadlines, update targets |
| Campaign Status Control | P0 | Active, Draft, Paused, Completed states with clear workflow |
| Campaign Analytics | P1 | Progress bar, amount raised vs target, donor count, daily trends |
| Campaign Embed | P1 | Copy-paste embed code for external websites |
| Campaign Share | P2 | Social sharing links, QR code generation |

**Campaign Fields:**
- Title
- Slug/URL
- Description (rich text)
- Cover image
- Target amount (RM)
- Start date / End date (optional)
- Status: Draft → Active → Paused → Completed
- Visibility: Public / Unlisted / Private
- Category (e.g., Wakaf, Zakat, General Donation, Emergency)
- Organization association

### 3.2 Donation Management

| Feature | Priority | Description |
|---------|----------|-------------|
| Receive Donations | P0 | Process one-time and recurring donations via payment gateway |
| Donation List | P0 | Paginated, sortable, filterable table of all donations |
| Donation Detail | P0 | Full view of a single donation with donor info, timeline, status |
| Donation Status Tracking | P0 | Pending → Succeeded / Failed → Refunded workflow |
| Manual Donation Entry | P1 | Add offline/cash donations for record-keeping |
| Refund Processing | P1 | Partial and full refunds with reason tracking |
| Receipt Generation | P1 | Auto-generated PDF/email receipts for successful donations |

**Donation Fields:**
- Amount (RM)
- Currency (default MYR)
- Status: Pending, Succeeded, Failed, Refunded
- Payment method (Card, Bank Transfer, E-Wallet, Cash)
- Transaction ID (from payment gateway)
- Donor info (name, email, phone — may be anonymous)
- Campaign reference
- Message/note from donor
- Created at / Updated at
- Metadata (IP, user agent, source page)

### 3.3 Donor Management

| Feature | Priority | Description |
|---------|----------|-------------|
| Donor Directory | P0 | List of all donors with search and filter |
| Donor Profile | P0 | Aggregate view: total donated, campaigns supported, donation history |
| Anonymous Handling | P0 | Support anonymous donations while still tracking internally |
| Donor Notes | P2 | Internal notes on donors |
| Donor Export | P2 | CSV/Excel export for marketing or reporting |

**Donor Fields:**
- Name (may be "Anonymous")
- Email
- Phone
- Total donations count
- Total donated amount
- First donation date
- Last donation date
- Tags/segments

### 3.4 Recurring Donations

| Feature | Priority | Description |
|---------|----------|-------------|
| Recurring Plans | P1 | Create subscription-like recurring donation plans |
| Plan Management | P1 | View, pause, cancel active recurring plans |
| Failed Retry Logic | P2 | Automatic retry for failed recurring charges |
| Donor Self-Service | P2 | Donor portal to manage their own recurring plans |

**Recurring Plan Fields:**
- Amount
- Frequency (Monthly, Weekly, Yearly)
- Status: Active, Paused, Cancelled, Expired
- Next charge date
- Total charges made
- Total amount collected
- Donor reference
- Campaign reference (optional)

### 3.5 Payout Management

| Feature | Priority | Description |
|---------|----------|-------------|
| Payout History | P1 | List of all payouts to organization's bank account |
| Payout Details | P1 | Breakdown of donations included in each payout |
| Payout Schedule | P2 | Configure automatic payout frequency |
| Pending Balance | P1 | Real-time view of available balance vs. pending |

**Payout Fields:**
- Amount
- Status: Pending, Processing, Paid, Failed
- Payout method (bank account)
- Transaction reference
- Donations included (array of references)
- Created at / Paid at

### 3.6 Financial Reports

| Feature | Priority | Description |
|---------|----------|-------------|
| Dashboard Overview | P0 | Key metrics at a glance |
| Donation Reports | P1 | Filterable by date range, campaign, status |
| Export to CSV/Excel | P1 | All report views exportable |
| Summary Statistics | P1 | Totals, averages, trends |
| Audit Trail | P2 | Complete log of all financial actions |

### 3.7 Organization & Team

| Feature | Priority | Description |
|---------|----------|-------------|
| Organization Profile | P0 | Name, description, logo, contact info |
| Member Management | P1 | Invite team members, assign roles |
| Roles & Permissions | P1 | Admin, Manager, Viewer roles at minimum |
| Team Activity Log | P2 | Track who did what |
| Billing & Plan | P2 | Subscription plan management (if SaaS pricing model) |

**Role Permissions Matrix:**

| Action | Admin | Manager | Viewer |
|--------|-------|---------|--------|
| View campaigns | ✅ | ✅ | ✅ |
| Create/edit campaigns | ✅ | ✅ | ❌ |
| Delete campaigns | ✅ | ❌ | ❌ |
| View donations | ✅ | ✅ | ✅ |
| Process refunds | ✅ | ✅ | ❌ |
| View payouts | ✅ | ✅ | ✅ |
| Manage team members | ✅ | ❌ | ❌ |
| Manage API keys | ✅ | ❌ | ❌ |
| View reports | ✅ | ✅ | ✅ |
| Edit settings | ✅ | ❌ | ❌ |

### 3.8 Developer Tools

| Feature | Priority | Description |
|---------|----------|-------------|
| API Keys | P1 | Generate, revoke, label API keys |
| REST API | P1 | CRUD for campaigns, donations, donors |
| Webhooks | P1 | Subscribe to events (donation.created, payout.paid, etc.) |
| Webhook Logs | P2 | Delivery attempts, retries, failures |
| Embed Forms | P2 | Copy-paste HTML embed code for donation forms |
| API Documentation | P2 | Auto-generated or maintained docs |

**Webhook Events:**
- `donation.created`
- `donation.succeeded`
- `donation.failed`
- `donation.refunded`
- `campaign.created`
- `campaign.updated`
- `campaign.completed`
- `payout.paid`
- `donor.created`

### 3.9 Settings

| Feature | Priority | Description |
|---------|----------|-------------|
| General Settings | P0 | Organization name, logo, timezone, currency |
| Payment Settings | P0 | Payment gateway configuration, bank details |
| Notification Settings | P1 | Email templates, notification preferences |
| Receipt Settings | P1 | Receipt template customization |
| Security | P1 | Two-factor authentication, password policy |
| Integrations | P2 | Third-party service connections |

---

## 4. User Flows

### 4.1 First-Time Setup Flow

1. User signs up (email + password or SSO)
2. Create organization profile (name, type, logo)
3. Configure payment settings (connect payment gateway)
4. Land on Dashboard with onboarding checklist
5. Create first campaign

### 4.2 Campaign Creation Flow

1. Click "Create Campaign"
2. Fill campaign form (title, description, target, dates)
3. Upload cover image
4. Review and publish (or save as draft)
5. Campaign goes live → shareable URL generated
6. Dashboard updates with campaign stats

### 4.3 Donation Receiving Flow

1. Donor visits campaign page (hosted or embedded)
2. Donor fills donation form (amount, personal info, payment)
3. Payment processed via gateway
4. Webhook received by platform
5. Donation record created → status: Pending
6. Payment gateway confirms → status: Succeeded
7. Receipt emailed to donor
8. Dashboard updates in real-time
9. Campaign progress bar updates

### 4.4 Payout Flow

1. Balance accumulates from successful donations
2. Admin requests payout (or automatic scheduled payout)
3. Payout record created → status: Pending
4. Platform transfers to organization's bank
5. Bank confirms → status: Paid
6. Organization notified

### 4.5 Refund Flow

1. Admin opens donation detail
2. Clicks "Refund"
3. Modal: enter refund amount (partial or full) + reason
4. Refund processed via payment gateway
5. Donation status → Refunded
6. New refund record created
7. Donor notified

---

## 5. Data Model

### 5.1 Core Entities

```
Organization
├── id: UUID
├── name: string
├── slug: string
├── type: enum [mosque, ngo, charity, community, individual]
├── logo_url: string
├── description: text
├── contact_email: string
├── contact_phone: string
├── address: json
├── timezone: string
├── currency: string (default: MYR)
├── status: enum [active, suspended, deactivated]
├── plan_id: nullable
├── created_at / updated_at

├── Users[] (members)
├── Campaigns[]
├── Donations[]
├── Payouts[]
├── ApiKeys[]
├── Webhooks[]

User
├── id: UUID
├── name: string
├── email: string
├── email_verified_at: timestamp
├── password: hashed
├── avatar_url: string
├── role: enum [super_admin, admin, manager, viewer]
├── status: enum [active, invited, deactivated]
├── organization_id: UUID
├── last_login_at: timestamp
├── created_at / updated_at

Campaign
├── id: UUID
├── organization_id: UUID
├── title: string
├── slug: string
├── description: text
├── cover_image_url: string
├── target_amount: decimal
├── raised_amount: decimal (computed)
├── donor_count: integer (computed)
├── status: enum [draft, active, paused, completed]
├── visibility: enum [public, unlisted, private]
├── category: string
├── start_date: date (nullable)
├── end_date: date (nullable)
├── embed_code: text
├── meta: json
├── created_by: UUID
├── created_at / updated_at

├── Donations[]
├── RecurringPlans[]

Donation
├── id: UUID
├── organization_id: UUID
├── campaign_id: UUID (nullable)
├── donor_id: UUID (nullable, for guest/anonymous)
├── amount: decimal
├── currency: string
├── status: enum [pending, succeeded, failed, refunded]
├── payment_method: enum [card, bank_transfer, ewallet, cash]
├── payment_gateway: string
├── gateway_transaction_id: string
├── gateway_response: json
├── is_anonymous: boolean
├── donor_name: string (snapshot)
├── donor_email: string (snapshot)
├── donor_phone: string (snapshot)
├── donor_message: text
├── refunded_amount: decimal
├── refund_reason: string (nullable)
├── receipt_sent: boolean
├── receipt_url: string (nullable)
├── ip_address: string
├── user_agent: string
├── source_url: string
├── meta: json
├── created_at / updated_at

Donor
├── id: UUID
├── organization_id: UUID
├── name: string
├── email: string
├── phone: string (nullable)
├── is_anonymous_preference: boolean
├── total_donated: decimal (computed)
├── donation_count: integer (computed)
├── first_donation_at: timestamp
├── last_donation_at: timestamp
├── notes: text (nullable)
├── tags: json
├── created_at / updated_at

├── Donations[]
├── RecurringPlans[]

RecurringPlan
├── id: UUID
├── organization_id: UUID
├── donor_id: UUID
├── campaign_id: UUID (nullable)
├── amount: decimal
├── currency: string
├── frequency: enum [weekly, monthly, yearly]
├── status: enum [active, paused, cancelled, expired]
├── start_date: date
├── end_date: date (nullable)
├── next_charge_date: date
├── total_charges: integer
├── total_amount: decimal
├── payment_method_token: string
├── gateway_subscription_id: string
├── meta: json
├── created_at / updated_at

Payout
├── id: UUID
├── organization_id: UUID
├── amount: decimal
├── currency: string
├── status: enum [pending, processing, paid, failed]
├── bank_account_id: UUID
├── gateway_payout_id: string
├── donations: json (array of donation IDs)
├── failure_reason: string (nullable)
├── paid_at: timestamp (nullable)
├── created_at / updated_at

Transaction
├── id: UUID
├── organization_id: UUID
├── transactionable_type: string (morph)
├── transactionable_id: UUID
├── type: enum [donation, refund, fee, payout, adjustment]
├── amount: decimal
├── currency: string
├── balance_after: decimal
├── description: string
├── meta: json
├── created_at

Refund
├── id: UUID
├── organization_id: UUID
├── donation_id: UUID
├── amount: decimal
├── currency: string
├── reason: string
├── status: enum [pending, succeeded, failed]
├── gateway_refund_id: string
├── processed_by: UUID
├── created_at / updated_at

ApiKey
├── id: UUID
├── organization_id: UUID
├── name: string
├── key: string (hashed)
├── last_used_at: timestamp
├── scopes: json
├── revoked_at: timestamp (nullable)
├── created_at / updated_at

Webhook
├── id: UUID
├── organization_id: UUID
├── url: string
├── secret: string
├── events: json (array of event names)
├── status: enum [active, paused]
├── last_triggered_at: timestamp
├── created_at / updated_at

├── WebhookDeliveries[]

WebhookDelivery
├── id: UUID
├── webhook_id: UUID
├── event: string
├── payload: json
├── response_status: integer
├── response_body: text (nullable)
├── attempt_count: integer
├── delivered_at: timestamp (nullable)
├── created_at
```

### 5.2 Relationships

```
Organization
  └─has many→ Users
  └─has many→ Campaigns
  └─has many→ Donations
  └─has many→ Donors
  └─has many→ Payouts
  └─has many→ RecurringPlans
  └─has many→ ApiKeys
  └─has many→ Webhooks

User
  └─belongs to→ Organization
  └─has many→ Campaigns (created)

Campaign
  └─belongs to→ Organization
  └─has many→ Donations
  └─has many→ RecurringPlans

Donation
  └─belongs to→ Organization
  └─belongs to→ Campaign (nullable)
  └─belongs to→ Donor (nullable)

Donor
  └─belongs to→ Organization
  └─has many→ Donations
  └─has many→ RecurringPlans

RecurringPlan
  └─belongs to→ Organization
  └─belongs to→ Donor
  └─belongs to→ Campaign (nullable)

Payout
  └─belongs to→ Organization
```

---

## 6. Technical Architecture

### 6.1 Technology Stack

| Layer | Technology | Rationale |
|-------|-----------|-----------|
| Framework | Laravel 11+ | Mature PHP framework, excellent ORM, queue system, ecosystem |
| Frontend | Blade + Tailwind CSS + Alpine.js | Server-rendered, lightweight, no SPA complexity needed |
| Interactivity | Livewire 3 | Reactive components without writing APIs |
| Database | PostgreSQL 15+ | Robust, JSON support, financial calculations |
| Cache | Redis | Session cache, rate limiting, queue |
| Queue | Laravel Queue + Redis | Background jobs for webhooks, receipts, exports |
| Search | Laravel Scout + Meilisearch | Fast donor/campaign search |
| Payments | Stripe / Billplz / ToyyibPay | Multiple gateway support for Malaysian market |
| File Storage | S3-compatible | Campaign images, receipts, exports |
| Mail | Mailgun / Postmark / SMTP | Transactional emails |
| Queue Worker | Supervisor / systemd | Process management |

### 6.2 Payment Gateway Abstraction

Design a payment gateway abstraction to support multiple providers:

```
PaymentGatewayInterface
  └─processPayment($data)
  └─processRefund($data)
  └─createRecurringPlan($data)
  └─cancelRecurringPlan($id)
  └─getBalance()
  └─requestPayout($data)
  └─verifyWebhookSignature($request)

StripeGateway implements PaymentGatewayInterface
BillplzGateway implements PaymentGatewayInterface
ToyyibPayGateway implements PaymentGatewayInterface
CashGateway implements PaymentGatewayInterface (manual entry)
```

### 6.3 File Structure

```
app/
├── Domain/
│   ├── Campaign/
│   │   ├── Models/
│   │   ├── Actions/
│   │   ├── DataTransferObjects/
│   │   └── Enums/
│   ├── Donation/
│   ├── Donor/
│   ├── Payout/
│   ├── Organization/
│   └── Payment/
├── Http/
│   ├── Controllers/
│   ├── Livewire/
│   └── Requests/
├── Services/
│   ├── PaymentGatewayService.php
│   ├── ReceiptGenerationService.php
│   ├── ReportExportService.php
│   └── WebhookDispatchService.php
└── Providers/

resources/views/
├── components/           # Reusable UI components
├── livewire/            # Livewire component views
├── pages/               # Page-level compositions
│   ├── dashboard/
│   ├── campaigns/
│   ├── donations/
│   ├── donors/
│   ├── payouts/
│   ├── settings/
│   └── developer/
└── layouts/
    └── app.blade.php

routes/
├── web.php
├── api.php
└── webhooks.php
```

### 6.4 API Design Principles

- RESTful resource-based URLs
- JSON request/response
- API key authentication (Bearer token)
- Rate limiting: 100 requests/minute per key
- Pagination: cursor-based for high-volume endpoints
- Versioning: URL path (`/api/v1/`)

### 6.5 Background Jobs

| Job | Triggered By |
|-----|-------------|
| ProcessWebhookJob | Webhook received from payment gateway |
| SendReceiptEmail | Donation succeeded |
| SendDonationNotification | New donation received |
| GenerateReportExport | User requests export |
| RetryFailedWebhook | Scheduled (hourly) |
| ProcessScheduledPayout | Scheduled (daily) |
| ChargeRecurringDonation | Scheduled (daily, based on plans) |

---

## 7. Security & Compliance

### 7.1 Data Protection

- All passwords hashed with bcrypt
- API keys: store hash, show plaintext only once on creation
- Donor data encrypted at rest (sensitive fields)
- Database connections over TLS
- Regular automated backups with encryption

### 7.2 Payment Security

- PCI DSS compliance via tokenization (never store raw card data)
- Webhook signature verification
- Idempotency keys for payment operations
- Rate limiting on payment endpoints
- Fraud detection rules (unusual amounts, velocity checks)

### 7.3 Access Control

- Role-based access control (RBAC) at organization level
- Two-factor authentication for admins
- Session management with timeout
- Audit logging for sensitive operations (refunds, payouts, settings changes)

### 7.4 Compliance Requirements

| Requirement | Implementation |
|-------------|---------------|
| PDPA (Malaysia) | Data processing consent, data deletion, privacy policy |
| Tax receipts | Auto-generated receipts with org registration number |
| Audit trail | Immutable transaction logs |
| Data retention | Configurable retention with soft deletes |

---

## 8. Design System

See `AGENTS.md` for complete design system specification. Key principles:

- Dark mode SaaS aesthetic (charcoal/navy, not pure black)
- Single accent color (emerald green or teal)
- Reusable component library (30+ components)
- Mobile-first responsive design
- Accessibility compliant (WCAG 2.1 AA)

### 8.1 Color Tokens

| Token | Hex | Usage |
|-------|-----|-------|
| `--bg-app` | `#0f172a` | Main app background |
| `--bg-sidebar` | `#1e293b` | Sidebar background |
| `--bg-card` | `#1e293b` | Card background |
| `--border` | `#334155` | Default borders |
| `--text-primary` | `#f8fafc` | Headings, primary text |
| `--text-secondary` | `#94a3b8` | Body text |
| `--text-tertiary` | `#64748b` | Labels, hints |
| `--accent` | `#10b981` | Primary buttons, active states |
| `--accent-hover` | `#059669` | Accent hover state |
| `--danger` | `#ef4444` | Errors, destructive actions |
| `--warning` | `#f59e0b` | Warnings, pending states |
| `--success` | `#22c55e` | Success states |

### 8.2 Component Inventory

**Layout:**
- `AppShell` — Root layout wrapper
- `Sidebar` — Navigation sidebar with groups
- `Topbar` — Header with search, notifications, account
- `PageHeader` — Page title + description + actions

**Content:**
- `Card` — Content containers
- `StatCard` — Dashboard metrics
- `EmptyState` — Zero-data states
- `Tabs` — Content tabbing

**Data Display:**
- `DataTable` — Sortable, filterable tables
- `Badge` — Status indicators
- `Pagination` — Page navigation
- `FilterBar` — Search + filters

**Actions:**
- `PrimaryButton`, `SecondaryButton`, `GhostButton`, `DangerButton`
- `Modal` — Dialog overlays
- `Dropdown` — Context menus

**Forms:**
- `FormInput`, `FormSelect`, `FormTextarea`, `FormLabel`, `FormError`

**Feedback:**
- `Alert` — Inline messages
- `Toast` — Temporary notifications

---

## 9. Page Specifications

### 9.1 Dashboard

**URL:** `/dashboard`  
**Layout:** `AppShell`  
**Components:** `PageHeader`, `StatCard` (4x), `Card`, `DataTable`, `EmptyState`

**Stats:**
- Total Donations (RM)
- Total Donors
- Active Campaigns
- Monthly Revenue

**Sections:**
1. Stats grid (4 columns desktop, 2 tablet, 1 mobile)
2. Recent Donations table (last 10, with "View All")
3. Active Campaigns progress cards
4. Quick Actions card

### 9.2 Campaigns Index

**URL:** `/campaigns`  
**Components:** `PageHeader`, `FilterBar`, `DataTable`, `Badge`, `EmptyState`

**Table Columns:**
- Name (with cover image thumbnail)
- Status (Badge)
- Target / Raised (progress)
- Donor Count
- Date
- Actions (View, Edit, Pause/Resume)

**Filters:**
- Status
- Category
- Date range
- Search

**Empty State:** "No campaigns yet. Create your first campaign to start collecting donations online."

### 9.3 Campaign Show

**URL:** `/campaigns/{slug}`  
**Components:** `PageHeader`, `Tabs`, `StatCard` (3x), `Card`, `DataTable`

**Tabs:**
1. Overview — Stats + description + embed code
2. Donations — Full donation table
3. Donors — Aggregated donor list
4. Settings — Edit campaign form

### 9.4 Campaign Create/Edit

**URL:** `/campaigns/create`, `/campaigns/{slug}/edit`  
**Components:** `PageHeader`, `Card`, `FormInput`, `FormTextarea`, `FormSelect`, `PrimaryButton`

**Fields:**
- Title (required)
- Slug (auto-generated, editable)
- Description (textarea)
- Cover image (file upload)
- Target amount (number, RM)
- Category (select)
- Start date / End date (optional)
- Visibility (select)

### 9.5 Donations Index

**URL:** `/donations`  
**Components:** `PageHeader`, `FilterBar`, `DataTable`, `Badge`, `Pagination`

**Table Columns:**
- ID
- Date
- Donor (name or "Anonymous")
- Campaign
- Amount
- Status (Badge)
- Payment Method
- Actions

**Filters:**
- Status
- Campaign
- Date range
- Payment method
- Search (donor name/email)

### 9.6 Donation Show

**URL:** `/donations/{id}`  
**Components:** `PageHeader`, `Card`, `Badge`, `Modal` (for refund)

**Info Display:**
- Donation details (amount, status, date)
- Donor info (name, email, phone)
- Campaign reference
- Payment details (method, transaction ID)
- Donor message
- Timeline (created → confirmed)
- Refund section (if eligible)

### 9.7 Donors Index

**URL:** `/donors`  
**Components:** `PageHeader`, `DataTable`, `EmptyState`

**Table Columns:**
- Name
- Email
- Total Donated
- Donation Count
- First Donation
- Last Donation
- Actions

### 9.8 Donor Show

**URL:** `/donors/{id}`  
**Components:** `PageHeader`, `StatCard` (3x), `Card`, `DataTable`, `Tabs`

**Stats:**
- Total Donated
- Donation Count
- Recurring Plans

**Tabs:**
1. Donation History
2. Recurring Plans
3. Notes

### 9.9 Payouts Index

**URL:** `/payouts`  
**Components:** `PageHeader`, `StatCard`, `DataTable`, `Badge`

**Stats:**
- Available Balance
- Pending Payouts
- Total Paid Out

**Table Columns:**
- Payout ID
- Date
- Amount
- Status
- Bank Account (masked)
- Actions

### 9.10 Settings

**URL:** `/settings`  
**Components:** `PageHeader`, `Tabs`, `Card`, `FormInput`, `FormSelect`, `Button`

**Tabs:**
1. General — Org profile, timezone, currency
2. Payment — Gateway config, bank accounts
3. Notifications — Email preferences, templates
4. Receipts — Template, numbering
5. Security — Password, 2FA, sessions
6. Billing — Plan details, invoices
7. Members — Team management

### 9.11 API Keys

**URL:** `/developer/api-keys`  
**Components:** `PageHeader`, `Card`, `DataTable`, `EmptyState`, `Modal`

**Table Columns:**
- Name
- Key (masked)
- Created
- Last Used
- Actions (Revoke)

**Modal:** Create new API key (show key once)

### 9.12 Webhooks

**URL:** `/developer/webhooks`  
**Components:** `PageHeader`, `Card`, `DataTable`, `Badge`, `EmptyState`

**Table Columns:**
- URL
- Events
- Status
- Last Triggered
- Actions (Edit, Pause, Delete)

---

## 10. Non-Functional Requirements

### 10.1 Performance

| Metric | Target |
|--------|--------|
| Page load (dashboard) | < 1.5s |
| API response (p95) | < 200ms |
| Database query (p95) | < 50ms |
| Search results | < 100ms |
| Report generation (< 10k rows) | < 5s |

### 10.2 Scalability

- Support 1,000+ campaigns per organization
- Support 100,000+ donations per organization
- Support 50 concurrent admin users
- Queue system handles burst webhook traffic

### 10.3 Reliability

- 99.9% uptime target
- Automated database backups every 6 hours
- Failed webhook retries: 3 attempts with exponential backoff
- Graceful degradation if payment gateway is down

### 10.4 Browser Support

| Browser | Support |
|---------|---------|
| Chrome (last 2) | Full |
| Firefox (last 2) | Full |
| Safari (last 2) | Full |
| Edge (last 2) | Full |
| Mobile Safari | Full |
| Chrome Mobile | Full |

---

## 11. Success Metrics

### 11.1 Product Metrics

| Metric | Target |
|--------|--------|
| Campaign creation time | < 3 minutes |
| Donation processing success rate | > 98% |
| Dashboard daily active users | > 60% of total users |
| Support tickets per user/month | < 0.5 |

### 11.2 Business Metrics

| Metric | Target |
|--------|--------|
| Monthly processed volume | RM 1M+ within 12 months |
| Organization retention (6mo) | > 80% |
| Average donation value | RM 150+ |
| Recurring plan adoption | > 20% of donors |

---

## 12. Roadmap

### Phase 1 — Foundation (Months 1-2)
- [ ] Core UI component library
- [ ] Authentication & organization setup
- [ ] Campaign CRUD
- [ ] Basic donation receiving (single gateway)
- [ ] Dashboard with stats
- [ ] Donation & donor lists

### Phase 2 — Operations (Months 3-4)
- [ ] Multi-payment gateway support
- [ ] Recurring donations
- [ ] Payout management
- [ ] Team roles & permissions
- [ ] Receipt generation & emails
- [ ] Campaign embed forms

### Phase 3 — Growth (Months 5-6)
- [ ] REST API
- [ ] Webhooks
- [ ] API keys & developer portal
- [ ] Advanced reports & exports
- [ ] Refund processing
- [ ] Notifications system

### Phase 4 — Polish (Months 7-8)
- [ ] Mobile app optimization
- [ ] Performance tuning
- [ ] Advanced analytics
- [ ] Third-party integrations
- [ ] White-label options
- [ ] Multi-currency support

---

## 13. Appendices

### Appendix A: Glossary

| Term | Definition |
|------|-----------|
| Wakaf | Islamic endowment / charitable donation |
| Tahfiz | Islamic school for Quran memorization |
| Zakat | Obligatory Islamic almsgiving |
| RM | Malaysian Ringgit (currency) |
| Recurring Plan | Subscription-style repeating donation |
| Payout | Transfer of collected funds to organization's bank |

### Appendix B: External Dependencies

| Service | Purpose |
|---------|---------|
| Stripe | International card payments |
| Billplz | Malaysian bank transfer / FPX |
| ToyyibPay | Malaysian payment aggregator |
| AWS S3 | File storage |
| Mailgun | Transactional email |
| Meilisearch | Search indexing |

### Appendix C: Related Documents

- `AGENTS.md` — Design system & UI component specifications
- `API.md` (future) — API documentation
- `WEBHOOKS.md` (future) — Webhook event specifications
- `CHANGELOG.md` (future) — Release notes

---

*End of PRD*
