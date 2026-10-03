<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery | Casa Verde Cliff Resort &amp; Spa</title>
    @vite([
        'resources/css/header.css',
        'resources/css/pages.css',
        'resources/css/gallery.css',
        'resources/css/theme.css',
        'resources/css/footer.css',
        'resources/js/gallery.js',
        'resources/js/app.js'
    ])
</head>
<body>
    @include('layouts.header')

    @php
        $book = url('/') . '#contact';
        $chips = ['all' => 'All', 'resort' => 'Resort', 'villas' => 'Villas', 'dining' => 'Dining', 'wellness' => 'Wellness', 'experiences' => 'Experience', 'sunsets' => 'Sunsets'];
        $keys = array_keys($sections);
    @endphp

    <main class="cv-page gal" id="gallery">

        {{-- OVERVIEW: heading, filters, mosaic --}}
        <section class="gal-overview" data-panel="all" aria-labelledby="gal-title">
            <header class="gal-head">
                <span class="gal-eyebrow">{{ $header['eyebrow'] }}</span>
                <h1 id="gal-title">{{ $header['title'] }}</h1>
                <p>{{ $header['text'] }}</p>
            </header>

            <nav class="gal-chips" aria-label="Gallery categories">
                @foreach($chips as $key => $label)
                    <a href="#{{ $key }}" data-chip="{{ $key }}" @class(['gal-chip', 'is-on' => $loop->first])>{{ $label }}</a>
                @endforeach
            </nav>

            <div class="gal-mosaic">
                @foreach($overview as $tile)
                    <a href="#{{ $tile['cat'] }}" class="gal-tile gal-tile--{{ $loop->iteration }}" aria-label="View {{ $tile['label'] }} photos">
                        <img src="{{ $tile['img'] }}" alt="" loading="{{ $loop->first ? 'eager' : 'lazy' }}">
                        <span class="gal-pill">{{ $tile['label'] }}</span>
                    </a>
                @endforeach
            </div>
        </section>

        {{-- CATEGORY SECTIONS --}}
        @foreach($sections as $key => $section)
            @php $next = $keys[($loop->index + 1) % count($keys)]; @endphp

            <section class="gal-section" id="section-{{ $key }}" data-panel="{{ $key }}" aria-labelledby="title-{{ $key }}">
                <a href="#all" class="gal-back">
                    <x-icon name="arrow" :size="16" class="gal-back-icon" /> Back to gallery
                </a>

                <div class="gal-frame">
                    <header class="gal-frame-head">
                        <h2 id="title-{{ $key }}">{{ $section['title'] }}</h2>
                        <p>{{ $section['subtitle'] }}</p>
                    </header>

                    <div class="gal-grid gal-grid--{{ $section['layout'] }}">
                        @foreach($section['items'] as $item)
                            <figure @class(['gal-item', 'gal-item--'.$loop->iteration, 'has-label' => isset($item['label'])])>
                                <button type="button" class="gal-open" data-full="{{ $item['img'] }}" data-alt="{{ $item['alt'] }}" aria-label="Enlarge photo: {{ $item['alt'] }}">
                                    <img src="{{ $item['img'] }}" alt="{{ $item['alt'] }}" loading="lazy">
                                </button>

                                @isset($item['label'])
                                    <figcaption>
                                        <span class="gal-badge"><x-icon :name="$item['icon']" :size="22" /></span>
                                        {{ $item['label'] }}
                                    </figcaption>
                                @endisset
                            </figure>
                        @endforeach
                    </div>
                </div>

                <a href="#{{ $next }}" class="gal-next">Next: {{ $sections[$next]['title'] }} <x-icon name="arrow" :size="16" /></a>
            </section>
        @endforeach

        {{-- CTA BAR --}}
        <aside class="gal-cta" aria-label="Book your stay">
            <x-icon name="leaf" :size="42" class="gal-cta-leaf" />
            <p>Ready to experience it in person?</p>
            <a href="{{ $book }}" class="cv-btn cv-btn--gold">Book your stay</a>
        </aside>

        {{-- Lightbox --}}
        <div class="gal-lb" id="galLightbox" role="dialog" aria-modal="true" aria-label="Photo viewer" hidden>
            <button type="button" class="gal-lb-close" aria-label="Close">&times;</button>
            <button type="button" class="gal-lb-nav gal-lb-prev" aria-label="Previous photo">&#8249;</button>
            <img alt="">
            <button type="button" class="gal-lb-nav gal-lb-next" aria-label="Next photo">&#8250;</button>
        </div>
    </main>

    @include('layouts.footer')
</body>
</html>
