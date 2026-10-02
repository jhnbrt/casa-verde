<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $villa->title }} | Casa Verde Cliff Resort &amp; Spa</title>

    <meta name="description" content="{{ \Illuminate\Support\Str::limit($intro, 155) }}">

    @vite([
        'resources/css/header.css',
        'resources/css/villa-show.css',
        'resources/css/theme.css',
        'resources/css/footer.css',
        'resources/js/app.js'
    ])

</head>

<body>

@include('layouts.header')

@php
    $bookUrl   = url('/') . '#contact';
    $layout    = $detail['layout'] ?? null;
    $usd       = $detail['usd'] ?? null;
    $storyHead = $detail['story_title'] ?? [];
    $gallery   = $detail['gallery'] ?? [];
@endphp

<main class="vs-page">

    {{-- =====================================================
         HERO: photo + villa summary
    ====================================================== --}}

    <section class="vs-hero">

        <div class="vs-hero-image">
            <img src="{{ asset($heroImage) }}" alt="{{ $villa->title }} exterior">
        </div>

        <div class="vs-hero-info">

            <p class="vs-eyebrow">Our Villas</p>

            <h1>{{ $villa->title }}</h1>

            @if($tagline)
                <p class="vs-tagline">{{ $tagline }}</p>
            @endif

            <p class="vs-intro">{{ $intro }}</p>

            @if($villa->price)
                <p class="vs-price">
                    <span class="vs-from">From</span>
                    <strong>PHP {{ number_format($villa->price) }}</strong>
                    <span class="vs-per">
                        per night
                        @if($usd)
                            / approx. USD {{ $usd }}
                        @endif
                    </span>
                </p>
            @endif

            <a href="{{ $bookUrl }}" class="vs-check">
                <span class="vs-check-logo">
                    <img src="{{ asset('images/logo.png') }}" alt="" width="28" height="28">
                </span>
                Check availability
            </a>

        </div>

    </section>


    {{-- =====================================================
         HIGHLIGHT STRIP
    ====================================================== --}}

    @if(!empty($detail['highlights']))
        <section class="vs-strip" aria-label="Villa highlights">
            <ul>
                @foreach($detail['highlights'] as $item)
                    <li>
                        <x-icon :name="$item['icon']" :size="40" />
                        <span>{{ $item['label'] }}</span>
                    </li>
                @endforeach
            </ul>
        </section>
    @elseif(!empty($features))
        {{-- Fallback for villas without config: use the card features --}}
        <section class="vs-strip" aria-label="Villa highlights">
            <ul>
                @foreach($features as $feature)
                    <li>
                        <x-icon name="lotus" :size="40" />
                        <span>{{ $feature }}</span>
                    </li>
                @endforeach
            </ul>
        </section>
    @endif


    {{-- =====================================================
         STORY + GALLERY
    ====================================================== --}}

    @if(!empty($storyHead) || count($gallery))
        <section class="vs-story">

            <div class="vs-story-text">
                @if(!empty($storyHead))
                    <h2>
                        @foreach($storyHead as $line)
                            {{ $line }}
                            @unless($loop->last)
                                <br>
                            @endunless
                        @endforeach
                    </h2>
                    <span class="vs-rule"></span>
                @endif

                @foreach($detail['story_text'] ?? [] as $paragraph)
                    <p>{{ $paragraph }}</p>
                @endforeach
            </div>

            @foreach($gallery as $i => $photo)
                <figure @class(['vs-photo', 'vs-photo-main' => $i === 0])>
                    <img
                        src="{{ asset($photo) }}"
                        alt="{{ $villa->title }} photo {{ $i + 1 }}"
                        loading="lazy"
                    >
                </figure>
            @endforeach

        </section>
    @endif


    {{-- =====================================================
         AMENITIES + LAYOUT
    ====================================================== --}}

    @if(!empty($detail['amenities']))
        <section class="vs-amenities">

            <div class="vs-amenities-title">
                <h3>Villa Amenities</h3>
                <x-icon name="lotus" :size="46" />
            </div>

            @foreach($detail['amenities'] as $column)
                <ul class="vs-amenity-list">
                    @foreach($column as $amenity)
                        <li>{{ $amenity }}</li>
                    @endforeach
                </ul>
            @endforeach

            @if($layout)
                <div class="vs-layout">

                    <div class="vs-layout-areas">
                        <h3>Villa Layout</h3>
                        <ul>
                            <li>Interior Area: {{ $layout['interior'] }} sqm</li>
                            <li>Terrace Area: {{ $layout['terrace'] }} sqm</li>
                            <li>Total Area: {{ $layout['total'] }} sqm</li>
                        </ul>
                    </div>

                    @if(!empty($layout['plan']))
                        <img
                            class="vs-plan"
                            src="{{ asset($layout['plan']) }}"
                            alt="{{ $villa->title }} floor plan"
                            loading="lazy"
                        >
                    @endif

                </div>
            @endif

        </section>
    @endif

</main>

{{-- The footer already carries the "Ready to experience Casa Verde?" bar --}}
@include('layouts.footer')

</body>
</html>
