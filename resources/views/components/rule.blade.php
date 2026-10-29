@props(['ornament' => null])

@php
    // One divider for the whole site: a line, an ornament, a line. Its style, colour and
    // ornament are edited once in the admin under Site-wide > "Line design", and every page
    // follows. The ornament is always the same size (see resources/css/rule.css). The
    // "ornament" prop is kept so older pages still work, but it no longer changes anything.
    // Type "none" in the admin for a plain line.
    $design = \App\Models\HomeContent::divider();
    $mark = trim((string) $design['ornament']);
    $mark = in_array(strtolower($mark), ['none', 'no', '-'], true) ? '' : $mark;
@endphp

<div {{ $attributes->class(['cv-rule', 'cv-rule--'.$design['style'], 'cv-rule--default' => ! $design['color']]) }} @if($design['color']) style="color:{{ $design['color'] }}" @endif>
    <i></i>@if($mark !== '')<x-icon :name="$mark" :size="42" />@endif<i></i>
</div>
