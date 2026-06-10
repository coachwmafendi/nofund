<?php

namespace App\Livewire\Developer;

use App\Enums\WebhookStatus;
use App\Models\Webhook;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;

class Webhooks extends Component
{
    public string $url = '';
    public array $selectedEvents = [];
    public bool $showModal = false;
    public ?Webhook $editingWebhook = null;

    public array $availableEvents = [
        'donation.succeeded',
        'donation.failed',
        'donation.refunded',
        'payout.paid',
        'campaign.completed',
    ];

    public function saveWebhook()
    {
        $this->validate([
            'url' => 'required|url',
            'selectedEvents' => 'required|array|min:1',
        ]);

        $data = [
            'url' => $this->url,
            'events' => $this->selectedEvents,
            'secret' => Str::random(32),
        ];

        if ($this->editingWebhook) {
            $this->editingWebhook->update($data);
            $this->dispatch('toast', message: 'Webhook updated successfully.', type: 'success');
        } else {
            Auth::user()->organization->webhooks()->create($data);
            $this->dispatch('toast', message: 'Webhook saved successfully.', type: 'success');
        }

        $this->showModal = false;
        $this->reset(['url', 'selectedEvents', 'editingWebhook']);
    }

    public function editWebhook(Webhook $webhook)
    {
        $this->editingWebhook = $webhook;
        $this->url = $webhook->url;
        $this->selectedEvents = $webhook->events;
        $this->showModal = true;
    }

    public function toggleStatus($id)
    {
        $webhook = Auth::user()->organization->webhooks()->findOrFail($id);
        $newStatus = $webhook->status === WebhookStatus::ACTIVE ? WebhookStatus::PAUSED : WebhookStatus::ACTIVE;
        $webhook->update(['status' => $newStatus]);
    }

    public function deleteWebhook($id)
    {
        Auth::user()->organization->webhooks()->findOrFail($id)->delete();
        $this->dispatch('toast', message: 'Webhook deleted successfully.', type: 'success');
    }

    public function openCreateModal()
    {
        $this->reset(['url', 'selectedEvents', 'editingWebhook']);
        $this->showModal = true;
    }

    public function render()
    {
        $webhooks = Auth::user()->organization->webhooks()->latest()->get();

        return view('livewire.developer.webhooks', [
            'webhooks' => $webhooks,
        ])->layout('layouts.app');
    }
}