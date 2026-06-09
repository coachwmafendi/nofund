<?php

namespace App\Livewire\Auth;

use App\Livewire\Onboarding\Wizard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Login extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    #[Layout('layouts.guest')]
    public function render()
    {
        return view('livewire.auth.login');
    }

    public function login()
    {
        $this->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $key = 'login-' . $this->email;

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            $this->addError('email', "Too many login attempts. Please try again in {$seconds} seconds.");
            return;
        }

        RateLimiter::hit($key, 60);

        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            $this->addError('email', 'The provided credentials are incorrect.');
            return;
        }

        $user = Auth::user();

        if (!$user->organization_id) {
            return redirect()->route('onboarding');
        }

        $this->dispatch('toast', message: 'Welcome back!', type: 'success');

        return redirect()->intended(route('dashboard', absolute: false));
    }
}