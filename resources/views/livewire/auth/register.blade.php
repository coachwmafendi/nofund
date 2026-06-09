<div class="min-h-screen bg-slate-950 flex flex-col items-center justify-center px-4 py-12">
    <!-- Logo -->
    <a href="/" class="mb-8 text-2xl font-bold text-emerald-500">nofund</a>

    <!-- Card -->
    <div class="w-full max-w-md">
        <div class="rounded-xl border border-slate-800 bg-slate-900/50 p-6">
            <h2 class="text-xl font-semibold text-slate-50 mb-6">Create your account</h2>

            <form wire:submit="register" class="space-y-4">
                <!-- Name -->
                <div>
                    <x-form.label for="name" required>Name</x-form.label>
                    <x-form.input
                        wire:model="name"
                        id="name"
                        name="name"
                        type="text"
                        placeholder="Your full name"
                        required
                        autofocus
                    />
                    <x-form.error name="name" />
                </div>

                <!-- Email -->
                <div>
                    <x-form.label for="email" required>Email</x-form.label>
                    <x-form.input
                        wire:model="email"
                        id="email"
                        name="email"
                        type="email"
                        placeholder="you@example.com"
                        required
                    />
                    <x-form.error name="email" />
                </div>

                <!-- Password -->
                <div>
                    <x-form.label for="password" required>Password</x-form.label>
                    <x-form.input
                        wire:model="password"
                        id="password"
                        name="password"
                        type="password"
                        placeholder="Min. 8 characters"
                        required
                    />
                    <x-form.error name="password" />
                </div>

                <!-- Confirm Password -->
                <div>
                    <x-form.label for="password_confirmation" required>Confirm Password</x-form.label>
                    <x-form.input
                        wire:model="password_confirmation"
                        id="password_confirmation"
                        name="password_confirmation"
                        type="password"
                        placeholder="Confirm your password"
                        required
                    />
                    <x-form.error name="password_confirmation" />
                </div>

                <!-- Submit -->
                <x-buttons.primary type="submit" class="w-full">
                    Create account
                </x-buttons.primary>
            </form>
        </div>

        <!-- Links -->
        <div class="mt-4 text-center text-sm text-slate-400">
            Already have an account?
            <a href="{{ route('login') }}" class="text-emerald-500 hover:text-emerald-400">Sign in</a>
        </div>
    </div>
</div>