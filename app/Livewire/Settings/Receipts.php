<?php

namespace App\Livewire\Settings;

use Livewire\Component;

class Receipts extends Component
{
    public string $headerText = '';
    public string $footerText = '';
    public bool $showLogo = true;

    public function save(): void
    {
        // Stub: No persistence yet
        session()->flash('success', 'Receipt settings saved successfully.');
    }

    public function render()
    {
        return view('livewire.settings.receipts');
    }
}