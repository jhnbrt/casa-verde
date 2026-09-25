<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Casa Verde Cliff Resort & Spa</title>

    @vite([
        'resources/css/home.css',
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

    $aboutValues = $contents
        ->get('about_values', collect());

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


<!-- =========================================================
     NAVIGATION
========================================================= -->

<header class="navbar">

    <div class="logo-area">

        @if($site)

            <img
                src="{{ asset($site->image) }}"
                alt="{{ $site->title }}"
            >

            <div class="logo-text">

                <span>
                    {{ $site->title }}
                </span>

                <small>
                    {{ $site->subtitle }}
                </small>

            </div>

        @endif

    </div>


    <nav class="nav-links">

        @foreach($contents->get('navigation', collect()) as $nav)

            <a href="{{ $nav->button_url }}">
                {{ $nav->title }}
            </a>

        @endforeach

    </nav>


    <a href="#booking" class="book-button">

        <span class="book-icon">✧</span>

        BOOK YOUR STAY

    </a>

</header>



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
                href="{{ $hero->button_url }}"
                class="primary-button"
            >

                <span>✧</span>

                {{ $hero->button_text }}

            </a>


            <a
                href="#about"
                class="secondary-button"
            >

                <span>✧</span>

                EXPLORE THE RESORT

            </a>

        </div>

    </div>



    <!-- VIDEO / IMAGE BOX -->

    <div class="video-box">

        <div class="video-border">

            <button class="play-button">

                ▶

            </button>

        </div>

    </div>



    <!-- =====================================================
         FEATURES
    ====================================================== -->

    <div class="features">

        @foreach($contents->get('features', collect()) as $feature)

            <div class="feature">

                <div class="feature-icon">

                    {{ $feature->icon }}

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
    class="villas-section"
    id="villas"
>

    @if($villasHeader)

        <div class="villas-header">

            <p class="section-label">

                {{ $villasHeader->subtitle }}

            </p>


            <h2>

                {{ $villasHeader->title }}

            </h2>


            <p class="section-subtitle">

                {{ $villasHeader->description }}

            </p>

        </div>

    @endif



    <div class="villa-container">


        @foreach($contents->get('villas', collect()) as $villa)

            <div class="villa-card">


                <img
                    src="{{ asset($villa->image) }}"
                    alt="{{ $villa->title }}"
                    class="villa-image"
                >


                <div class="villa-content">


                    <h3>

                        <span class="villa-icon">✧</span>

                        {{ $villa->title }}

                    </h3>


                    <p class="villa-tagline">

                        {{ $villa->subtitle }}

                    </p>


                    <p class="villa-price">

                        From PHP
                        {{ number_format($villa->price) }}

                    </p>

                  @if($villa->features)

    @php
        $villaFeatures = is_string($villa->features)
            ? json_decode($villa->features, true)
            : $villa->features;
    @endphp

    <ul class="villa-features">

        @foreach($villaFeatures ?? [] as $feature)

            <li>

                <span>♧</span>

                {{ $feature }}

            </li>

        @endforeach

    </ul>

@endif

                    <a
                        href="{{ $villa->button_url ?? '#' }}"
                        class="villa-button"
                    >

                        <span>✧</span>

                        {{ $villa->button_text }}

                    </a>


                </div>

            </div>

        @endforeach


    </div>


    <div class="section-divider"></div>

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


            <div class="experience-decoration">

                <span></span>

                <div class="experience-symbol">

                    ✧

                </div>

                <span></span>

            </div>


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
            href="{{ $experienceBottom->button_url ?? '#' }}"
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

                    {!! nl2br(e($wellnessIntro->title)) !!}

                </h2>


                <p class="wellness-description">

                    {{ $wellnessIntro->description }}

                </p>


                <a
                    href="{{ $wellnessIntro->button_url ?? '#' }}"
                    class="wellness-button"
                >

                    <span>✧</span>

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

                    <img
                        src="{{ asset($item->image) }}"
                        alt="{{ $item->title }}"
                    >

                </div>



                <div class="wellness-card-icon">

                    {{ $item->icon }}

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

                <b>✧</b>

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

                    {!! nl2br(e($diningIntro->title)) !!}

                </h2>


                <p class="dining-description">

                    {{ $diningIntro->description }}

                </p>


                <a
                    href="{{ $diningIntro->button_url ?? '#' }}"
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



    <div class="dining-decoration">

        <span></span>

        <b>✧</b>

        <span></span>

    </div>



    @php

        $diningBottom = $contents
            ->get('dining_bottom', collect())
            ->first();

    @endphp


    @if($diningBottom)

        <p class="dining-bottom-text">

            {{ $diningBottom->description }}

        </p>

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
            href="{{ $longStayBooking->button_url ?? '#' }}"
            class="long-stay-booking"
        >

            <span class="long-stay-booking-icon">
                ◈
            </span>

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
                        {!! nl2br(e($longStay->title)) !!}
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
                        {{ $feature->icon }}
                    </div>


                    {{-- TITLE --}}

                    <div class="long-stay-feature-title">
                        {!! nl2br(e($feature->title)) !!}
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
                Designed for the way you want to live.
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

                        {{ $option->icon }}

                    </div>


                    {{-- TITLE --}}

                    <h3>
                        {!! nl2br(e($option->title)) !!}
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
                        {!! nl2br(e($aboutHero->title)) !!}
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
                                    {{ $value->icon }}
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
                                {{ $principle->icon }}
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
                        {!! nl2br(e($contactHero->title)) !!}
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
                GET IN TOUCH
            </h3>


            @if($contactInfo->count())

                <div class="contact-info-list">

                    @foreach($contactInfo as $info)

                        <div class="contact-info-item">

                            <div class="contact-info-icon">
                                {{ $info->icon }}
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
                    action="{{ $contactForm->button_url ?? '#' }}"
                    method="POST"
                >

                    @csrf


                    <div class="contact-form-row">

                        <input
                            type="text"
                            name="name"
                            placeholder="Full Name"
                        >


                        <input
                            type="email"
                            name="email"
                            placeholder="Email Address"
                        >

                    </div>


                    <input
                        type="text"
                        name="subject"
                        placeholder="Subject"
                    >


                    <textarea
                        name="message"
                        placeholder="Your Message"
                    ></textarea>


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
                        ◈
                    </div>

                </div>

            </div>

        @endif

    </div>


    {{-- =====================================================
         FOOTER
    ====================================================== --}}

    @if($contactFooter)

        <div class="contact-footer">


            {{-- LOGO --}}

            <div class="contact-footer-logo">

                @if($contactFooter->image)

                    <img
                        src="{{ asset($contactFooter->image) }}"
                        alt="Casa Verde Cliff Resort & Spa"
                    >

                @endif

            </div>


            {{-- CENTER TEXT --}}

            <div class="contact-footer-title">

                <span></span>

                <p>
                    {{ $contactFooter->title }}
                </p>

                <span></span>

            </div>


            {{-- SOCIAL MEDIA --}}

            <div class="contact-socials">

                <a href="#" aria-label="Facebook">
                    f
                </a>

                <a href="#" aria-label="Instagram">
                    ◎
                </a>

                <a href="#" aria-label="YouTube">
                    ▶
                </a>

            </div>

        </div>

    @endif

</section>
</main>
</body>
</html>