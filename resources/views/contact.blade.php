<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v=20261017" sizes="any">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=20261017">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | Casa Verde Cliff Resort &amp; Spa</title>
    @vite(['resources/css/header.css','resources/css/pages.css','resources/css/contact.css','resources/css/theme.css','resources/css/footer.css','resources/css/rule.css','resources/js/app.js'])
</head>
<body>
@include('layouts.header')
@php
    $heading = $contact?->title ?: 'Get in touch';
    $eyebrow = $contact?->subtitle ?: 'CONTACT US';
    $intro = $contact?->description ?: 'We would love to help you plan your stay at Casa Verde Cliff Resort & Spa.';
    $address = $map?->subtitle ?: 'Mankahilo, Consuelo, Camotes Islands, Cebu 6050, Philippines';
    $email = trim((string) ($contact?->button_text ?? ''));
    $phone = trim((string) ($contact?->button_url ?? ''));
    $place = 'Casa Verde Cliff Resort and Spa, Mankahilo, Consuelo, Camotes, Cebu';
    $defaultMap = 'https://www.google.com/maps?q='.rawurlencode($place).'&t=k&z=17&output=embed';
    $candidate = trim((string) ($map?->description ?? ''));
    // Restrict admin-supplied iframe URLs to Google Maps to prevent arbitrary embedded sites.
    $host = parse_url($candidate, PHP_URL_HOST);
    $validMap = is_string($host) && in_array(strtolower($host), ['www.google.com','google.com','maps.google.com'], true)
        && str_starts_with((string) parse_url($candidate, PHP_URL_PATH), '/maps');
    $mapUrl = $validMap && str_starts_with($candidate, 'https://') ? $candidate : $defaultMap;
    $mapsLink = trim((string) ($map?->button_url ?? ''));
    $linkHost = parse_url($mapsLink, PHP_URL_HOST);
    if (!str_starts_with($mapsLink, 'https://') || !is_string($linkHost) || !in_array(strtolower($linkHost), ['google.com','www.google.com','maps.google.com','maps.app.goo.gl'], true)) {
        $mapsLink = 'https://www.google.com/maps/search/?api=1&query='.rawurlencode($place);
    }
@endphp
<main class="cv-contact">
    <section class="cv-contact-intro">
        <span class="cv-contact-eyebrow">{{ $eyebrow }}</span>
        <h1>{{ $heading }}</h1>
        <p>{{ $intro }}</p>
    </section>

    <section class="cv-contact-details" aria-label="Contact information">
        <article class="cv-contact-detail cv-contact-detail--featured">
            <span class="cv-contact-detail-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/></svg></span>
            <h2>Address</h2><p>{{ $address }}</p>
        </article>
        <article class="cv-contact-detail">
            <span class="cv-contact-detail-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.9v3a2 2 0 0 1-2.2 2A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7l.5 3a2 2 0 0 1-.6 1.7L7.1 10a16 16 0 0 0 6.9 6.9l1.6-1.9a2 2 0 0 1 1.7-.6l3 .5a2 2 0 0 1 1.7 1.9Z"/></svg></span>
            <h2>Contact</h2>
            @if($phone)<p><a href="tel:{{ preg_replace('/[^+0-9]/', '', $phone) }}">{{ $phone }}</a></p>@else<p>Contact us through the form below.</p>@endif
        </article>
        <article class="cv-contact-detail">
            <span class="cv-contact-detail-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="3"/><path d="m3 6 9 7 9-7"/></svg></span>
            <h2>Email</h2>
            @if($email && filter_var($email, FILTER_VALIDATE_EMAIL))<p><a href="mailto:{{ $email }}">{{ $email }}</a></p>@else<p>Send us a message below.</p>@endif
        </article>
    </section>

    <section class="cv-contact-lower" aria-label="Location and inquiry form">
        <div class="cv-contact-map-section">
            <div class="cv-contact-map-frame">
                <iframe src="{{ $mapUrl }}" title="Casa Verde Cliff Resort & Spa location on Google Maps" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
            </div>
            <a class="cv-contact-maps-link" href="{{ $mapsLink }}" target="_blank" rel="noopener noreferrer">{{ $map?->button_text ?: 'Open in Google Maps' }} <span aria-hidden="true">↗</span></a>
        </div>
        <div class="cv-contact-form-section">
            <h2 class="cv-contact-form-title">Send us a message</h2>
            <form action="{{ route('contact.store') }}" method="POST" class="cv-contact-form">
                @csrf
                @if(session('contact_status'))<p class="cv-contact-success" role="status">{{ session('contact_status') }}</p>@endif
                @if($errors->any())<p class="cv-contact-error" role="alert">Please review the fields and try again.</p>@endif
                <label><span class="cv-contact-field-label">Your Name <span aria-hidden="true">*</span></span><input type="text" name="name" placeholder="Name" value="{{ old('name') }}" required maxlength="100" autocomplete="name"></label>
                <label><span class="cv-contact-field-label">Email <span aria-hidden="true">*</span></span><input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required maxlength="150" autocomplete="email"></label>
                <label><span class="cv-contact-field-label">Subject <span aria-hidden="true">*</span></span><input type="text" name="subject" placeholder="Subject" value="{{ old('subject') }}" required maxlength="150"></label>
                <label><span class="cv-contact-field-label">Description <span aria-hidden="true">*</span></span><textarea name="message" placeholder="Message" rows="4" required maxlength="3000">{{ old('message') }}</textarea></label>
                <button type="submit">Send Message</button>
            </form>
        </div>
    </section>
</main>
@include('layouts.footer')
</body>
</html>
