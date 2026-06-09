# AGENTS.md

## Project Context

This project is nofund, a modern fundraising and donation management platform.

The app is built for organizations, NGOs, mosques, tahfiz centers, charities, and campaign owners to manage fundraising campaigns, receive donations, track donors, and monitor financial performance.

The interface must feel modern, clean, premium, trustworthy, and simple to use.

Avoid default Laravel, Breeze, Jetstream, Nova, or Filament-style UI. The design should look like a modern SaaS dashboard, not a generic admin panel.

## Main Design Direction

Use a modern SaaS dashboard style inspired by polished products like Kimchi.dev, Stripe, Linear, Vercel, and Clerk.

The UI should have:

- clean dark mode interface
- charcoal or dark navy background, not pure black
- left sidebar navigation
- grouped menu sections
- large rounded cards
- subtle borders
- generous spacing
- beautiful empty states
- simple icon usage
- consistent typography
- one main accent color only
- professional and calm visual style
- responsive desktop and mobile layout

The app should look custom-built and premium.

## UI Principles

Follow these principles for every page:

- Simplicity first
  Keep the layout clean and easy to understand.
- Reusable components
  Do not repeat the same UI markup across pages.
- Consistent spacing
  Use the same padding, margin, gap, and layout rhythm throughout the app.
- Consistent styling
  Buttons, cards, forms, tables, badges, and empty states must look the same across all pages.
- Clear hierarchy
  Page titles, descriptions, actions, cards, tables, and empty states must be visually organized.
- Avoid clutter
  Do not overload a page with too many borders, shadows, colors, or competing elements.
- Premium feel
  Use subtle borders, soft contrast, rounded corners, and clean typography.

## Color Direction

Use a dark SaaS color system.

Recommended base colors:

- App background: dark charcoal or dark navy
- Sidebar background: slightly different dark tone
- Card background: slightly lighter than page background
- Border: subtle muted border
- Text primary: near-white
- Text secondary: muted gray
- Text tertiary: softer gray
- Accent: one primary brand color only

Avoid:

- pure black background
- too many bright colors
- random gradients
- inconsistent button colors
- default browser blue links
- heavy shadows

Suggested accent color for Ihsan:

- emerald green
- teal
- or warm amber

Use the accent color only for:

- primary buttons
- active sidebar item
- important icons
- selected tabs
- progress indicators
- key status highlights

## Typography

Use clean and readable typography.

Text hierarchy:

- Page title: large, bold, clear
- Section title: medium size and semibold
- Card title: semibold
- Body text: readable and calm
- Helper text: smaller and muted
- Labels: small, medium weight

Avoid using too many font sizes.

Use consistent line height and spacing.

## Layout System

Use an app shell layout.

Main structure:

<x-app-shell>
    <x-page-header
        title="Dashboard"
        description="Overview of your fundraising performance."
    />

    <x-card>
        Page content here
    </x-card>
</x-app-shell>

The layout should include:

- fixed or sticky left sidebar on desktop
- topbar or header area
- main content area
- account dropdown
- responsive mobile navigation

Desktop layout:

- sidebar on the left
- content on the right
- page max width where suitable
- generous content padding

Mobile layout:

- sidebar collapses into mobile drawer
- content uses full width
- cards stack vertically
- tables become scrollable or responsive

## Sidebar Rules

The sidebar should be grouped by feature area.

Suggested sidebar groups:

### Main

- Dashboard
- Campaigns
- Donations
- Donors
- Recurring Plans

### Finance

- Payouts
- Transactions
- Refunds
- Reports

### Organization

- Members
- Teams
- Settings

### Developer

- API Keys
- Webhooks
- Embed Forms

Sidebar requirements:

- active item must be clearly visible
- icon size must be consistent
- item height must be consistent
- group labels must be muted and small
- spacing between groups must be generous
- sidebar should not look crowded

## Reusable UI Component System

Build the interface using reusable UI components.

Do not repeat the same layout, card, button, empty state, table, or form markup across pages.

Create reusable components for:

- AppShell
- Sidebar
- SidebarGroup
- SidebarItem
- Topbar
- AccountDropdown
- PageHeader
- Card
- StatCard
- EmptyState
- PrimaryButton
- SecondaryButton
- GhostButton
- DangerButton
- Badge
- DataTable
- TableHeader
- TableRow
- FormInput
- FormSelect
- FormTextarea
- FormLabel
- FormError
- Modal
- Tabs
- Breadcrumbs
- Dropdown
- Alert
- Toast
- Pagination
- SearchInput
- FilterBar

Each component must be:

- reusable across multiple pages
- easy to customize using props
- visually consistent with the design system
- not tightly coupled to one specific page
- built with clean Tailwind CSS classes
- responsive for desktop and mobile
- accessible where possible

