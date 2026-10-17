@props(['value' => null, 'em' => 1.3])

@php
    $glyph = trim((string) $value);
@endphp

@if ($glyph !== '')
    <x-icon :name="$glyph" :size="30" class="cv-glyph" style="width:auto;height:{{ $em }}em;vertical-align:middle;object-fit:contain" />
@endif
