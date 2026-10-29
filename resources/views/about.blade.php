<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v=20261017" sizes="any">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=20261017">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | Casa Verde Cliff Resort &amp; Spa</title>
    @vite(['resources/css/header.css', 'resources/css/pages.css', 'resources/css/about.css', 'resources/css/theme.css', 'resources/css/footer.css','resources/css/rule.css', 'resources/js/app.js'])
</head>
<body>
@include('layouts.header')
<main class="cv-about">
    <section class="cv-about-hero">
        <div class="cv-about-hero-inner">
            <span class="cv-about-eyebrow">CASA VERDE CLIFF RESORT &amp; SPA</span>
            <h1>Rooted in Nature.<br><em>Inspired by Camotes.</em></h1>
            <x-rule />
            <p>Discover our story, our values, and our promise to preserve the island we call home.</p>
        </div>
    </section>

    @foreach($contents->get('about_intro', collect()) as $story)
        <section class="cv-about-story cv-about-container">
            <div class="cv-about-side-label"><span>01 / OUR BEGINNING</span><h2>{{ $story->title }}</h2></div>
            <div class="cv-about-copy">@foreach(preg_split('/\R\s*\R/', trim((string)$story->description)) as $paragraph)<p>{{ $paragraph }}</p>@endforeach</div>
        </section>
    @endforeach

    <section class="cv-about-values">
        <div class="cv-about-container">
            <div class="cv-about-section-title"><span>WHAT DEFINES US</span><h2>Thoughtful hospitality,<br><em>with purpose.</em></h2></div>
            <div class="cv-about-values-grid">
                @foreach($contents->get('about_values', collect())->filter(fn ($value) => filled(trim(strip_tags((string) ($value->description ?? '')))))->values() as $value)
                    <article class="cv-about-value-card">
                        <div class="cv-about-card-number">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</div>
                        <h3>{{ $value->title }}</h3>
                        @foreach(preg_split('/\R\s*\R/', trim((string)$value->description)) as $paragraph)<p>{{ $paragraph }}</p>@endforeach
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="cv-about-sustain cv-about-container">
        <div class="cv-about-section-title"><span>CARING FOR OUR ISLAND</span><h2>Our Commitment to<br><em>Sustainability</em></h2><p>Preserving the natural beauty of Camotes is at the heart of how we welcome our guests.</p></div>
        <div class="cv-about-sustain-grid">
            @foreach($contents->get('about_sustainability', collect())->filter(fn ($item) => filled(trim(strip_tags((string) ($item->description ?? '')))))->values() as $item)
                <article class="cv-about-sustain-card">
                    <span class="cv-about-sustain-index">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                    <h3>{{ $item->title }}</h3>
                    <p>{{ $item->description }}</p>
                </article>
            @endforeach
        </div>
    </section>

    <section class="cv-about-end"><span>YOUR ISLAND ESCAPE AWAITS</span><h2>Come experience Casa Verde.</h2><a href="{{ route('contact.index') }}">Get in touch <span aria-hidden="true">↗</span></a></section>
</main>
@include('layouts.footer')
</body>
</html>
