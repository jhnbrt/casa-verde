@props(['ornament' => null])

@php
    // The line design (style, colour and default ornament) is edited once in the admin
    // under Site-wide > "Line design". A section can override the ornament; type "none"
    // for a plain line.
    $design = \App\Models\HomeContent::divider();
    $mark = trim((string) $ornament) !== '' ? trim((string) $ornament) : $design['ornament'];
    $mark = in_array(strtolower($mark), ['none', 'no', '-'], true) ? '' : $mark;
@endphp

<div {{ $attributes->class(['cv-rule', 'cv-rule--'.$design['style']]) }} @if($design['color']) style="color:{{ $design['color'] }}" @endif>
    <i></i>@if($mark !== '')<x-icon :name="$mark" :size="26" />@endif<i></i>
</div>
