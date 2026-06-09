<div>
    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="mb-6 rounded-lg border border-emerald-500/20 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-400">
            {{ session('success') }}
        </div>
    @endif

    <x-page-header
        title="{{ $campaign->title }}"
        description="{{ Str::limit($campaign->description, 100) }}"
    >
        <x-slot:actions>
            <x-buttons.ghost onclick="history.back()">Back</x-buttons.ghost>
            <x-buttons.ghost wire:click="setTab('settings')">Edit</x-buttons.ghost>

            @if($campaign->status === \App\Enums\CampaignStatus::ACTIVE)
                <x-buttons.ghost wire:click="pause">Pause</x-buttons.ghost>
            @elseif(in_array($campaign->status, [\App\Enums\CampaignStatus::DRAFT, \App\Enums\CampaignStatus::PAUSED]))
                <x-buttons.primary wire:click="activate">Activate</x-buttons.primary>
            @endif

            @if($campaign->status !== \App\Enums\CampaignStatus::COMPLETED)
                <x-buttons.ghost wire:click="complete">Mark Complete</x-buttons.ghost>
            @endif

            <x-buttons.ghost wire:click="setTab('embed')">
                Embed
            </x-buttons.ghost>
        </x-slot:actions>
    </x-page-header>

    {{-- Status Badge --}}
    <div class="mb-6 flex items-center gap-2">
        <x-badge variant="{{ match($campaign->status->value) {
            'active' => 'success',
            'draft' => 'default',
            'paused' => 'warning',
            'completed' => 'info',
            default => 'default'
        } }}">
            {{ ucfirst($campaign->status->value) }}
        </x-badge>
        <span class="text-xs text-slate-500">
            {{ ucfirst($campaign->visibility->value) }} &middot; {{ $campaign->category }}
        </span>
    </div>

    {{-- Tabs --}}
    <x-tabs :tabs="$tabs" :activeKey="$activeTab">
        <div class="mt-4">

            {{-- Overview Tab --}}
            @if($activeTab === 'overview')
                {{-- Stats Row --}}
                <div class="grid grid-cols-2 gap-4 lg:grid-cols-4 mb-6">
                    <x-stat-card
                        title="Total Raised"
                        :value="'RM ' . number_format($stats['totalRaised'], 2)"
                        :description="'of RM ' . number_format($campaign->target_amount, 2) . ' target'"
                        icon="banknotes"
                    />
                    <x-stat-card
                        title="Unique Donors"
                        :value="number_format($stats['donorCount'])"
                        :description="$stats['donationCount'] . ' total donations'"
                        icon="users"
                    />
                    <x-stat-card
                        title="Donations"
                        :value="number_format($stats['donationCount'])"
                        description="Successful transactions"
                        icon="gift"
                    />
                    @if($stats['daysLeft'] !== null)
                        <x-stat-card
                            title="Days Left"
                            :value="(string) $stats['daysLeft']"
                            :description="$campaign->end_date?->format('M d, Y')"
                            icon="clock"
                        />
                    @else
                        <x-stat-card
                            title="Status"
                            :value="ucfirst($campaign->status->value)"
                            description="No end date set"
                            icon="flag"
                        />
                    @endif
                </div>

                {{-- Progress Bar --}}
                <x-card class="mb-6">
                    <div class="mb-2 flex items-center justify-between text-sm">
                        <span class="text-slate-400">Campaign Progress</span>
                        <span class="font-medium text-emerald-400">{{ $stats['progressPercent'] }}%</span>
                    </div>
                    <div class="w-full bg-slate-800 rounded-full h-2">
                        <div
                            class="bg-emerald-500 h-2 rounded-full transition-all duration-500"
                            style="width: {{ $stats['progressPercent'] }}%"
                        ></div>
                    </div>
                </x-card>

                {{-- Description --}}
                @if($campaign->description)
                    <x-card>
                        <x-slot:title>Description</x-slot:title>
                        <div class="prose prose-sm prose-invert max-w-none text-slate-300">
                            <p>{{ $campaign->description }}</p>
                        </div>
                    </x-card>
                @endif
            @endif

            {{-- Donations Tab --}}
            @if($activeTab === 'donations')
                <x-card>
                    <x-table.data-table>
                        <x-slot:header>
                            <x-table.header>Donor</x-table.header>
                            <x-table.header class="text-right">Amount</x-table.header>
                            <x-table.header>Status</x-table.header>
                            <x-table.header>Method</x-table.header>
                            <x-table.header>Date</x-table.header>
                        </x-slot:header>

                        <x-slot:rows>
                            @forelse($donations as $donation)
                                <x-table.row>
                                    <x-table.cell class="font-medium text-slate-200">
                                        {{ $donation->is_anonymous ? 'Anonymous' : ($donation->donor_name ?? $donation->donor_email) }}
                                    </x-table.cell>
                                    <x-table.cell class="text-right font-medium">
                                        RM {{ number_format($donation->amount, 2) }}
                                    </x-table.cell>
                                    <x-table.cell>
                                        <x-badge variant="{{ match($donation->status->value) {
                                            'succeeded' => 'success',
                                            'pending' => 'warning',
                                            'failed' => 'danger',
                                            'refunded' => 'default',
                                            default => 'default'
                                        } }}">
                                            {{ ucfirst($donation->status->value) }}
                                        </x-badge>
                                    </x-table.cell>
                                    <x-table.cell>{{ $donation->payment_method?->label() ?? 'N/A' }}</x-table.cell>
                                    <x-table.cell class="text-slate-500">{{ $donation->created_at->format('M d, Y') }}</x-table.cell>
                                </x-table.row>
                            @empty
                                <x-table.row>
                                    <x-table.empty colspan="5">
                                        <x-empty-state
                                            icon="banknotes"
                                            title="No donations yet"
                                            description="Donations will appear here once supporters contribute."
                                        />
                                    </x-table.empty>
                                </x-table.row>
                            @endforelse
                        </x-slot:rows>
                    </x-table.data-table>

                    @if($donations->hasPages())
                        <div class="mt-4">
                            {{ $donations->links('components.pagination') }}
                        </div>
                    @endif
                </x-card>
            @endif

            {{-- Donors Tab --}}
            @if($activeTab === 'donors')
                <x-card>
                    <x-table.data-table>
                        <x-slot:header>
                            <x-table.header>Donor</x-table.header>
                            <x-table.header class="text-center">Donations</x-table.header>
                            <x-table.header class="text-right">Total Donated</x-table.header>
                        </x-slot:header>

                        <x-slot:rows>
                            @forelse($donors as $donor)
                                <x-table.row>
                                    <x-table.cell class="font-medium text-slate-200">
                                        {{ $donor->donor_name ?? $donor->donor_email }}
                                    </x-table.cell>
                                    <x-table.cell class="text-center text-slate-400">
                                        {{ $donor->donation_count }}
                                    </x-table.cell>
                                    <x-table.cell class="text-right font-medium text-emerald-400">
                                        RM {{ number_format($donor->total_donated, 2) }}
                                    </x-table.cell>
                                </x-table.row>
                            @empty
                                <x-table.row>
                                    <x-table.empty colspan="3">
                                        <x-empty-state
                                            icon="users"
                                            title="No donors yet"
                                            description="Unique donors will appear here."
                                        />
                                    </x-table.empty>
                                </x-table.row>
                            @endforelse
                        </x-slot:rows>
                    </x-table.data-table>

                    @if($donors->hasPages())
                        <div class="mt-4">
                            {{ $donors->links('components.pagination') }}
                        </div>
                    @endif
                </x-card>
            @endif

            {{-- Settings Tab --}}
            @if($activeTab === 'settings')
                <x-card>
                    <form wire:submit="update" class="space-y-6">
                        {{-- Title --}}
                        <x-form.field>
                            <x-form.label for="title" required>Title</x-form.label>
                            <x-form.input id="title" type="text" wire:model="title" />
                            <x-form.error name="title" />
                        </x-form.field>

                        {{-- Slug --}}
                        <x-form.field>
                            <x-form.label for="slug" required>Slug</x-form.label>
                            <x-form.input id="slug" type="text" wire:model="slug" />
                            <x-form.error name="slug" />
                        </x-form.field>

                        {{-- Description --}}
                        <x-form.field>
                            <x-form.label for="description">Description</x-form.label>
                            <x-form.textarea id="description" wire:model="description" rows="4" />
                            <x-form.error name="description" />
                        </x-form.field>

                        {{-- Target Amount --}}
                        <x-form.field>
                            <x-form.label for="targetAmount" required>Target Amount (RM)</x-form.label>
                            <x-form.input id="targetAmount" type="number" wire:model="targetAmount" min="1" step="0.01" />
                            <x-form.error name="targetAmount" />
                        </x-form.field>

                        {{-- Category --}}
                        <x-form.field>
                            <x-form.label for="category" required>Category</x-form.label>
                            <x-form.select id="category" wire:model="category">
                                <option value="">Select a category</option>
                                <option value="Wakaf">Wakaf</option>
                                <option value="Zakat">Zakat</option>
                                <option value="Sedekah">Sedekah</option>
                                <option value="Dana Pendidikan">Dana Pendidikan</option>
                                <option value="Bantuan Kecemasan">Bantuan Kecemasan</option>
                                <option value="Pembangunan">Pembangunan</option>
                                <option value="General Donation">General Donation</option>
                            </x-form.select>
                            <x-form.error name="category" />
                        </x-form.field>

                        {{-- Dates Row --}}
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <x-form.field>
                                <x-form.label for="startDate">Start Date</x-form.label>
                                <x-form.input id="startDate" type="date" wire:model="startDate" />
                                <x-form.error name="startDate" />
                            </x-form.field>

                            <x-form.field>
                                <x-form.label for="endDate">End Date</x-form.label>
                                <x-form.input id="endDate" type="date" wire:model="endDate" />
                                <x-form.error name="endDate" />
                            </x-form.field>
                        </div>

                        {{-- Visibility --}}
                        <x-form.field>
                            <x-form.label for="visibility" required>Visibility</x-form.label>
                            <x-form.select id="visibility" wire:model="visibility">
                                <option value="public">Public</option>
                                <option value="unlisted">Unlisted</option>
                                <option value="private">Private</option>
                            </x-form.select>
                            <x-form.error name="visibility" />
                        </x-form.field>

                        {{-- Status Actions + Save --}}
                        <div class="flex items-center justify-between pt-4 border-t border-slate-800">
                            <div class="flex items-center gap-2">
                                @if(in_array($campaign->status, [\App\Enums\CampaignStatus::DRAFT, \App\Enums\CampaignStatus::PAUSED]))
                                    <x-buttons.primary wire:click="activate" type="button">Activate</x-buttons.primary>
                                @endif
                                @if($campaign->status === \App\Enums\CampaignStatus::ACTIVE)
                                    <x-buttons.ghost wire:click="pause" type="button">Pause</x-buttons.ghost>
                                @endif
                                @if($campaign->status !== \App\Enums\CampaignStatus::COMPLETED)
                                    <x-buttons.ghost wire:click="complete" type="button">Mark Complete</x-buttons.ghost>
                                @endif
                            </div>
                            <x-buttons.primary type="submit">Save Changes</x-buttons.primary>
                        </div>
                    </form>
                </x-card>
            @endif

            {{-- Embed Tab --}}
            @if($activeTab === 'embed')
                <x-card>
                    <x-slot:title>Embed Code</x-slot:title>
                    <x-slot:description>Embed this campaign's donation form on your website.</x-slot:description>

                    <x-form.textarea
                        id="embed-textarea"
                        wire:model="embedCode"
                        rows="6"
                        readonly
                        class="font-mono text-xs text-slate-400"
                    />

                    <div class="mt-4 flex items-center gap-3">
                        <x-buttons.primary type="button" onclick="navigator.clipboard.writeText(document.getElementById('embed-textarea').value)">
                            Copy Embed Code
                        </x-buttons.primary>
                        <span class="text-xs text-slate-500">Paste this HTML into your website.</span>
                    </div>
                </x-card>
            @endif

        </div>
    </x-tabs>
</div>