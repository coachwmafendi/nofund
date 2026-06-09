<div class="mb-8">
    <h1 class="text-2xl font-semibold text-slate-50">{{ $title }}</h1>
    @if($description)
        <p class="mt-1 text-sm text-slate-400">{{ $description }}</p>
    @endif
    @if(isset($actions))
        <div class="mt-4 flex items-center gap-3">{{ $actions }}</div>
    @endif
</div>