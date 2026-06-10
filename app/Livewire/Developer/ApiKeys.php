<?php

namespace App\Livewire\Developer;

use App\Models\ApiKey;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Component;

class ApiKeys extends Component
{
    public string $keyName = '';
    public bool $showCreateModal = false;
    public ?string $newlyCreatedKey = null;

    public function mount()
    {
        //
    }

    public function createKey()
    {
        $this->validate([
            'keyName' => 'required|string|max:255',
        ]);

        $plainKey = 'nf_' . Str::random(40);

        Auth::user()->organization->apiKeys()->create([
            'name' => $this->keyName,
            'key_hash' => Hash::make($plainKey),
            'scopes' => ['read'],
        ]);

        $this->newlyCreatedKey = $plainKey;
        $this->showCreateModal = false;
        $this->keyName = '';

        $this->dispatch('toast', message: 'API key created successfully.', type: 'success');
    }

    public function revokeKey($id)
    {
        $apiKey = Auth::user()->organization->apiKeys()->findOrFail($id);
        $apiKey->update(['revoked_at' => now()]);
        $this->dispatch('toast', message: 'API key revoked successfully.', type: 'success');
    }

    public function render()
    {
        $apiKeys = Auth::user()->organization->apiKeys()->latest()->get();

        return view('livewire.developer.api-keys', [
            'apiKeys' => $apiKeys,
        ])->layout('layouts.app');
    }
}