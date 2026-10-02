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
        'villa'     => '<path d="M3 11 12 4l9 7"/><path d="M5.5 10v6h13v-6"/><path d="M3 19.5c1.5-1 3-1 4.5 0s3 1 4.5 0 3-1 4.5 0 3 1 4.5 0"/>',
        'bed'       => '<path d="M3 18v-6a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v6"/><path d="M3 15h18M6 10V7h12v3"/>',
        'beds'      => '<path d="M2 18v-5a1.5 1.5 0 0 1 1.5-1.5h8A1.5 1.5 0 0 1 13 13v5M2 15h11M4 11.5V8h7v3.5"/><path d="M15 18v-5a1.5 1.5 0 0 1 1.5-1.5h4A1.5 1.5 0 0 1 22 13v5M15 15h7M17 11.5V8h4v3.5"/>',
        'bath'      => '<path d="M3 12h18v2a5 5 0 0 1-5 5H8a5 5 0 0 1-5-5v-2Z"/><path d="M6 12V6a2 2 0 0 1 4 0M7 19l-1 2M17 19l1 2"/>',
        'jacuzzi'   => '<path d="M3 13h18v1.5a4.5 4.5 0 0 1-4.5 4.5h-9A4.5 4.5 0 0 1 3 14.5V13Z"/><circle cx="8" cy="8.5" r="1.3"/><circle cx="12" cy="6" r="1.3"/><circle cx="16" cy="8.5" r="1.3"/>',
        'terrace'   => '<path d="M4 11a8 8 0 0 1 16 0H4Z"/><path d="M12 11v8M7 19h10"/>',
        'wifi'      => '<path d="M2.5 9.5a14 14 0 0 1 19 0M5.5 12.8a9.5 9.5 0 0 1 13 0M8.6 16a5 5 0 0 1 6.8 0"/><circle cx="12" cy="19" r="1" fill="currentColor"/>',
        'lotus'     => '<path d="M12 20c-3-2-5-5-5-9 2 1 4 3 5 5 1-2 3-4 5-5 0 4-2 7-5 9Z"/><path d="M12 16c-1.5-2-1.5-6 0-9 1.5 3 1.5 7 0 9Z"/>',
        'rings'     => '<circle cx="9" cy="14.5" r="5"/><circle cx="15.5" cy="14.5" r="5"/><path d="m7.5 6 1.5-2.5L10.5 6 9 8 7.5 6Z"/>',
        'cake'      => '<path d="M4 20h16M5 20v-6a1.5 1.5 0 0 1 1.5-1.5h11A1.5 1.5 0 0 1 19 14v6"/><path d="M5 16c2 1.5 3.5 1.5 5 0s3-1.5 4.5 0 3 1.5 4.5 0M12 12.5V9M12 9c-.9-.8-.9-1.8 0-3 .9 1.2.9 2.2 0 3Z"/>',
        'cliff'     => '<path d="M3 21h18M5 21V12l3-2 2 3 3-7 3 4v11"/><path d="M16 8V4m0 0h3.5L18 6l1.5 2H16"/>',
        'cloche'    => '<path d="M4 17a8 8 0 0 1 16 0H4Z"/><path d="M2.5 20h19M12 9V7"/><circle cx="12" cy="6.2" r=".9"/>',
        'clipboard' => '<rect x="5" y="4.5" width="14" height="16.5" rx="2"/><path d="M9 4.5h6V3H9v1.5ZM8.5 10h7M8.5 13.5h7M8.5 17h4"/>',
        'stones'    => '<ellipse cx="12" cy="18" rx="7" ry="2.6"/><ellipse cx="12" cy="13" rx="5" ry="2.2"/><ellipse cx="12" cy="8.6" rx="3" ry="1.8"/>',
        'hands'     => '<path d="M4 15c2-1 4-3 6-3.5l4 1c1 .3 1.3 1.5.4 2L10 17.5"/><path d="M9 11 6.5 6.8M12 10.5 10.5 5M15 11.5 14.5 6.5M18 13l1.5-4"/><path d="M4 15.5 8 20"/>',
        'foot'      => '<path d="M9 4c-2 0-3 2-3 5 0 2 .6 3.4 1.2 5 .5 1.4.2 3 .2 4.5a2.5 2.5 0 0 0 5 0c0-2 1-3 1-5.5C13.4 8 11.5 4 9 4Z"/><circle cx="15.5" cy="5.5" r=".9"/><circle cx="17.7" cy="8" r=".8"/><circle cx="18.6" cy="11" r=".8"/>',
        'spaset'    => '<rect x="5" y="9" width="5" height="11" rx="1.5"/><path d="M6.5 9V6.5h2V9M13 20v-7h5v7M13 20h5"/><path d="M15.5 13v-2m0-3c-1-1-1-2 0-3 1 1 1 2 0 3Z"/>',
        'calendar'  => '<rect x="3.5" y="5" width="17" height="15" rx="2"/><path d="M3.5 10h17M8 3v4M16 3v4M7.5 13.5h2M11 13.5h2M14.5 13.5h2M7.5 16.5h2M11 16.5h2"/>',
        'shield'    => '<path d="M12 3 5 5.8v5.6c0 4.3 2.8 7.6 7 9.6 4.2-2 7-5.3 7-9.6V5.8L12 3Z"/><path d="m8.8 12 2.4 2.4 4.2-4.6"/>',
        'cocktail'  => '<path d="M5 5h14l-7 8-7-8Z"/><path d="M12 13v7M8.5 20h7M15.5 3.5 13.2 7.6"/><circle cx="16.6" cy="3.2" r="1"/>',
        'fish'      => '<path d="M3 12c2.4-4 6.4-5.6 10-4.2 2.2.9 4 2.5 5.2 4.2-1.2 1.7-3 3.3-5.2 4.2C9.4 17.6 5.4 16 3 12Z"/><path d="m18.2 12 3.3-3.6v7.2L18.2 12Z"/><circle cx="7.8" cy="11" r=".8" fill="currentColor"/>',
        'sunset'    => '<path d="M4.5 16a7.5 7.5 0 0 1 15 0"/><path d="M2.5 16h19M12 4.5V6.5M5.3 8.3l1.2 1.2M18.7 8.3l-1.2 1.2M5.5 19.5h13M8.5 22h7"/>',
        'coffee'    => '<path d="M4 10h11v4a5 5 0 0 1-5 5H9a5 5 0 0 1-5-5v-4Z"/><path d="M15 11h1.5a2.5 2.5 0 0 1 0 5H15M8 3.5c-.8 1 .8 2 0 3.2M11.5 3.5c-.8 1 .8 2 0 3.2M3 21.5h13"/>',
        'cutlery'   => '<path d="M5.5 3v6.5M9 3v6.5M5.5 9.5a1.8 1.8 0 0 0 3.5 0M7.3 11.3V21"/><path d="M17 21V3c-2.3 1.5-3.2 4-3.2 7.5H17"/>',
        'wine'      => '<path d="M4.5 4h6.5c0 3.6-1.2 5.6-3.25 5.6S4.5 7.6 4.5 4Z"/><path d="M7.75 9.6V17M5.25 17h5"/><path d="M13.5 6H20c0 3.6-1.2 5.6-3.25 5.6S13.5 9.6 13.5 6Z"/><path d="M16.75 11.6V19M14.25 19h5"/>',
        'arrow'     => '<path d="M4 12h15m-5-5 5 5-5 5"/>',
    ];
@endphp

<svg {{ $attributes->merge(['class' => 'cv-icon']) }} width="{{ $size }}" height="{{ $size }}" viewBox="0 0 24 24"
     fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    {!! $paths[$name] ?? '' !!}
</svg>
