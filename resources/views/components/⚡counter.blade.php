<?php

use Livewire\Component;

new class extends Component
{
    public int $count = 0;

    public function increment(): void
    {
        $this->count++;
    }

    public function decrement(): void
    {
        $this->count--;
    }
};
?>

<div class="space-y-4">
    <flux:separator text="Counter Demo" />

    <div class="flex items-center justify-center gap-4">
        <flux:button variant="primary" wire:click="decrement" icon="minus" />

        <flux:badge size="lg" color="emerald">
            {{ $count }}
        </flux:badge>

        <flux:button variant="primary" wire:click="increment" icon="plus" />
    </div>

    <flux:text class="text-center">
        Klik butang untuk tambah atau tolak nilai.
    </flux:text>
</div>
