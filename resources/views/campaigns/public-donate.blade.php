<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Donate to {{ $campaign->title }}</title>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css'])
    <style>body{background:#0f172a;color:#f8fafc;font-family:Inter,sans-serif;}</style>
</head>
<body class="antialiased p-6">
    <div class="max-w-md mx-auto">
        <div class="mb-6 text-center">
            <h1 class="text-xl font-semibold text-slate-50">{{ $campaign->title }}</h1>
            <p class="mt-1 text-sm text-slate-400">{{ $campaign->organization->name }}</p>
        </div>

        <div class="mb-6 rounded-xl bg-slate-900 border border-slate-800 p-4">
            <div class="flex justify-between text-sm mb-2">
                <span class="text-slate-400">Raised</span>
                <span class="font-medium text-emerald-400">RM {{ number_format($campaign->raised_amount, 2) }}</span>
            </div>
            <div class="w-full bg-slate-800 rounded-full h-2">
                <div class="bg-emerald-500 h-2 rounded-full" style="width: {{ min(100, ($campaign->raised_amount / max($campaign->target_amount, 1)) * 100) }}%"></div>
            </div>
            <div class="mt-2 text-xs text-slate-500 text-center">of RM {{ number_format($campaign->target_amount, 2) }} target</div>
        </div>

        <form class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1">Amount (RM)</label>
                <input type="number" min="1" step="0.01" class="block w-full rounded-lg border border-slate-700 bg-slate-900/50 px-3 py-2 text-sm text-slate-50 placeholder:text-slate-500 focus:border-emerald-500/50 focus:ring-1 focus:ring-emerald-500/50 focus:outline-none" placeholder="50.00" />
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1">Full Name</label>
                <input type="text" class="block w-full rounded-lg border border-slate-700 bg-slate-900/50 px-3 py-2 text-sm text-slate-50 placeholder:text-slate-500 focus:border-emerald-500/50 focus:ring-1 focus:ring-emerald-500/50 focus:outline-none" placeholder="Your name" />
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1">Email</label>
                <input type="email" class="block w-full rounded-lg border border-slate-700 bg-slate-900/50 px-3 py-2 text-sm text-slate-50 placeholder:text-slate-500 focus:border-emerald-500/50 focus:ring-1 focus:ring-emerald-500/50 focus:outline-none" placeholder="you@example.com" />
            </div>

            <button type="button" class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white transition-colors hover:bg-emerald-500">
                Donate Now
            </button>
        </form>

        <p class="mt-4 text-xs text-center text-slate-500">Powered by nofund</p>
    </div>
</body>
</html>
