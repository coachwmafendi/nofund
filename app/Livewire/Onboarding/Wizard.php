<?php

namespace App\Livewire\Onboarding;

use App\Enums\OrganizationStatus;
use App\Enums\OrganizationType;
use App\Models\Organization;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Wizard extends Component
{
    public int $step = 1;

    // Step 1: Organization Profile
    public string $orgName = '';
    public string $orgSlug = '';
    public string $orgType = '';
    public string $orgEmail = '';

    #[Layout('layouts.guest')]
    public function render()
    {
        return view('livewire.onboarding.wizard');
    }

    public function updatedOrgName($value)
    {
        $this->orgSlug = Str::slug($value);
    }

    public function nextStep()
    {
        $this->validate([
            'orgName' => ['required', 'string', 'max:255'],
            'orgSlug' => ['required', 'string', 'max:255', 'unique:organizations,slug'],
            'orgType' => ['required', 'string', 'in:' . implode(',', array_column(OrganizationType::cases(), 'value'))],
            'orgEmail' => ['required', 'email'],
        ]);

        $this->step = 2;
    }

    public function complete()
    {
        $organization = Organization::create([
            'name' => $this->orgName,
            'slug' => $this->orgSlug,
            'type' => OrganizationType::from($this->orgType),
            'contact_email' => $this->orgEmail,
            'currency' => 'MYR',
            'timezone' => 'Asia/Kuala_Lumpur',
            'status' => OrganizationStatus::ACTIVE,
        ]);

        $user = auth()->user();
        $user->organization_id = $organization->id;
        $user->save();

        return redirect()->route('dashboard');
    }

    public function back()
    {
        $this->step = 1;
    }
}