Avoid creating page-specific UI unless absolutely necessary.

If a UI pattern appears more than once, extract it into a reusable component.

## Laravel Blade Component Structure

Use Blade components for reusable visual UI.

Suggested folder structure:

resources/views/components/
├── app-shell.blade.php
├── sidebar.blade.php
├── sidebar-group.blade.php
├── sidebar-item.blade.php
├── topbar.blade.php
├── account-dropdown.blade.php
├── page-header.blade.php
├── card.blade.php
├── stat-card.blade.php
├── empty-state.blade.php
├── badge.blade.php
├── alert.blade.php
├── modal.blade.php
├── tabs.blade.php
├── dropdown.blade.php
├── pagination.blade.php
├── buttons/
│   ├── primary.blade.php
│   ├── secondary.blade.php
│   ├── ghost.blade.php
│   └── danger.blade.php
├── form/
│   ├── input.blade.php
│   ├── select.blade.php
│   ├── textarea.blade.php
│   ├── label.blade.php
│   ├── error.blade.php
│   └── field.blade.php
└── table/
    ├── data-table.blade.php
    ├── header.blade.php
    ├── row.blade.php
    ├── cell.blade.php
    └── empty.blade.php

Use components like this:

<x-app-shell>
    <x-page-header
        title="Campaigns"
        description="Manage all fundraising campaigns."
    >
        <x-slot:actions>
            <x-buttons.primary>
                Create Campaign
            </x-buttons.primary>
        </x-slot:actions>
    </x-page-header>

    <x-card>
        Content here
    </x-card>
</x-app-shell>

## Component Reuse Rules

Before creating a new UI section, check whether an existing component can be reused.

Do not create different button styles for every page.

Do not create different card styles for every page.

Do not create different empty state layouts for every page.

Do not create different form field styles for every page.

Do not create different table styles for every page.

All pages must use the same:

- button radius
- button padding
- card padding
- card border color
- text size scale
- sidebar style
- page header style
- form field style
- table style
- badge style
- modal style
- empty state style

Every time you build a new page, compose it from existing reusable components first.

Only create a new component when the UI pattern is reusable across the app.

## Priority Components for Ihsan

Build these components first:

- AppShell
- Sidebar
- SidebarGroup
- SidebarItem
- Topbar
- AccountDropdown
- PageHeader
- Card
- StatCard
- EmptyState
- Button
- Badge
- DataTable
- FormInput
- FormSelect
- FormTextarea
- Modal
- Tabs
- Pagination
- FilterBar

These components should become the foundation for all future pages.

## AppShell Component

The AppShell component should control the main application layout.

It should include:

- sidebar
- topbar
- main content wrapper
- responsive mobile menu
- account dropdown area

Example usage:

<x-app-shell>
    {{ $slot }}
</x-app-shell>

The AppShell should be used on all authenticated dashboard pages.

## PageHeader Component

Use PageHeader at the top of each page.

It should support:

- title
- description
- optional action buttons
- optional breadcrumbs

Example:

<x-page-header
    title="Donations"
    description="View and manage all donation transactions."
>
    <x-slot:actions>
        <x-buttons.secondary>
            Export
        </x-buttons.secondary>

        <x-buttons.primary>
            New Donation
        </x-buttons.primary>
    </x-slot:actions>
</x-page-header>

## Card Component

Use Card for content sections.

Card should support:

- title
- description
- optional header actions
- body slot
- optional footer

Example:

<x-card>
    <x-slot:title>
        Donation Summary
    </x-slot:title>

    <x-slot:description>
        Overview of donation performance this month.
    </x-slot:description>

    Card content here.
</x-card>

Card style:

- rounded corners
- subtle border
- dark background
- consistent padding
- no heavy shadow

## EmptyState Component

Use EmptyState whenever there is no data.

Do not show blank pages.

EmptyState should include:

- icon
- title
- description
- optional action button

Example:

<x-empty-state
    icon="chart-bar"
    title="No donations yet"
    description="Donation records will appear here once supporters start contributing."
>
    <x-slot:actions>
        <x-buttons.primary>
            Create Campaign
        </x-buttons.primary>
    </x-slot:actions>
</x-empty-state>

Good empty state examples:

- No campaigns yet
- No donations yet
- No donors yet
- No payout records yet
- No API keys yet
- No webhook events yet

Empty states must be helpful, not just decorative.

They should explain what will appear and what the user can do next.

## Button Components

Create button components instead of repeating button classes.

Button types:

- PrimaryButton
- SecondaryButton
- GhostButton
- DangerButton

Button rules:

- all buttons use the same border radius
- all buttons use consistent height
- all buttons use consistent horizontal padding
- icons should be aligned properly
- loading state should be supported if needed
- disabled state should be styled clearly

Example:

<x-buttons.primary>
    Create Campaign
