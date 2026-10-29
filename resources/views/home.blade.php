<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v=20261017" sizes="any">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=20261017">
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Casa Verde Cliff Resort & Spa</title>

    @vite([
        'resources/css/header.css',
        'resources/css/home.css',
        'resources/css/theme.css',
        'resources/css/home-villas.css',
        'resources/css/footer.css','resources/css/rule.css',
        'resources/js/app.js'
    ])
</head>

<body>

@php

    /*
    |--------------------------------------------------------------------------
    | GET SINGLE CONTENT FROM DATABASE
    |--------------------------------------------------------------------------
    */

    $site = $contents->get('site', collect())->first();

    $hero = $contents->get('hero', collect())->first();

    $hl = fn (int $n) => \App\Models\HomeContent::pageItem($contents, 'home_labels', $n);
    // A symbol / icon image set on a section's own entry wins over the shared "Home page: symbols and wording" entries.
    $pick = fn ($row, int $n) => filled($row?->icon) ? $row->icon : $hl($n)?->icon;
    $isImagePath = fn (?string $v): bool => (bool) preg_match('/\.(png|jpe?g|webp|gif|svg)$/i', trim((string) $v));

    $villasHeader = $contents
        ->get('villas_header', collect())
        ->first();

    $experiencesHeader = $contents
        ->get('experiences_header', collect())
        ->first();

    $wellnessIntro = $contents
        ->get('wellness_intro', collect())
        ->first();

    $diningIntro = $contents
        ->get('dining_intro', collect())
        ->first();
    $longStay = $contents->get('long_stay', collect())->first();

    $longStayBooking = $contents
        ->get('long_stay_booking', collect())
        ->first();

    $longStayFeatures = $contents
        ->get('long_stay_features', collect());

    $longStayOptionsHeader = $contents
        ->get('long_stay_options_header', collect())
        ->first();

    $longStayOptions = $contents
        ->get('long_stay_options', collect());
    
    $aboutHero = $contents
    ->get('about_hero', collect())
    ->first();

    $aboutResort = $contents
        ->get('about_resort', collect())
        ->first();

    // Show only the original four Home page resort highlights.
    $homeResortTitles = [
        'boutique luxury', 'nature first',
        'heartfelt hospitality', 'legendary sunsets',
    ];
    $aboutValues = $contents
        ->get('about_values', collect())
        ->filter(fn ($item) => in_array(strtolower(trim((string) $item->title)), $homeResortTitles, true))
        ->sortBy(fn ($item) => array_search(strtolower(trim((string) $item->title)), $homeResortTitles, true))
        ->values();

    $aboutPrinciples = $contents
        ->get('about_principles', collect());
    
    $contactHero = $contents
    ->get('contact_hero', collect())
    ->first();

$contactInfo = $contents
    ->get('contact_info', collect());

$contactForm = $contents
    ->get('contact_form', collect())
    ->first();

$contactMap = $contents
    ->get('contact_map', collect())
    ->first();

$contactFooter = $contents
    ->get('contact_footer', collect())
    ->first();
@endphp


@include('layouts.header')




<main>


<!-- =========================================================
     HERO SECTION
========================================================= -->

@if($hero)

<section
    class="hero"
    style="background-image: url('{{ asset($hero->image) }}');"
