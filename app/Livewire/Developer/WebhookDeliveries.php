<?php

namespace App\Livewire\Developer;

use App\Models\Webhook;
use Livewire\Component;
use Livewire\WithPagination;

class WebhookDeliveries extends Component
{
    use WithPagination;

    public ?Webhook $webhook = null;

    public function mount(Webhook $webhook)
    {
        $this->webhook = $webhook;
    }

    public function render()
    {
        $deliveries = $this->webhook->deliveries()->latest()->paginate(20);

        return view('livewire.developer.webhook-deliveries', [
            'deliveries' => $deliveries,
        ]);
    }
}