</x-buttons.primary>

<x-buttons.secondary>
    Export CSV
</x-buttons.secondary>

<x-buttons.ghost>
    Cancel
</x-buttons.ghost>

<x-buttons.danger>
    Delete
</x-buttons.danger>

## Badge Component

Use Badge for statuses.

Suggested statuses:

- Active
- Draft
- Paused
- Completed
- Pending
- Paid
- Failed
- Refunded
- Processing
- Verified
- Unverified

Badge rules:

- subtle background
- readable text
- consistent padding
- rounded pill style
- do not use overly bright colors

Example:

<x-badge variant="success">
    Active
</x-badge>

<x-badge variant="warning">
    Pending
</x-badge>

<x-badge variant="danger">
    Failed
</x-badge>

## Form Components

All forms must use reusable form components.

Suggested components:

- FormField
- FormLabel
- FormInput
- FormSelect
- FormTextarea
- FormError
- Checkbox
- Radio
- Toggle

Example:

<x-form.field>
    <x-form.label for="name">
        Campaign Name
    </x-form.label>

    <x-form.input
        id="name"
        name="name"
        placeholder="Example: Wakaf Pembinaan Masjid"
    />

    <x-form.error name="name" />
</x-form.field>

Form rules:

- labels must be clear
- placeholders must be helpful
- error messages must be visible
- input height must be consistent
- border color must be subtle
- focus state must use the accent color
- avoid default browser styling

## DataTable Component

Use DataTable for lists and records.

Suggested usage:

- campaigns list
- donations list
- donors list
- transactions list
- payouts list
- members list
- webhook events
- API keys

DataTable should support:

- header
- rows
- empty state
- search
- filters
- pagination
- row actions
- status badges
- responsive behavior

Example:

<x-table.data-table>
    <x-slot:header>
        <x-table.header>Name</x-table.header>
        <x-table.header>Status</x-table.header>
        <x-table.header>Amount Raised</x-table.header>
        <x-table.header>Actions</x-table.header>
    </x-slot:header>

    <x-table.row>
        <x-table.cell>Wakaf Masjid</x-table.cell>
        <x-table.cell>
            <x-badge variant="success">Active</x-badge>
        </x-table.cell>
        <x-table.cell>RM12,500</x-table.cell>
        <x-table.cell>
            <x-buttons.ghost>View</x-buttons.ghost>
        </x-table.cell>
    </x-table.row>
</x-table.data-table>

Table rules:

- do not use heavy borders
- use subtle row dividers
- align numbers properly
- status should use badge component
- actions should be visually calm
- empty table must show EmptyState

## StatCard Component

Use StatCard for dashboard metrics.

Examples:

- Total Donations
- Total Donors
- Active Campaigns
- Monthly Revenue
- Successful Payments
- Failed Payments
- Pending Payouts

Example:

<x-stat-card
    title="Total Donations"
    value="RM24,500"
    description="+12.5% from last month"
    icon="banknotes"
/>

StatCard rules:

- value should be prominent
- title should be muted
- description should be small
- icon should be subtle
- do not overuse colors

## Modal Component

Use Modal for focused actions.

Examples:

- confirm delete campaign
- refund donation
- invite member
- create API key
- edit donor details

Modal rules:

- clear title
- short description
- primary and secondary actions
- danger action must be visually distinct
- avoid very large modals unless necessary

## Tabs Component

Use Tabs when separating related sections.

Examples:

Campaign details page:

- Overview
- Donations
- Donors
- Settings
- Embed
- Reports

Organization settings page:

- General
- Members
- Billing
- API Keys
- Webhooks

Tabs rules:

- active tab must be obvious
- tabs should not wrap awkwardly
- mobile should be scrollable horizontally if needed

## Account Dropdown

The account dropdown should include:

- user name
- user email
- organization section
- pricing or billing
- members
- settings
- logout

It should look polished and consistent with the dark UI.

Example items:

- Account Settings
- Organization
- Billing
- Members
- API Keys
- Log out

## Empty State Copywriting

Use helpful copywriting for empty states.

Bad:

No data.

Good:

No donations yet.
Donation records will appear here once supporters start contributing to your campaigns.

Bad:

Nothing here.

Good:

No campaigns yet.
Create your first campaign to start collecting donations online.

Use calm, helpful, and human language.

## Suggested Ihsan Pages

Build these pages using the reusable component system.

### Dashboard

Use:

- PageHeader
- StatCard
- Card
- DataTable
- EmptyState

Sections:

- total donation amount
- number of donors
- active campaigns
- recent donations
- campaign performance

### Campaigns

Use:

- PageHeader
- FilterBar
- DataTable
- Badge
- EmptyState

Actions:

- create campaign
- view campaign
- edit campaign
- pause campaign

### Campaign Details

Use:

