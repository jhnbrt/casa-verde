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

                    <div class="villa-image-wrapper">

                        <img
                            src="{{ asset($villa->image) }}"
                            alt="{{ $villa->title }}"
                            class="villa-image"
                        >

                    </div>


                    {{-- CONTENT --}}

                    <div class="villa-content">

                        <h2>

                            <span class="villa-symbol">
                                ✧
                            </span>

                            {{ $villa->title }}

                        </h2>


                        @if($villa->subtitle)

                            <p class="villa-tagline">

                                {{ $villa->subtitle }}

                            </p>

                        @endif


                        @if($villa->price)

                            <p class="villa-price">

                                From PHP

                                <strong>
                                    {{ number_format($villa->price) }}
                                </strong>

                                <span>
                                    / night
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

                                @foreach($villaFeatures as $feature)

                                    <li>

                                        <span class="feature-icon">
                                            ♧
                                        </span>

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

                            <span>
                                ✧
                            </span>

                            {{ $villa->button_text ?? 'VIEW VILLA' }}

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

            <div class="bottom-intro">

                <span class="bottom-icon">
                    ⚑
                </span>

                <div>

                    <h3>
                        Not sure which villa is
                        right for you?
                    </h3>

                    <p>
                        We're happy to help you choose
                        the perfect stay.
                    </p>

                </div>

            </div>


            <div class="bottom-divider"></div>


            <div class="bottom-feature">

                <span class="bottom-icon">
                    ♧
                </span>

                <p>
                    Comfortable spaces designed for
                    relaxed island living.
                </p>

            </div>


            <div class="bottom-divider"></div>


            <div class="bottom-feature">

                <span class="bottom-icon">
                    ✧
                </span>

                <p>
                    Enjoy a peaceful private stay
                    surrounded by nature.
                </p>

            </div>


            <a
                href="{{ url('/#contact') }}"
                class="bottom-book-button"
            >

                <span>✧</span>

                BOOK YOUR STAY

            </a>

        </div>

    </section>

</main>

    @include('layouts.footer')

</body>
</html>