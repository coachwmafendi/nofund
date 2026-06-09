<div class="min-h-screen bg-slate-950 flex flex-col items-center justify-center px-4 py-12">
    <!-- Logo -->
    <a href="/" class="mb-8 text-2xl font-bold text-emerald-500">nofund</a>

    <!-- Card -->
    <div class="w-full max-w-md">
        <div class="rounded-xl border border-slate-800 bg-slate-900/50 p-6">
            <h2 class="text-xl font-semibold text-slate-50 mb-6">Sign in to your account</h2>

            <form wire:submit="login" class="space-y-4">
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
                        autofocus
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
                        placeholder="Enter your password"
                        required
                    />
                    <x-form.error name="password" />
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input
                        wire:model="remember"
                        id="remember"
                        type="checkbox"
                        class="h-4 w-4 rounded border-slate-700 bg-slate-900/50 text-emerald-600 focus:ring-emerald-500/50"
                    />
                    <label for="remember" class="ml-2 text-sm text-slate-400">Remember me</label>
                </div>

                <!-- Submit -->
                <x-buttons.primary type="submit" class="w-full">
                    Sign in
                </x-buttons.primary>
            </form>
        </div>

        <!-- Links -->
        <div class="mt-4 text-center text-sm text-slate-400">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-emerald-500 hover:text-emerald-400">Register</a>
        </div>

        <div class="mt-2 text-center">
            <a href="{{ route('password.request') }}" class="text-sm text-slate-400 hover:text-slate-300">
                Forgot your password?
            </a>
        </div>
    </div>
</div>