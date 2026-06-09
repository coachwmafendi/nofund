# UI Components Spec — nofund Dashboard

## Context
Build all foundational Blade UI components for the nofund dark SaaS dashboard. Pure Tailwind CSS v4 (no Flux component styling). Uses Livewire + Blade.

## Design Tokens (Tailwind)

```
Backgrounds:
- page bg:        bg-slate-950        (dark navy/charcoal, not pure black)
- sidebar bg:     bg-slate-900
- card bg:        bg-slate-900/50     or bg-slate-800/40
- hover bg:       bg-slate-800
- active bg:      bg-slate-800/60

Borders:
- subtle:         border-slate-800
- focus/accent:   border-emerald-500/50

Text:
- primary:        text-slate-50
- secondary:      text-slate-400
- tertiary:       text-slate-500
- accent:         text-emerald-400

Accent (primary action color):
- emerald-500 / emerald-400
- Use for: primary buttons, active sidebar items, selected tabs, important icons, focus rings

Hover transitions:
- transition-colors duration-150
```

## Icon Strategy
Use Heroicons (24px, stroke-width 1.5) via Laravel Blade Icons package:
- `<x-heroicon-o-home>`  (outline)
- `<x-heroicon-s-check>` (solid) for small inline icons

Install `blade-ui-kit/blade-heroicons`.

## Component Inventory

### Layout (Chunk 1)
| Component | File | Props |
|-----------|------|-------|
| AppShell | `components/app-shell.blade.php` | slot |
| Sidebar | `components/sidebar.blade.php` | — |
| SidebarGroup | `components/sidebar-group.blade.php` | label |
| SidebarItem | `components/sidebar-item.blade.php` | href, icon, label, active=false |
| Topbar | `components/topbar.blade.php` | pageTitle? |
| AccountDropdown | `components/account-dropdown.blade.php` | user |

### Content / Display (Chunk 2)
| Component | File | Props |
|-----------|------|-------|
| PageHeader | `components/page-header.blade.php` | title, description |
| Card | `components/card.blade.php` | slot (title, description, actions, footer) |
| StatCard | `components/stat-card.blade.php` | title, value, description, icon |
| EmptyState | `components/empty-state.blade.php` | icon, title, description |
| Badge | `components/badge.blade.php` | variant (success, warning, danger, info, default) |

### Buttons (Chunk 2)
| Component | File | Props |
|-----------|------|-------|
| PrimaryButton | `components/buttons/primary.blade.php` | type, wire:click, loading |
| SecondaryButton | `components/buttons/secondary.blade.php` | — |
| GhostButton | `components/buttons/ghost.blade.php` | — |
| DangerButton | `components/buttons/danger.blade.php` | — |

### Dialog / Navigation (Chunk 3)
| Component | File | Props |
|-----------|------|-------|
| Modal | `components/modal.blade.php` | show, title |
| Tabs | `components/tabs.blade.php` | tabs[] (label, key, activeKey) |
| Pagination | `components/pagination.blade.php` | links |

### Form (Chunk 3)
| Component | File | Props |
|-----------|------|-------|
| FormInput | `components/form/input.blade.php` | id, name, type, placeholder, value, wire:model |
| FormSelect | `components/form/select.blade.php` | id, name, options, wire:model |
| FormTextarea | `components/form/textarea.blade.php` | id, name, rows, placeholder |
| FormLabel | `components/form/label.blade.php` | for, required |
| FormError | `components/form/error.blade.php` | name |
| FormField | `components/form/field.blade.php` | slot |

### Table (Chunk 4)
| Component | File | Props |
|-----------|------|-------|
| DataTable | `components/table/data-table.blade.php` | slot (header, rows, empty) |
| TableHeader | `components/table/header.blade.php` | slot |
| TableRow | `components/table/row.blade.php` | slot |
| TableCell | `components/table/cell.blade.php` | slot |
| TableEmpty | `components/table/empty.blade.php` | — |

## Styling Detail

### AppShell
```blade
<div class="min-h-screen bg-slate-950 text-slate-50 flex">
    <!-- Sidebar -->
    <x-sidebar />

    <!-- Main area -->
    <div class="flex-1 flex flex-col min-w-0">
        <x-topbar />
        <main class="flex-1 p-6 md:p-8 overflow-y-auto">
            {{ $slot }}
        </main>
    </div>
</div>
```

### Sidebar
- width: w-64 fixed lg:static
- Mobile: hidden by default, toggle with drawer overlay
- Groups with muted labels: text-xs font-medium text-slate-500 uppercase tracking-wider px-3 py-2
- Items: flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-slate-400 hover:text-slate-50 hover:bg-slate-800 transition-colors
- Active item: bg-slate-800/60 text-emerald-400 border-r-2 border-emerald-500

### PageHeader
```blade
<div class="mb-8">
    <h1 class="text-2xl font-semibold text-slate-50">{{ $title }}</h1>
    @if($description)
        <p class="mt-1 text-sm text-slate-400">{{ $description }}</p>
    @endif
    @if(isset($actions))
        <div class="mt-4 flex items-center gap-3">{{ $actions }}</div>
    @endif
</div>
```

### Card
```blade
<div class="rounded-xl border border-slate-800 bg-slate-900/50">
    @if(isset($title) || isset($actions))
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-800">
            <div>
                @if(isset($title))
                    <h3 class="font-semibold text-slate-50">{{ $title }}</h3>
                @endif
                @if(isset($description))
                    <p class="text-sm text-slate-400 mt-0.5">{{ $description }}</p>
                @endif
            </div>
            @if(isset($actions))
                <div class="flex items-center gap-2">{{ $actions }}</div>
            @endif
        </div>
    @endif
    <div class="p-5">{{ $slot }}</div>
    @if(isset($footer))
        <div class="px-5 py-3 border-t border-slate-800">{{ $footer }}</div>
    @endif
</div>
```

