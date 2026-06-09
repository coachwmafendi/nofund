<label
    for="{{ $for }}"
    class="block text-sm font-medium text-slate-300 mb-1"
>
    {{ $slot }}@if($required ?? false)<span class="text-red-400">*</span>@endif
</label>