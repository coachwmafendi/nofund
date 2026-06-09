<?php

namespace App\Livewire\Campaigns;

use App\Enums\CampaignStatus;
use App\Enums\CampaignVisibility;
use App\Models\Campaign;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class Show extends Component
{
    use WithPagination;

    public Campaign $campaign;

    public string $activeTab = 'overview';

    // Editable fields
    public string $title = '';
    public string $slug = '';
    public ?string $description = '';
    public float $targetAmount = 0;
    public string $category = '';
    public ?string $startDate = '';
    public ?string $endDate = '';
    public string $visibility = 'public';

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:campaigns,slug,' . $this->campaign->id,
            'description' => 'nullable|string',
            'targetAmount' => 'required|numeric|min:1',
            'category' => 'required|string|max:100',
            'startDate' => 'nullable|date',
            'endDate' => 'nullable|date|after_or_equal:startDate',
            'visibility' => 'required|in:public,unlisted,private',
        ];
    }

    public function mount(Campaign $campaign): void
    {
        $this->campaign = $campaign;
        $this->syncFields();
    }

    public function syncFields(): void
    {
        $this->title = $this->campaign->title;
        $this->slug = $this->campaign->slug;
        $this->description = $this->campaign->description;
        $this->targetAmount = (float) $this->campaign->target_amount;
        $this->category = $this->campaign->category;
        $this->startDate = $this->campaign->start_date?->format('Y-m-d') ?? '';
        $this->endDate = $this->campaign->end_date?->format('Y-m-d') ?? '';
        $this->visibility = $this->campaign->visibility->value;
    }

    public function updatedTitle(string $value): void
    {
        if (empty($this->slug) || $this->slug === Str::slug($this->campaign->title)) {
            $this->slug = Str::slug($value);
        }
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function update(): void
    {
        $this->validate();

        if (empty($this->slug)) {
            $this->slug = Str::slug($this->title);
        }

        $this->campaign->update([
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'target_amount' => $this->targetAmount,
            'category' => $this->category,
            'start_date' => $this->startDate ?: null,
            'end_date' => $this->endDate ?: null,
            'visibility' => $this->visibility,
        ]);

        $this->syncFields();
        $this->dispatch('toast', message: 'Campaign updated successfully.', type: 'success');
    }

    public function pause(): void
    {
        $this->campaign->update(['status' => CampaignStatus::PAUSED]);
        $this->campaign->refresh();
        $this->dispatch('toast', message: 'Campaign paused.', type: 'success');
    }

    public function activate(): void
    {
        $this->campaign->update(['status' => CampaignStatus::ACTIVE]);
        $this->campaign->refresh();
        $this->dispatch('toast', message: 'Campaign activated.', type: 'success');
    }

    public function complete(): void
    {
        $this->campaign->update(['status' => CampaignStatus::COMPLETED]);
        $this->campaign->refresh();
        $this->dispatch('toast', message: 'Campaign marked as completed.', type: 'success');
    }

    public function getStatsProperty(): array
    {
        $donations = $this->campaign->donations()->where('status', 'succeeded')->get();

        return [
            'totalRaised' => $donations->sum('amount'),
            'donorCount' => $donations->where('is_anonymous', false)->distinct('donor_email')->count(),
            'donationCount' => $donations->count(),
            'daysLeft' => $this->campaign->end_date
                ? max(0, (int) Carbon::now()->diffInDays($this->campaign->end_date, false))
                : null,
            'progressPercent' => $this->campaign->target_amount > 0
                ? min(100, round(($this->campaign->raised_amount / $this->campaign->target_amount) * 100, 1))
                : 0,
        ];
    }

    public function getDonationsProperty()
    {
        return $this->campaign->donations()
            ->with('donor')
            ->latest()
            ->paginate(20, ['*'], 'donations_page');
    }

    public function getDonorsProperty()
    {
        return $this->campaign->donations()
            ->where('status', 'succeeded')
            ->where('is_anonymous', false)
            ->selectRaw('donor_email, donor_name, COUNT(*) as donation_count, SUM(amount) as total_donated')
            ->groupBy('donor_email', 'donor_name')
            ->orderByDesc('total_donated')
            ->paginate(20, ['*'], 'donors_page');
    }

    public function getEmbedCodeProperty(): string
    {
        $url = config('app.url') . '/campaign/' . $this->campaign->public_id;
        return <<<HTML
<iframe
    src="{$url}/embed"
    width="100%"
    height="600"
    frameborder="0"
    style="border-radius: 12px; max-width: 600px;"
></iframe>
HTML;
    }

    public function render()
    {
        $tabs = [
            ['key' => 'overview', 'label' => 'Overview'],
            ['key' => 'donations', 'label' => 'Donations'],
            ['key' => 'donors', 'label' => 'Donors'],
            ['key' => 'settings', 'label' => 'Settings'],
            ['key' => 'embed', 'label' => 'Embed'],
        ];

        return view('livewire.campaigns.show', [
            'tabs' => $tabs,
            'stats' => $this->stats,
            'donations' => $this->donations,
            'donors' => $this->donors,
            'embedCode' => $this->embedCode,
        ])->layout('layouts.app');
    }
}