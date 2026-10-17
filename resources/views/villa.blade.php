<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Our Villas | Casa Verde Cliff Resort & Spa
    </title>

    @vite([
        'resources/js/villa-photos.js',
        'resources/css/header.css',
        'resources/css/villa.css',
        'resources/css/theme.css',
        'resources/css/footer.css',
        'resources/js/app.js'
    ])

</head>

<body>

{{-- =========================================================
     NAVIGATION
========================================================= --}}

@include('layouts.header')

@php
    $vStyle = \App\Models\HomeContent::pageRow($contents, 'villas_page_style');
    $vBottom = \App\Models\HomeContent::pageRows($contents, 'villas_page_bottom');
    $vBook = \App\Models\HomeContent::pageRow($contents, 'villas_page_book');
    $featureIconRows = \App\Models\HomeContent::pageRows($contents, 'villas_feature_icons');
    $vHref = fn (?string $url) => \Illuminate\Support\Str::startsWith($url ?? '', ['http', '#', 'mailto:', 'tel:']) ? $url : url($url ?: '/#contact');
@endphp


{{-- =========================================================
     VILLAS PAGE
========================================================= --}}

<main>

    <section class="villas-section">


        {{-- =================================================
             PAGE HEADER
        ================================================== --}}

        @if($villasHeader)

            <div class="villas-header">

                <p class="section-label">
                    {{ $villasHeader->subtitle }}
                </p>

                <h1>
                    {{ $villasHeader->title }}
                </h1>

                <p class="section-description">
                    {{ $villasHeader->description }}
                </p>

            </div>

        @else

            <div class="villas-header">

                <p class="section-label">
                    OUR VILLAS
                </p>

                <h1>
                    Choose your private villa
                </h1>

                <p class="section-description">
                    Two distinctive villa styles, each designed
                    for privacy, comfort and slow island living.
                </p>

            </div>

        @endif



        {{-- =================================================
             VILLA CARDS
        ================================================== --}}

        <div class="villa-container">

            @foreach($villas as $villa)

                <article class="villa-card">


                    {{-- IMAGE --}}

                    @php
                        $photos = $villaPhotos[\Illuminate\Support\Str::slug($villa->title)] ?? [];
                    @endphp

                    @if(count($photos) > 1)

                        {{-- Clicking the photo opens a gallery with all of the villa's photos --}}
                        <button
                            type="button"
                            class="villa-image-wrapper villa-photo-open"
                            data-vm-open="vm-{{ $loop->index }}"
                            aria-haspopup="dialog"
                            aria-label="View all {{ count($photos) }} photos of {{ $villa->title }}"
                        >
                            <img
                                src="{{ asset($villa->image) }}"
                                alt="{{ $villa->title }}"
                                class="villa-image"
                            >

                            <span class="villa-photo-count" aria-hidden="true">
                                <svg viewBox="0 0 24 24"><rect x="3" y="3" width="8" height="8" rx="1.5"/><rect x="13" y="3" width="8" height="8" rx="1.5"/><rect x="3" y="13" width="8" height="8" rx="1.5"/><rect x="13" y="13" width="8" height="8" rx="1.5"/></svg>
                                {{ count($photos) }} photos
                            </span>
                        </button>

                        <dialog class="vm" id="vm-{{ $loop->index }}" data-vm aria-label="{{ $villa->title }} photos">
                            <div class="vm-inner">

                                <p class="vm-title">{{ $villa->title }}</p>

                                <button type="button" class="vm-close" data-vm-close aria-label="Close photos">
                                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18"/></svg>
                                </button>

                                <div class="vm-stage" tabindex="0" aria-roledescription="carousel">
                                    @foreach($photos as $photo)
                                        <figure
                                            class="vm-slide"
                                            data-pos="{{ $loop->first ? '0' : ($loop->iteration === 2 ? '1' : ($loop->last ? '-1' : '2')) }}"
                                            role="group"
                                            aria-roledescription="slide"
                                            aria-label="{{ $loop->iteration }} of {{ count($photos) }}"
                                        >
                                            <img
                                                src="{{ asset($photo) }}"
                                                alt="{{ $villa->title }}, photo {{ $loop->iteration }}"
                                                loading="lazy"
                                            >
                                        </figure>
                                    @endforeach
                                </div>

                                <div class="vm-nav">
                                    <button type="button" data-vm-prev aria-label="Previous photo">
                                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M19 12H5M11 6l-6 6 6 6"/></svg>
                                    </button>
                                    <span class="vm-count" aria-live="polite" data-vm-count>1 / {{ count($photos) }}</span>
                                    <button type="button" data-vm-next aria-label="Next photo">
                                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                                    </button>
                                </div>

                            </div>
                        </dialog>

                    @else

                        <div class="villa-image-wrapper">

                            <img
                                src="{{ asset($villa->image) }}"
                                alt="{{ $villa->title }}"
                                class="villa-image"
                            >

                        </div>

                    @endif


                    {{-- CONTENT --}}

                    <div class="villa-content">

                        <h2>

                            <span class="villa-symbol"><x-glyph :value="$vStyle?->icon" :em="1.1" /></span>

                            {{ $villa->title }}

                        </h2>


                        @if($villa->subtitle)

                            <p class="villa-tagline">

                                {{ $villa->subtitle }}

                            </p>

                        @endif


                        @if($villa->price)

                            <p class="villa-price">

                                {{ $vStyle?->title }}
                                <strong>
                                    {{ number_format($villa->price) }}
                                </strong>

                                <span>
                                    {{ $vStyle?->description }}
                                </span>

                            </p>

                        @endif


                        {{-- FEATURES --}}

                        @php
                            $villaFeatures = is_string($villa->features)
                                ? json_decode($villa->features, true)
                                : $villa->features;

                            if (empty($villaFeatures)) {
                                $villaFeatures = config(
                                    'villas.' . \Illuminate\Support\Str::slug($villa->title) . '.card_features',
                                    []
                                );
                            }
                        @endphp

                        @if(!empty($villaFeatures))

                            <ul class="villa-features">

                                @foreach($villaFeatures as $featureIndex => $feature)
                                    @php
                                        $featureIconRow = $featureIconRows->first(fn ($row) => \Illuminate\Support\Str::slug($row->title ?? '') === \Illuminate\Support\Str::slug($villa->title ?? '') && (int) $row->sort_order === $featureIndex + 1);
                                        $featureIcon = $featureIconRow?->icon;
                                    @endphp

                                    <li>

                                        <span class="feature-icon">@if(filled($featureIcon))<x-icon :name="$featureIcon" :size="18" />@elseif(filled($vStyle?->image))<x-icon :name="$vStyle->image" :size="18" />@else{{ $vStyle?->subtitle }}@endif</span>

                                        <span>
                                            {{ $feature }}
                                        </span>

                                    </li>

                                @endforeach

                            </ul>

                        @endif


                        {{-- BUTTON --}}

                        <a
                            href="{{ filled($villa->button_url) && $villa->button_url !== '#' ? $villa->button_url : route('villas.show', \Illuminate\Support\Str::slug($villa->title)) }}"
                            class="villa-button"
                        >

                            <span><x-glyph :value="$vStyle?->icon" :em="1.1" /></span>
                            {{ $villa->button_text ?: $vStyle?->button_text }}

                        </a>

                    </div>

                </article>

            @endforeach

        </div>

    </section>



    {{-- =====================================================
         BOTTOM INFORMATION / CTA
    ====================================================== --}}

    <section class="villa-bottom">
        <div class="villa-bottom-content">
            @if($intro = $vBottom->first())
                <div class="bottom-intro">
                    <span class="bottom-icon"><x-glyph :value="$intro->icon" :em="1.6" /></span>
                    <div>
                        <h3>{!! nl2br(e($intro->title)) !!}</h3>
                        <p>{!! nl2br(e($intro->description)) !!}</p>
                    </div>
                </div>
            @endif

            @foreach($vBottom->slice(1) as $point)
                <div class="bottom-divider"></div>
                <div class="bottom-feature">
                    <span class="bottom-icon"><x-glyph :value="$point->icon" :em="1.6" /></span>
                    <p>{!! nl2br(e($point->description)) !!}</p>
                </div>
            @endforeach

            @if($vBook && filled($vBook->button_text))
                <a href="{{ $vHref($vBook->button_url) }}" class="bottom-book-button">
                    <span><x-glyph :value="$vBook->icon" :em="1.1" /></span>
                    {{ $vBook->button_text }}
                </a>
            @endif
        </div>
    </section>

</main>

    @include('layouts.footer')

</body>
</html>