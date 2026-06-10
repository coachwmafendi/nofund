<?php

namespace App\Livewire\Settings;

use App\Enums\OrganizationType;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class General extends Component
{
    public string $name = '';
    public string $slug = '';
    public OrganizationType $type;
    public string $contactEmail = '';
    public string $contactPhone = '';
    public string $description = '';
    public string $timezone = '';
    public string $currency = '';

    public array $timezones = [];
    public array $currencies = [];

    public function mount(): void
    {
        $org = Auth::user()->organization;

        $this->name = $org->name ?? '';
        $this->slug = $org->slug ?? '';
        $this->type = $org->type ?? OrganizationType::CHARITY;
        $this->contactEmail = $org->contact_email ?? '';
        $this->contactPhone = $org->contact_phone ?? '';
        $this->description = $org->description ?? '';
        $this->timezone = $org->timezone ?? 'Asia/Kuala_Lumpur';
        $this->currency = $org->currency ?? 'MYR';

        $this->timezones = [
            'Asia/Kuala_Lumpur' => 'Kuala Lumpur (MYT)',
            'Asia/Jakarta' => 'Jakarta (WIB)',
            'Asia/Singapore' => 'Singapore (SGT)',
            'Asia/Bangkok' => 'Bangkok (ICT)',
            'Asia/Manila' => 'Manila (PHT)',
            'Asia/Hong_Kong' => 'Hong Kong (HKT)',
            'Asia/Shanghai' => 'Shanghai (CST)',
            'Asia/Tokyo' => 'Tokyo (JST)',
            'Asia/Seoul' => 'Seoul (KST)',
            'Asia/Dubai' => 'Dubai (GST)',
            'Europe/London' => 'London (GMT)',
            'Europe/Paris' => 'Paris (CET)',
            'Europe/Berlin' => 'Berlin (CET)',
            'America/New_York' => 'New York (EST)',
            'America/Los_Angeles' => 'Los Angeles (PST)',
            'America/Chicago' => 'Chicago (CST)',
            'Australia/Sydney' => 'Sydney (AEST)',
            'Pacific/Auckland' => 'Auckland (NZST)',
        ];

        $this->currencies = [
            'MYR' => 'Malaysian Ringgit (MYR)',
            'USD' => 'US Dollar (USD)',
            'SGD' => 'Singapore Dollar (SGD)',
            'IDR' => 'Indonesian Rupiah (IDR)',
        ];
    }

    public function save(): void
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'contactEmail' => 'nullable|email|max:255',
            'contactPhone' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:1000',
            'timezone' => 'nullable|string',
            'currency' => 'required|in:MYR,USD,SGD,IDR',
        ]);

        $org = Auth::user()->organization;
        $org->update([
            'name' => $this->name,
            'slug' => $this->slug,
            'type' => $this->type,
            'contact_email' => $this->contactEmail,
            'contact_phone' => $this->contactPhone,
            'description' => $this->description,
            'timezone' => $this->timezone,
            'currency' => $this->currency,
        ]);

        $this->dispatch('toast', message: 'Settings saved successfully.', type: 'success');
    }

    public function render()
    {
        return view('livewire.settings.general')->layout('layouts.app');
    }
}