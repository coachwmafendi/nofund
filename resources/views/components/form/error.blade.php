@props(['name' => null, 'for' => null])

@php
    $fieldName = $name ?? $for;
@endphp

@error($fieldName)
    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
@enderror