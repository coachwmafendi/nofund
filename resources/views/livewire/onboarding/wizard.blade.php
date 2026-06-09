<div class="min-h-screen bg-slate-950 flex flex-col items-center justify-center px-4 py-12">
    <!-- Logo -->
    <a href="/" class="mb-8 text-2xl font-bold text-emerald-500">nofund</a>

    <!-- Progress Steps -->
    <div class="flex items-center gap-3 mb-8">
        <div class="flex items-center gap-2">
            <div class="{{ $step >= 1 ? 'bg-emerald-500 text-white' : 'bg-slate-800 text-slate-400' }} w-7 h-7 rounded-full flex items-center justify-center text-sm font-medium">
                1
            </div>
            <span class="{{ $step >= 1 ? 'text-slate-200' : 'text-slate-500' }} text-sm">Organization</span>
        </div>
        <div class="w-8 h-px bg-slate-700"></div>
        <div class="flex items-center gap-2">
            <div class="{{ $step >= 2 ? 'bg-emerald-500 text-white' : 'bg-slate-800 text-slate-400' }} w-7 h-7 rounded-full flex items-center justify-center text-sm font-medium">
                2
            </div>
            <span class="{{ $step >= 2 ? 'text-slate-200' : 'text-slate-500' }} text-sm">Done</span>
        </div>
    </div>

    <!-- Step Content -->
    <div class="w-full max-w-md">
        @if ($step === 1)
            <div class="rounded-xl border border-slate-800 bg-slate-900/50 p-6">
                <h2 class="text-xl font-semibold text-slate-50 mb-2">Tell us about your organization</h2>
                <p class="text-sm text-slate-400 mb-6">This information will be used to set up your account.</p>

                <form wire:submit="nextStep" class="space-y-4">
                    <!-- Organization Name -->
                    <div>
                        <x-form.label for="orgName" required>Organization Name</x-form.label>
                        <x-form.input
                            wire:model="orgName"
                            id="orgName"
                            name="orgName"
                            type="text"
                            placeholder="e.g. Masjid Jamek"
                            required
                            autofocus
                        />
                        <x-form.error name="orgName" />
                    </div>

                    <!-- URL Slug -->
                    <div>
                        <x-form.label for="orgSlug" required>URL Slug</x-form.label>
                        <x-form.input
                            wire:model="orgSlug"
                            id="orgSlug"
                            name="orgSlug"
                            type="text"
                            placeholder="masjid-jamek"
                            required
                        />
                        <p class="mt-1 text-xs text-slate-500">Your organization's URL: nofund.app/org/masjid-jamek</p>
                        <x-form.error name="orgSlug" />
                    </div>

                    <!-- Organization Type -->
                    <div>
                        <x-form.label for="orgType" required>Organization Type</x-form.label>
                        <x-form.select wire:model="orgType" id="orgType" name="orgType" required>
                            <option value="">Select type...</option>
                            @foreach (\App\Enums\OrganizationType::cases() as $type)
                                <option value="{{ $type->value }}">{{ ucfirst($type->value) }}</option>
                            @endforeach
                        </x-form.select>
                        <x-form.error name="orgType" />
                    </div>

                    <!-- Contact Email -->
                    <div>
                        <x-form.label for="orgEmail" required>Contact Email</x-form.label>
                        <x-form.input
                            wire:model="orgEmail"
                            id="orgEmail"
                            name="orgEmail"
                            type="email"
                            placeholder="contact@organization.org"
                            required
                        />
                        <x-form.error name="orgEmail" />
                    </div>

                    <!-- Submit -->
                    <x-buttons.primary type="submit" class="w-full">
                        Continue
                    </x-buttons.primary>
                </form>
            </div>
        @elseif ($step === 2)
            <div class="rounded-xl border border-slate-800 bg-slate-900/50 p-6 text-center">
                <div class="mb-4">
                    <svg class="mx-auto h-12 w-12 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-slate-50 mb-2">You're all set!</h2>
                <p class="text-sm text-slate-400 mb-6">Your organization has been created. You can now start managing your campaigns and donations.</p>

                <div class="bg-slate-800/50 rounded-lg p-4 mb-6 text-left">
                    <p class="text-sm text-slate-300 font-medium">{{ $orgName }}</p>
                    <p class="text-xs text-slate-500">{{ ucfirst($orgType) }} &middot; {{ $orgEmail }}</p>
                </div>

                <x-buttons.primary wire:click="complete" class="w-full">
                    Go to Dashboard
                </x-buttons.primary>

                <button wire:click="back" class="mt-3 text-sm text-slate-400 hover:text-slate-300">
                    Go back
                </button>
            </div>
        @endif
    </div>
</div>