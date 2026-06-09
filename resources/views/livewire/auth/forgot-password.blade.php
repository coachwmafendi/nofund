<div class="min-h-screen bg-slate-950 flex flex-col items-center justify-center px-4 py-12">
    <!-- Logo -->
    <a href="/" class="mb-8 text-2xl font-bold text-emerald-500">nofund</a>

    <!-- Card -->
    <div class="w-full max-w-md">
        <div class="rounded-xl border border-slate-800 bg-slate-900/50 p-6">
            <h2 class="text-xl font-semibold text-slate-50 mb-2">Reset your password</h2>
            <p class="text-sm text-slate-400 mb-6">Enter your email and we'll send you a reset link.</p>

            <!-- Success Message -->
            @if (session('status'))
                <div class="mb-4 rounded-lg bg-emerald-500/10 border border-emerald-500/20 px-4 py-3 text-sm text-emerald-400">
                    {{ session('status') }}
                </div>
            @endif

            <form wire:submit="sendResetLink" class="space-y-4">
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

                <!-- Submit -->
                <x-buttons.primary type="submit" class="w-full">
                    Send reset link
                </x-buttons.primary>
            </form>
        </div>

        <!-- Back to Login -->
        <div class="mt-4 text-center">
            <a href="{{ route('login') }}" class="text-sm text-slate-400 hover:text-slate-300">
                Back to sign in
            </a>
        </div>
    </div>
</div>