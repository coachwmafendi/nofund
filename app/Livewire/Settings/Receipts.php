<?php

namespace App\Livewire\Settings;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Receipts extends Component
{
    public string $headerText = '';
    public string $footerText = '';
    public bool $showLogo = true;

    public function mount(): void
    {
        $org = Auth::user()->organization;
        $settings = $org->meta ?? [];
        $this->headerText = $settings['receipt_header'] ?? '';
        $this->footerText = $settings['receipt_footer'] ?? '';
        $this->showLogo = $settings['receipt_show_logo'] ?? true;
    }

    public function save(): void
    {
        $org = Auth::user()->organization;
        $meta = $org->meta ?? [];
        $meta['receipt_header'] = $this->headerText;
        $meta['footer_footer'] = $this->footerText;
        $meta['receipt_show_logo'] = $this->showLogo;
        $org->update(['meta' => $meta]);

        session()->flash('success', 'Receipt settings saved successfully.');
    }

    public function render()
    {
        return view('livewire.settings.receipts')->layout('layouts.app');
    }
}