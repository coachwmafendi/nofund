<?php

use App\Http\Controllers\AuthController;
use App\Http\Middleware\EnsureHasOrganization;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\Onboarding\Wizard;
use App\Livewire\Settings\Billing;
use App\Livewire\Settings\General;
use App\Livewire\Settings\Members;
use App\Livewire\Settings\Receipts;
use App\Livewire\Settings\Security;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Public campaign donation page (for embed)
Route::get('/donate/{campaign}', [\App\Http\Controllers\PublicDonationController::class, 'show'])->name('campaigns.public-donate');

// Guest auth routes - redirect if already authenticated
Route::middleware(['guest'])->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
    Route::get('/forgot-password', ForgotPassword::class)->name('password.request');
    Route::post('/forgot-password', [ForgotPassword::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', ResetPassword::class)->name('password.reset');
});

// Authenticated routes
Route::middleware(['auth', EnsureHasOrganization::class])->group(function () {
    // Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Onboarding (only for users without organization)
    Route::get('/onboarding', Wizard::class)->name('onboarding');

    // Missing links — redirect to existing pages
    Route::redirect('/members', '/settings/members');
    Route::redirect('/billing', '/settings/billing');
    Route::redirect('/organization', '/settings/general');

    // Placeholder pages
    Route::get('/teams', function () {
        return view('livewire.placeholder', ['title' => 'Teams', 'message' => 'Team management is coming soon.']);
    })->name('teams.index');

    Route::get('/developer/embed-forms', function () {
        return view('livewire.placeholder', ['title' => 'Embed Forms', 'message' => 'Embeddable donation forms are coming soon.']);
    })->name('developer.embed-forms');

    // Existing auth routes
    Route::get('/donations', \App\Livewire\Donations\Index::class)->name('donations.index');
    Route::get('/donors', \App\Livewire\Donors\Index::class)->name('donors.index');
    Route::get('/campaigns', \App\Livewire\Campaigns\Index::class)->name('campaigns.index');
    Route::get('/campaigns/create', \App\Livewire\Campaigns\Create::class)->name('campaigns.create');
    Route::get('/campaigns/{campaign}', \App\Livewire\Campaigns\Show::class)->name('campaigns.show');

    // Finance
    Route::get('/payouts', \App\Livewire\Payouts\Index::class)->name('payouts.index');
    Route::get('/transactions', \App\Livewire\Transactions\Index::class)->name('transactions.index');
    Route::get('/refunds', \App\Livewire\Refunds\Index::class)->name('refunds.index');
    Route::get('/reports', \App\Livewire\Reports\Index::class)->name('reports.index');

    // Recurring Plans
    Route::get('/recurring-plans', \App\Livewire\RecurringPlans\Index::class)->name('recurring-plans.index');

    // Developer
    Route::get('/developer/api-keys', \App\Livewire\Developer\ApiKeys::class)->name('developer.api-keys');
    Route::get('/developer/webhooks', \App\Livewire\Developer\Webhooks::class)->name('developer.webhooks');

    // Settings
    Route::redirect('/settings', '/settings/general');
    Route::get('/settings/general', General::class)->name('settings.general');
    Route::get('/settings/members', Members::class)->name('settings.members');
    Route::get('/settings/billing', Billing::class)->name('settings.billing');
    Route::get('/settings/receipts', Receipts::class)->name('settings.receipts');
    Route::get('/settings/security', Security::class)->name('settings.security');
});