@props(['name', 'size' => 22])

@php
    $paths = [
        'pin'       => '<path d="M12 21s-7-6.2-7-11.5A7 7 0 0 1 19 9.5C19 14.8 12 21 12 21Z"/><circle cx="12" cy="9.5" r="2.5"/>',
        'phone'     => '<path d="M21 16.5v3a1.6 1.6 0 0 1-1.8 1.6A18.5 18.5 0 0 1 3 4.8 1.6 1.6 0 0 1 4.6 3h3a1.6 1.6 0 0 1 1.6 1.4c.1 1 .4 1.9.7 2.8a1.6 1.6 0 0 1-.4 1.7L8.2 10a13 13 0 0 0 5.8 5.8l1.1-1.3a1.6 1.6 0 0 1 1.7-.4c.9.3 1.8.6 2.8.7a1.6 1.6 0 0 1 1.4 1.7Z"/>',
        'whatsapp'  => '<path d="M3.5 20.5 5 15.8A8.5 8.5 0 1 1 8.4 19l-4.9 1.5Z"/><path d="M9 8.8c.3 2.6 2.8 5.1 5.3 5.6l1.2-1.2-1.9-1-.9.7a4 4 0 0 1-2-2l.7-.9-1-1.9L9 8.8Z"/>',
        'mail'      => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3.5 6.5 8.5 6.5 8.5-6.5"/>',
        'clock'     => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3.5 2"/>',
        'instagram' => '<rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.3" cy="6.7" r=".6" fill="currentColor"/>',
        'facebook'  => '<path d="M14 8.5h2.5V5H14a3.5 3.5 0 0 0-3.5 3.5V11H8v3.5h2.5V21H14v-6.5h2.4l.6-3.5H14V8.5Z" fill="currentColor" stroke="none"/>',
        'tiktok'    => '<path d="M14.5 3v11.2a3.2 3.2 0 1 1-3.2-3.2M14.5 3c.3 2.4 1.9 4 4.5 4.2" />',
        'youtube'   => '<rect x="3" y="6" width="18" height="12" rx="3.5"/><path d="m10.5 9.5 4 2.5-4 2.5v-5Z" fill="currentColor"/>',
        'cave'      => '<path d="M3 20h18M5 20c0-6 3-11 7-11s7 5 7 11"/><path d="M9.5 20c0-3 1.2-5.5 2.5-5.5s2.5 2.5 2.5 5.5"/>',
        'snorkel'   => '<rect x="4" y="9" width="16" height="8" rx="3.5"/><path d="M12 9V4h3.5"/><path d="M4 13h16"/>',
        'padel'     => '<ellipse cx="10" cy="9" rx="5.5" ry="6.5"/><path d="m14 14 6 6M7.5 7h5M7.5 10h5"/>',
        'island'    => '<path d="M12 20V10"/><path d="M12 10c-3-4-7-3-8-1 3-1 6 0 8 1Zm0 0c3-4 7-3 8-1-3-1-6 0-8 1Zm0 0c-1-4 1-6 3-6-1 2-2 4-3 6Z"/><path d="M4 20h16"/>',
        'leaf'      => '<path d="M5 19c0-9 5-14 14-14 0 9-5 14-14 14Z"/><path d="M5 19 14 10"/>',
        'arrow'     => '<path d="M4 12h15m-5-5 5 5-5 5"/>',
    ];
@endphp

<svg {{ $attributes->merge(['class' => 'cv-icon']) }} width="{{ $size }}" height="{{ $size }}" viewBox="0 0 24 24"
     fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    {!! $paths[$name] ?? '' !!}
</svg>
