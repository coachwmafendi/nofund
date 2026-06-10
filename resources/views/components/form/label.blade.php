@props(['for' => null, 'required' => false])

<label
    for="{{ $for }}"
    class="block text-sm font-medium text-slate-300 mb-1"
>
    {{ $slot }}@if($required)<span class="text-red-400">*</span>@endif
</label>