>

    <div class="hero-overlay"></div>


    <div class="hero-content">

        <p class="eyebrow">

            {{ $site?->title }}

        </p>


        <div class="gold-line"></div>


        <h1>

            {{ $hero->title }}

        </h1>


        <h2>

            {{ $hero->subtitle }}

        </h2>


        <p class="hero-description">

            {{ $hero->description }}

        </p>


        <div class="hero-buttons">

            <a
                href="{{ $hero->button_url === '#booking' ? '#contact' : $hero->button_url }}"
                class="primary-button"
            >

                <span><x-glyph :value="$pick($hero, 4)" :em="1.1" /></span>

                {{ $hero->button_text }}

            </a>


            <a
                href="#about"
                class="secondary-button"
            >

                <span><x-glyph :value="$hl(1)?->icon" :em="1.1" /></span>

                {{ $hl(1)?->title }}

            </a>

        </div>

    </div>



    <!-- VIDEO / IMAGE BOX -->

    <div class="video-box">

        <div class="video-border">

            <button class="play-button">

                <x-glyph :value="$hl(5)?->icon" :em="1" />

            </button>

        </div>

    </div>



    <!-- =====================================================
         FEATURES
    ====================================================== -->

    <div class="features">

        @foreach($contents->get('features', collect()) as $feature)

            <div class="feature">

                @php
                    $featureIcons = [
                        1 => 'images/icons/cliff.png',
                        2 => 'images/icons/sunset.png',
                        3 => 'images/icons/villa.png',
                        4 => 'images/icons/ocean.png',
                        5 => 'images/icons/spa.png',
                        6 => 'images/icons/dining.png',
                    ];
                    // An icon uploaded in the admin (Home > Hero strip) wins over the built-in picture.
                    $featureIcon = \App\Models\HomeContent::customIconImage($feature->icon) ?? ($featureIcons[$loop->iteration] ?? null);
                @endphp

                <div class="feature-icon">

                    @if($featureIcon)
                        <img
                            src="{{ asset($featureIcon) }}"
                            alt=""
                            aria-hidden="true"
                            loading="lazy"
                        >
                    @else
                        <x-glyph :value="$feature->icon" :em="1.6" />
                    @endif

                </div>


                <div>

                    <strong>
                        {{ $feature->title }}
                    </strong>

                    <span>
                        {{ $feature->subtitle }}
                    </span>

                </div>

            </div>

        @endforeach

    </div>

</section>

@endif



<!-- =========================================================
     VILLAS SECTION
========================================================= -->

<section
    class="villas-section home-villas"
    id="villas"