- PageHeader
- Tabs
- StatCard
- Card
- DataTable

Tabs:

- Overview
- Donations
- Donors
- Embed
- Settings

### Donations

Use:

- PageHeader
- FilterBar
- DataTable
- Badge
- Pagination

Donation statuses:

- Succeeded
- Pending
- Failed
- Refunded

### Donors

Use:

- PageHeader
- DataTable
- EmptyState
- Badge

### Payouts

Use:

- PageHeader
- StatCard
- DataTable
- Badge

### Settings

Use:

- PageHeader
- Card
- FormInput
- FormSelect
- Button

### API Keys

Use:

- PageHeader
- Card
- DataTable
- EmptyState
- Modal

### Webhooks

Use:

- PageHeader
- Card
- DataTable
- Badge
- EmptyState

## Tailwind CSS Rules

Use Tailwind CSS cleanly.

Avoid:

- very long repeated class lists in every page
- inconsistent spacing
- arbitrary values everywhere
- random colors
- inline styles unless necessary

Prefer:

- reusable components
- consistent spacing scale
- consistent radius
- consistent border color
- consistent text colors

If the same Tailwind class group is repeated many times, move it into a component.

## Responsive Design Rules

All pages must be responsive.

Desktop:

- sidebar visible
- content area spacious
- tables can show more columns

Tablet:

- reduce padding
- stack cards if needed

Mobile:

- sidebar becomes drawer
- topbar includes menu button
- content uses full width
- cards stack vertically
- tables become scrollable or card-based
- action buttons stack if needed

Do not design desktop-only pages.

## Accessibility Rules

Follow basic accessibility rules:

- buttons must be real button elements
- links must be real anchor elements
- form inputs must have labels
- color must not be the only status indicator
- focus states must be visible
- modals must be keyboard-friendly
- icons should have accessible labels when needed

## Icon Rules

Use one consistent icon library only.

Recommended:

- Heroicons
- Lucide
- Phosphor Icons

Do not mix multiple icon styles.

Icon rules:

- same stroke width
- same default size
- muted color by default
- accent color only when important
- active sidebar icon should match active text color

## Animation Rules

Use subtle animation only.

Allowed:

- hover transitions
- dropdown fade/scale
- modal entrance
- sidebar drawer transition
- button loading state

Avoid:

- excessive motion
- bouncing effects
- distracting animations
- slow transitions

## Code Quality Rules

Write clean, maintainable Laravel code.

General rules:

- keep Blade files readable
- avoid deeply nested markup
- extract repeated UI into components
- keep page files focused on composition
- use meaningful component names
- use consistent naming
- avoid unnecessary JavaScript
- use Livewire only where interactivity is needed

## Livewire Rules

Use Livewire for interactive features such as:

- search
- filters
- pagination
- modals
- form submission
- tabs if needed
- real-time validation
- donation management actions

Livewire components should not contain messy UI markup if the markup can be extracted into Blade components.

Keep Livewire responsible for state and behavior.

Keep Blade components responsible for visual presentation.

## Page Composition Rule

A page should look like a clean composition of reusable components.

Example:

<x-app-shell>
    <x-page-header
        title="Donations"
        description="Monitor all successful, pending, failed, and refunded donations."
    >
        <x-slot:actions>
            <x-buttons.secondary>
                Export
            </x-buttons.secondary>
        </x-slot:actions>
    </x-page-header>

    <div class="grid gap-4 md:grid-cols-3">
        <x-stat-card
            title="Total Donations"
            value="RM32,100"
            description="All-time successful donations"
            icon="banknotes"
        />

        <x-stat-card
            title="Donors"
            value="1,284"
            description="Total unique donors"
            icon="users"
        />

        <x-stat-card
            title="Success Rate"
            value="98.2%"
            description="Successful payment rate"
            icon="check-circle"
        />
    </div>

    <x-card>
        <livewire:donations-table />
    </x-card>
</x-app-shell>

## Anti-Patterns

Do not do these:

- do not hardcode repeated UI on every page
- do not create one-off buttons everywhere
- do not use different card styles per page
- do not use random colors
- do not create inconsistent spacing
- do not make every page look different
- do not copy default Laravel starter kit design
- do not create Filament-like admin UI unless specifically requested
- do not overuse gradients
- do not use pure black background
- do not use too many shadows
- do not build desktop-only layouts

## Final UI Goal

The final UI should feel like:

- modern SaaS
- premium dashboard
- clean and calm
- easy to navigate
- trustworthy for donation and fundraising
- custom-built
- not generic admin panel
- consistent across every page
- reusable and maintainable

Every UI decision should support trust, clarity, and ease of use.

For a fundraising app, users must feel confident managing campaigns, donations, donors, payouts, and organization settings.

Build the app as a polished product, not just a functional admin panel.