### StatCard
```blade
<div class="rounded-xl border border-slate-800 bg-slate-900/50 p-5">
    <div class="flex items-center justify-between">
        <h3 class="text-sm font-medium text-slate-400">{{ $title }}</h3>
        @if(isset($icon))
            <x-dynamic-component :component="'heroicon-o-' . $icon" class="w-5 h-5 text-slate-500" />
        @endif
    </div>
    <p class="mt-2 text-2xl font-semibold text-slate-50">{{ $value }}</p>
    @if(isset($description))
        <p class="mt-1 text-xs text-slate-500">{{ $description }}</p>
    @endif
</div>
```

### Buttons
All share: `inline-flex items-center justify-center gap-2 rounded-lg px-4 py-2 text-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-500/50`

- Primary: `bg-emerald-600 text-white hover:bg-emerald-500`
- Secondary: `bg-slate-800 text-slate-200 border border-slate-700 hover:bg-slate-700`
- Ghost: `text-slate-400 hover:text-slate-50 hover:bg-slate-800`
- Danger: `bg-red-600/90 text-white hover:bg-red-500`

### Badge
```blade
<span @class([
    'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium',
    'bg-emerald-500/10 text-emerald-400' => $variant === 'success',
    'bg-amber-500/10 text-amber-400' => $variant === 'warning',
    'bg-red-500/10 text-red-400' => $variant === 'danger',
    'bg-sky-500/10 text-sky-400' => $variant === 'info',
    'bg-slate-700/50 text-slate-400' => $variant === 'default',
])>
    {{ $slot }}
</span>
```

### EmptyState
```blade
<div class="flex flex-col items-center justify-center py-12 text-center">
    <x-dynamic-component :component="'heroicon-o-' . $icon" class="w-12 h-12 text-slate-600 mb-4" />
    <h3 class="text-sm font-semibold text-slate-200">{{ $title }}</h3>
    <p class="mt-1 text-sm text-slate-500 max-w-xs">{{ $description }}</p>
    @if(isset($actions))
        <div class="mt-6">{{ $actions }}</div>
    @endif
</div>
```

### Modal
Use Livewire + Alpine for show/hide. Simple overlay with centered card.
```blade
<div x-data="{ open: {{ $show ? 'true' : 'false' }} }" @keydown.escape.window="open = false">
    <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
        <div x-show="open" x-transition @click.away="open = false" class="w-full max-w-md rounded-xl border border-slate-800 bg-slate-900 p-6 shadow-xl">
            @if(isset($title))
                <h3 class="text-lg font-semibold text-slate-50">{{ $title }}</h3>
            @endif
            <div class="mt-4">{{ $slot }}</div>
        </div>
    </div>
</div>
```

### Tabs
```blade
<div>
    <nav class="flex items-center gap-1 border-b border-slate-800 pb-px">
        @foreach($tabs as $tab)
            <button
                @class([
                    'px-3 py-2 text-sm font-medium border-b-2 transition-colors',
                    'border-emerald-500 text-emerald-400' => $activeKey === $tab['key'],
                    'border-transparent text-slate-400 hover:text-slate-200' => $activeKey !== $tab['key'],
                ])
            >
                {{ $tab['label'] }}
            </button>
        @endforeach
    </nav>
    <div class="mt-4">{{ $slot }}</div>
</div>
```

### FormInput
```blade
<input
    {{ $attributes->merge([
        'class' => 'block w-full rounded-lg border border-slate-700 bg-slate-900/50 px-3 py-2 text-sm text-slate-50 placeholder:text-slate-500 focus:border-emerald-500/50 focus:ring-1 focus:ring-emerald-500/50 focus:outline-none transition-colors'
    ]) }}
/>
```

### FormSelect
```blade
<select
    {{ $attributes->merge([
        'class' => 'block w-full rounded-lg border border-slate-700 bg-slate-900/50 px-3 py-2 text-sm text-slate-50 focus:border-emerald-500/50 focus:ring-1 focus:ring-emerald-500/50 focus:outline-none transition-colors'
    ]) }}
>
    {{ $slot }}
</select>
```

### Table components
- DataTable wrapper: `w-full`, no outer border
- Header row: `border-b border-slate-800 text-left text-xs font-medium text-slate-500 uppercase tracking-wider`
- Header cell: `px-4 py-3`
- Row: `border-b border-slate-800/50 last:border-0 hover:bg-slate-800/30 transition-colors`
- Cell: `px-4 py-3 text-sm text-slate-300`

## Dependencies to Verify
- `npm install` — Tailwind CSS v4 already set up via Vite
- `composer require blade-ui-kit/blade-heroicons`
- Livewire already installed

## Acceptance Criteria
- [ ] All components render without errors
- [ ] AppShell shows sidebar + topbar + main content
- [ ] Sidebar shows grouped nav items with active state
- [ ] Topbar shows org name + account dropdown
- [ ] PageHeader shows title, description, actions slot
- [ ] Card shows rounded card with border, title, body, optional footer
- [ ] StatCard shows metric with icon
- [ ] EmptyState shows icon + title + description + optional actions
- [ ] Badge shows all 5 variants
- [ ] All 4 button variants render correctly
- [ ] Modal overlays with backdrop blur
- [ ] Tabs render with active underline
- [ ] Form components render with focus ring in emerald
- [ ] Table shows header + rows + empty state
- [ ] All components use the exact Tailwind token colors above
- [ ] `php artisan view:cache` succeeds (verifies no Blade syntax errors)
