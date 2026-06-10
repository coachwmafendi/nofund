<?php

namespace App\Livewire\Settings;

use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Security extends Component
{
    public string $currentPassword = '';
    public string $newPassword = '';
    public string $confirmPassword = '';

    public function resetPassword(): void
    {
        $this->validate([
            'currentPassword' => 'required',
            'newPassword' => 'required|min:8',
            'confirmPassword' => 'required|same:newPassword',
        ]);

        $user = auth()->user();

        if (!Hash::check($this->currentPassword, $user->password)) {
            $this->addError('currentPassword', 'Current password is incorrect.');
            return;
        }

        $user->update([
            'password' => Hash::make($this->newPassword),
        ]);

        $this->currentPassword = '';
        $this->newPassword = '';
        $this->confirmPassword = '';

        session()->flash('success', 'Password changed successfully.');
    }

    public function render()
    {
        return view('livewire.settings.security')->layout('layouts.app');
    }
}