>

    @if($villasHeader)

        <div class="villas-header">

            <p class="section-label">{{ $villasHeader->subtitle }}</p>

            <h2>{{ $villasHeader->title }}</h2>

            <x-rule class="home-rule" />

            <p class="section-subtitle">{{ $villasHeader->description }}</p>

        </div>

    @endif


    <div class="villa-container">

        @foreach($contents->get('villas', collect()) as $villa)

            @php
                $villaSlug   = \Illuminate\Support\Str::slug($villa->title);
                $homeVillaImage = $contents->get('home_villa_images', collect())
                    ->first(fn ($item) => \Illuminate\Support\Str::slug($item->title) === $villaSlug);
                $villaDetail = config("villas.{$villaSlug}", []);
                $villaUsd    = $villaDetail['usd'] ?? null;
                $cardIcons   = $villaDetail['card_icons'] ?? [];

                $villaFeatures = is_string($villa->features)
                    ? json_decode($villa->features, true)
                    : $villa->features;

                if (empty($villaFeatures)) {
                    $villaFeatures = $villaDetail['card_features'] ?? [];
                }

                $villaUrl = filled($villa->button_url) && $villa->button_url !== '#'
                    ? $villa->button_url
                    : route('villas.show', $villaSlug);
            @endphp

            <div class="villa-card">

                <img
                    src="{{ asset($homeVillaImage?->image ?: $villa->image) }}"
                    alt="{{ $villa->title }}"
                    class="villa-image"
                >

                <div class="villa-content">

                    <h3>
                        <img
                            class="villa-icon"
                            src="{{ asset(\App\Models\HomeContent::customIconImage(\App\Models\HomeContent::siteIcon('villa')) ?? 'images/icons/ico-lotus.png') }}"
                            alt=""
                            width="38"
                            height="38"
                        >
                        <span>{{ $villa->title }}</span>
                    </h3>

                    @if($villa->subtitle)
                        <p class="villa-tagline">{{ $villa->subtitle }}</p>
                    @endif

                    @if($villa->price)
                        <p class="villa-price">
                            From PHP {{ number_format($villa->price) }}
                            @if($villaUsd)
                                | approx. USD {{ $villaUsd }}
                            @endif
                            per night
                        </p>
                    @endif

                    @if(!empty($villaFeatures))
                        <ul class="villa-features">
                            @foreach($villaFeatures as $i => $feature)
                                <li>
                                    @php
                                        // The Home page has its own icon entries (Home page: Villa card feature icons),
                                        // separate from the Villas page. An uploaded image or an icon name replaces the built-in icon.
                                        $iconValue = trim((string) $contents->get('home_villa_feature_icons', collect())
                                            ->first(fn ($row) => \Illuminate\Support\Str::slug($row->title ?? '') === $villaSlug && (int) $row->sort_order === $i + 1)?->icon);
                                        $customCardIcon = \App\Models\HomeContent::customIconImage($iconValue);
                                        $iconName = ($customCardIcon || $iconValue === '') ? ($cardIcons[$i] ?? 'lotus') : $iconValue;
                                        $iconFile = $customCardIcon
                                            ?? (preg_match('/^[a-z0-9-]+$/', $iconName) ? 'images/icons/ico-' . $iconName . '.png' : null);
                                    @endphp
                                    @if($iconFile && file_exists(public_path($iconFile)))
                                        <img class="villa-feature-icon" src="{{ asset($iconFile) }}" alt="" width="30" height="30">
                                    @else
                                        <x-icon :name="$iconName" :size="30" />
                                    @endif
                                    <span>{{ $feature }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    <a href="{{ $villaUrl }}" class="villa-button">
                        <span aria-hidden="true"></span>
                        {{ $villa->button_text ?: 'View ' . $villa->title }}
                    </a>

                </div>

            </div>

        @endforeach

    </div>

    <x-rule class="home-rule home-rule--wide" />

</section>



<!-- =========================================================
     EXPERIENCES
========================================================= -->

<section
    class="experiences-section"
    id="experiences"
>


    @if($experiencesHeader)

        <div class="experiences-header">


            <p class="experiences-label">

                {{ $experiencesHeader->subtitle }}

            </p>


            <x-rule class="home-rule" />


            <h2>

                {{ $experiencesHeader->title }}

            </h2>


            <p class="experiences-subtitle">

                {{ $experiencesHeader->description }}

            </p>


        </div>

    @endif



    <div class="experience-container">


        @foreach($contents->get('experiences', collect()) as $experience)

            <div class="experience-card">


                <img
                    src="{{ asset($experience->image) }}"
                    alt="{{ $experience->title }}"
                >


                <div class="experience-overlay"></div>


                <div class="experience-content">

                    <h3>

                        {{ $experience->title }}

                    </h3>


                    <p>

                        {{ $experience->description }}

                    </p>

                </div>


            </div>

        @endforeach


    </div>



    @php

        $experienceBottom = $contents
            ->get('experiences_bottom', collect())
            ->first();

    @endphp


    @if($experienceBottom)

        <p class="experience-bottom-text">

            {{ $experienceBottom->description }}

        </p>


        <a
            href="{{ ($experienceBottom->button_url ?? '#') === '#' ? route('experiences.index') : $experienceBottom->button_url }}"
            class="discover-button"
        >

            {{ $experienceBottom->button_text }}

        </a>

    @endif


</section>



<!-- =========================================================
     WELLNESS
========================================================= -->

<section
    class="wellness-section"
    id="wellness"
>


    @if($wellnessIntro)

        <div class="wellness-main">


            <div class="wellness-intro">


                <p class="wellness-label">

                    {{ $wellnessIntro->subtitle }}

                </p>


                <h2>

                    {{ $wellnessIntro->title_html }}

                </h2>


                <p class="wellness-description">

                    {{ $wellnessIntro->description }}

                </p>


                <a
                    href="{{ ($wellnessIntro->button_url ?? '#') === '#' ? route('wellness.index') : $wellnessIntro->button_url }}"
                    class="wellness-button"
                >

                    <span aria-hidden="true"><img src="{{ asset('images/logo-badge.png') }}" alt="" class="cv-spa-logo"></span>

                    {{ $wellnessIntro->button_text }}

                </a>


            </div>



            <div class="wellness-main-image">

                <img
                    src="{{ asset($wellnessIntro->image) }}"
                    alt="{{ $wellnessIntro->title }}"
                >

            </div>


        </div>

    @endif



    <div class="wellness-cards">


        @foreach($contents->get('wellness', collect()) as $item)


            <div class="wellness-card">


                <div class="wellness-card-image">

                    @if($item->image)
                        <img
                            src="{{ asset($item->image) }}"
                            alt="{{ $item->display_title }}"
                        >
                    @endif

                </div>



                <div class="wellness-card-icon" aria-hidden="true">

                    @if($isImagePath($item->icon))
                        <x-icon :name="$item->icon" :size="48" />
                    @else
                    @switch((($loop->iteration - 1) % 3) + 1)

                        @case(1)
                            {{-- hands + flower --}}
                            <svg viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="34" cy="14" r="5"/>
                                <circle cx="25" cy="20" r="3.5"/>
                                <path d="M34 19v6M25 23.5V28"/>
                                <path d="M6 40c6-2 10 0 16 6 6 4 14 4 20 0 6-6 10-8 16-6"/>
                                <path d="M10 48c10 8 34 8 44 0"/>
                            </svg>
                            @break

                        @case(2)
                            {{-- lotus --}}
                            <svg viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M32 12c-7 8-7 20 0 28 7-8 7-20 0-28z"/>
                                <path d="M32 40C22 38 16 30 15 22c8 2 14 8 17 18z"/>
                                <path d="M32 40c10-2 16-10 17-18-8 2-14 8-17 18z"/>
                                <path d="M32 40C20 42 10 38 5 30c8-2 19 1 27 10z"/>
                                <path d="M32 40c12 2 22-2 27-10-8-2-19 1-27 10z"/>
                                <path d="M18 47c8 4 20 4 28 0"/>
                            </svg>
                            @break

                        @case(3)
                            {{-- meditating figure --}}
                            <svg viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="32" cy="22" r="5"/>
                                <path d="M32 27v13"/>
                                <path d="M32 30c-8 2-12 8-10 14M32 30c8 2 12 8 10 14"/>
                                <path d="M16 48c8-5 24-5 32 0-8 5-24 5-32 0z"/>
                                <path d="M32 6v5M20 10l3 4M44 10l-3 4M14 20h5M45 20h5"/>
                            </svg>
                            @break

                        @default
                            <x-glyph :value="$item->icon" :em="1.6" />

                    @endswitch
                    @endif

                </div>



                <div class="wellness-card-content">

                    <h3>

                        {{ $item->title }}

                    </h3>


                    <p>

                        {{ $item->description }}

                    </p>

                </div>


            </div>


        @endforeach


    </div>



    @php

        $wellnessQuote = $contents
            ->get('wellness_quote', collect())
            ->first();

    @endphp


    @if($wellnessQuote)

        <div class="wellness-quote">


            <p>

                {{ $wellnessQuote->description }}

            </p>


            <div class="wellness-quote-decoration">

                <span></span>

                @if(filled($pick($wellnessQuote, 10)))
                    <x-icon :name="$pick($wellnessQuote, 10)" :size="64" class="wellness-quote-lotus" style="object-fit:contain" />
                @else
                <svg class="wellness-quote-lotus" viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M32 8c-7 9-7 24 0 34 7-10 7-25 0-34z"/>
                    <path d="M32 42C21 40 14 31 13 21c9 2 16 9 19 21z"/>
                    <path d="M32 42c11-2 18-11 19-21-9 2-16 9-19 21z"/>
                    <path d="M32 42c-12 3-22-1-28-9 9-3 21 0 28 9z"/>
                    <path d="M32 42c12 3 22-1 28-9-9-3-21 0-28 9z"/>
                </svg>
                @endif

                <span></span>

            </div>


        </div>

    @endif


</section>



<!-- =========================================================
     DINING
========================================================= -->

<section
    class="dining-section"
    id="dining"
>


    @if($diningIntro)

        <div class="dining-main">


            <div class="dining-intro">


                <p class="dining-label">

                    {{ $diningIntro->subtitle }}

                </p>


                <h2>

                    {{ $diningIntro->title_html }}

                </h2>


                <p class="dining-description">

                    {{ $diningIntro->description }}

                </p>


                <a
                    href="{{ ($diningIntro->button_url ?? '#') === '#' ? route('dining.index') : $diningIntro->button_url }}"
                    class="dining-button"
                >

                    {{ $diningIntro->button_text }}

                </a>


            </div>



            <div class="dining-main-image">

                <img
                    src="{{ asset($diningIntro->image) }}"
                    alt="{{ $diningIntro->title }}"
                >

            </div>


        </div>

    @endif

 <x-rule class="home-rule" />

    <div class="dining-cards">


        @foreach($contents->get('dining', collect()) as $item)


            <div class="dining-card">


                <div class="dining-card-image">

                    <img
                        src="{{ asset($item->image) }}"
                        alt="{{ $item->title }}"
                    >

                </div>



                <div class="dining-card-content">


                    <h3>

                        {{ $item->title }}

                    </h3>


                    <p>

                        {{ $item->description }}

                    </p>


                </div>


            </div>


        @endforeach


    </div>



   @php
    $diningBottom = $contents
        ->get('dining_bottom', collect())
        ->first();
@endphp

@if($diningBottom)

    <div class="dining-decoration-2">

        <span></span>

        <p class="dining-bottom-text">
            {{ $diningBottom->description }}
        </p>

        <span></span>

    </div>

@endif
</section>

    <!-- =========================
        LONG STAY
    ========================== -->


<!-- =========================
     LONG STAY SECTION
========================== -->

<section class="long-stay-section" id="longstay">

    {{-- =========================================
         BOOK YOUR STAY BUTTON
    ========================================== --}}

    @if($longStayBooking)

        <a
            href="{{ in_array($longStayBooking->button_url ?? '#', ['#','#booking']) ? '#contact' : $longStayBooking->button_url }}"
            class="long-stay-booking"
        >

            <span class="long-stay-booking-icon" aria-hidden="true"><img src="{{ asset('images/logo-badge.png') }}" alt="" class="cv-booking-logo"></span>

            {{ $longStayBooking->button_text }}

        </a>

    @endif


    {{-- =========================================
         MAIN LONG STAY CONTENT
    ========================================== --}}

    @if($longStay)

        <div class="long-stay-content">

            {{-- TEXT --}}

            <div class="long-stay-text">

                {{-- LONG STAY LABEL --}}

                @if($longStay->subtitle)

                    <p class="long-stay-label">
                        {{ $longStay->subtitle }}
                    </p>

                @endif


                {{-- TITLE --}}

                @if($longStay->title)

                    <h2>
                        {{ $longStay->title_html }}
                    </h2>

                @endif


                {{-- DESCRIPTION --}}

                @if($longStay->description)

                    <p class="long-stay-description">
                        {{ $longStay->description }}
                    </p>

                @endif


                {{-- EXPLORE LONG STAYS BUTTON --}}

                @if($longStay->button_text)

                    <a
                        href="{{ $longStay->button_url ?? '#' }}"
                        class="long-stay-button"
                    >
                        {{ $longStay->button_text }}
                    </a>

                @endif

            </div>


            {{-- =========================================
                 LONG STAY IMAGE
            ========================================== --}}

            @if($longStay->image)

                <div class="long-stay-image">

                    <img
                        src="{{ asset($longStay->image) }}"
                        alt="{{ $longStay->title }}"
                    >

                </div>

            @endif

        </div>

    @endif


    {{-- =========================================
         LONG STAY FEATURES
    ========================================== --}}

    @if($longStayFeatures->count())

        <div class="long-stay-features">

            @foreach($longStayFeatures as $feature)

                <div class="long-stay-feature">

                    {{-- ICON --}}

                    <div class="long-stay-feature-icon">
                        <x-glyph :value="$feature->icon" :em="1.6" />
                    </div>


                    {{-- TITLE --}}

                    <div class="long-stay-feature-title">
                        {{ $feature->title_html }}
                    </div>

                </div>

            @endforeach

        </div>

    @endif

</section>


<!-- =========================
     LONG STAY OPTIONS SECTION
========================== -->

<section
    class="long-stay-options-section"
    id="long-stay-options"
>

    {{-- =========================================
         OPTIONS HEADING
    ========================================== --}}

    <div class="long-stay-options-header">

        @if(isset($longStayOptionsHeader) && $longStayOptionsHeader)

            <h2>
                {{ $longStayOptionsHeader->title }}
            </h2>

        @else

            <h2>
                {{ $hl(3)?->title }}
            </h2>

        @endif

    </div>


    {{-- =========================================
         OPTIONS CARDS
    ========================================== --}}

    @if($longStayOptions->count())

        <div class="long-stay-options-grid">

            @foreach($longStayOptions as $option)

                <div class="long-stay-option-card">

                    {{-- ICON --}}

                    <div class="long-stay-option-icon">

                        <x-glyph :value="$option->icon" :em="1.6" />

                    </div>


                    {{-- TITLE --}}

                    <h3>
                        {{ $option->title_html }}
                    </h3>

                </div>

            @endforeach

        </div>

    @endif
</section>
<!-- =========================================================
     ABOUT CASA VERDE
========================================================= -->

<section class="about-section" id="about">


    {{-- =====================================================
         ABOUT HERO
    ====================================================== --}}

    @if($aboutHero)

        <div class="about-hero">

            {{-- BACKGROUND IMAGE --}}

            @if($aboutHero->image)

                <img
                    src="{{ asset($aboutHero->image) }}"
                    alt="{{ $aboutHero->title }}"
                    class="about-hero-image"
                >

            @endif


            {{-- DARK OVERLAY --}}

            <div class="about-hero-overlay"></div>


            {{-- TEXT --}}

            <div class="about-hero-content">

                @if($aboutHero->subtitle)

                    <p class="about-hero-label">
                        {{ $aboutHero->subtitle }}
                    </p>

                @endif


                @if($aboutHero->title)

                    <h2>
                        {{ $aboutHero->title_html }}
                    </h2>

                @endif


                @if($aboutHero->description)

                    <p class="about-hero-description">
                        {{ $aboutHero->description }}
                    </p>

                @endif

            </div>

        </div>

    @endif


    {{-- =====================================================
         OUR RESORT
    ====================================================== --}}

    @if($aboutResort)

        <div class="about-resort">


            {{-- LEFT SIDE --}}

            <div class="about-resort-left">

                @if($aboutResort->subtitle)

                    <p class="about-resort-label">
                        {{ $aboutResort->subtitle }}
                    </p>

                @endif


                @if($aboutResort->title)

                    <h2>
                        {{ $aboutResort->title }}
                    </h2>

                @endif


                @if($aboutResort->description)

                    <p class="about-resort-description">
                        {{ $aboutResort->description }}
                    </p>

                @endif


                {{-- VALUES --}}

                @if($aboutValues->count())

                    <div class="about-values">

                        @foreach($aboutValues as $value)

                            <div class="about-value">

                                <div class="about-value-icon">
                                    <x-glyph :value="$value->icon" :em="1.6" />
                                </div>

                                <div class="about-value-title">
                                    {{ $value->title }}
                                </div>

                            </div>

                        @endforeach

                    </div>

                @endif

            </div>


            {{-- CENTER IMAGE --}}

            @if($aboutResort->image)

                <div class="about-resort-image">

                    <img
                        src="{{ asset($aboutResort->image) }}"
                        alt="{{ $aboutResort->title }}"
                    >

                </div>

            @endif


            {{-- RIGHT PRINCIPLES --}}

            @if($aboutPrinciples->count())

                <div class="about-principles">

                    @foreach($aboutPrinciples as $principle)

                        <div class="about-principle">

                            <div class="about-principle-icon">
                                <x-glyph :value="$principle->icon" :em="1.6" />
                            </div>

                            <div class="about-principle-title">
                                {{ $principle->title }}
                            </div>

                        </div>

                    @endforeach

                </div>

            @endif

        </div>

    @endif

</section>
<!-- =========================================================
     CONTACT US
========================================================= -->

<section class="contact-section" id="contact">


    {{-- =====================================================
         CONTACT HERO
    ====================================================== --}}

    @if($contactHero)

        <div class="contact-hero">

            @if($contactHero->image)

                <img
                    src="{{ asset($contactHero->image) }}"
                    alt="{{ $contactHero->title }}"
                    class="contact-hero-image"
                >

            @endif


            <div class="contact-hero-overlay"></div>


            <div class="contact-hero-content">

                @if($contactHero->subtitle)

                    <p class="contact-hero-label">
                        {{ $contactHero->subtitle }}
                    </p>

                @endif


                @if($contactHero->title)

                    <h2>
                        {{ $contactHero->title_html }}
                    </h2>

                @endif


                @if($contactHero->description)

                    <p class="contact-hero-description">
                        {{ $contactHero->description }}
                    </p>

                @endif

            </div>

        </div>

    @endif


    {{-- =====================================================
         CONTACT INFORMATION AREA
    ====================================================== --}}

    <div class="contact-main">


        {{-- =================================================
             GET IN TOUCH
        ================================================== --}}

        <div class="contact-information">

            <h3>
                {{ $hl(2)?->title }}
            </h3>


            @if($contactInfo->count())

                <div class="contact-info-list">

                    @foreach($contactInfo as $info)
                        @php
                            $t = strtolower($info->title);
                            $infoIcon = str_contains($t, 'location') ? 'pin' : (str_contains($t, 'phone') || str_contains($t, 'whatsapp') ? 'phone' : (str_contains($t, 'mail') ? 'mail' : 'clock'));
                        @endphp

                        <div class="contact-info-item">

                            <div class="contact-info-icon">
                                <x-icon :name="$infoIcon" :size="24" />
                            </div>


                            <div class="contact-info-content">

                                <strong>
                                    {{ $info->title }}
                                </strong>


                                <p>
                                    {!! nl2br(e($info->description)) !!}
                                </p>

                            </div>

                        </div>

                    @endforeach

                </div>

            @endif

        </div>


        {{-- =================================================
             SEND MESSAGE
        ================================================== --}}

        @if($contactForm)

            <div class="contact-form-wrapper">

                <h3>
                    {{ $contactForm->title }}
                </h3>


                <form
                    class="contact-form"
                    action="{{ route('contact.store') }}#contact"
                    method="POST"
                >

                    @csrf

                    @if(session('contact_status'))
                        <p class="form-alert form-alert--ok" role="status">{{ session('contact_status') }}</p>
                    @endif
                    @if($errors->any())
                        <p class="form-alert form-alert--error" role="alert">{{ $errors->first() }}</p>
                    @endif


                    <div class="contact-form-row">

                        <input
                            type="text"
                            name="name" value="{{ old('name') }}"
                            placeholder="Full Name"
                        >


                        <input
                            type="email"
                            name="email" value="{{ old('email') }}"
                            placeholder="Email Address"
                        >

                    </div>


                    <input
                        type="text"
                        name="subject" value="{{ old('subject') }}"
                        placeholder="Subject"
                    >


                    <textarea
                        name="message"
                        placeholder="Your Message"
                    >{{ old('message') }}</textarea>


                    <button type="submit">
                        {{ $contactForm->button_text }}
                    </button>

                </form>


                @if($contactForm->description)

                    <p class="contact-form-note">
                        {{ $contactForm->description }}
                    </p>

                @endif

            </div>

        @endif


        {{-- =================================================
             FIND US
        ================================================== --}}

        @if($contactMap)

            <div class="contact-map-wrapper">

                <h3>
                    {{ $contactMap->title }}
                </h3>


                <div class="contact-map">

                    <img
                        src="{{ asset($contactMap->image) }}"
                        alt="Casa Verde location map"
                    >


                    {{-- MAP MARKER --}}

                    <div class="contact-map-marker">
                        <x-icon :name="\App\Models\HomeContent::siteIcon('pin')" :size="18" />
                    </div>

                </div>

            </div>

        @endif

    </div>


</section>
</main>

@include('layouts.footer', ['hideCta' => true])

</body>
</html>