<?php

namespace App\Livewire\Campaigns;

use App\Enums\CampaignStatus;
use App\Enums\CampaignVisibility;
use App\Models\Campaign;
use Illuminate\Support\Str;
use Livewire\Component;

class Create extends Component
{
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
            'slug' => 'required|string|max:255|unique:campaigns,slug',
            'description' => 'nullable|string',
            'targetAmount' => 'required|numeric|min:1',
            'category' => 'required|string|max:100',
            'startDate' => 'nullable|date',
            'endDate' => 'nullable|date|after_or_equal:startDate',
            'visibility' => 'required|in:public,unlisted,private',
        ];
    }

    public function updatedTitle(string $value): void
    {
        if (empty($this->slug) || $this->slug === Str::slug($this->title)) {
            $this->slug = Str::slug($value);
        }
    }

    public function create(): void
    {
        $this->validate();

        if (empty($this->slug)) {
            $this->slug = Str::slug($this->title);
        }

        $campaign = Campaign::create([
            'organization_id' => auth()->user()->organization_id,
            'created_by' => auth()->id(),
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'target_amount' => $this->targetAmount,
            'category' => $this->category,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
            'visibility' => $this->visibility,
            'status' => CampaignStatus::DRAFT,
        ]);

        $campaign->update(['embed_code' => $campaign->generateEmbedCode()]);

        $this->dispatch('toast', message: 'Campaign created successfully.', type: 'success');

        $this->redirect(route('campaigns.show', $campaign->public_id));
    }

    public function render()
    {
        return view('livewire.campaigns.create')->layout('layouts.app');
    }
}