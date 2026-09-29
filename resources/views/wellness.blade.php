<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Wellness | Casa Verde Cliff Resort & Spa</title>

    @vite([
        'resources/css/header.css',
        'resources/css/wellness.css',
        'resources/css/theme.css',
        'resources/css/footer.css',
        'resources/js/app.js'
    ])

</head>

<body>

@include('layouts.header')

<main>


<!-- =========================================================
     WELLNESS HERO
========================================================= -->

<section class="wellness-hero">


    <!-- LEFT CONTENT -->

    <div class="wellness-introduction">

        <p class="wellness-label">
            WELLNESS
        </p>


        <h1>
            Relax. Rejuvenate.<br>
            Reconnect with yourself.
        </h1>


        <p class="wellness-description">

            At Casa Verde Cliff Resort & Spa, wellness is more than a
            treatment - it's a way of life. Let our spa experiences
            restore your body, calm your mind and uplift your spirit.

        </p>

    </div>


    <!-- RIGHT IMAGE -->

    <div class="wellness-hero-image">

        <img
            src="{{ asset('images/wellness/hero.jpg') }}"
            alt="Wellness Spa"
        >

    </div>

</section>



<!-- =========================================================
     WELLNESS SERVICES
========================================================= -->

<section class="wellness-services">


    <!-- =====================================================
         CARD 1
    ====================================================== -->

    <article class="wellness-card">

        <div class="wellness-card-image">

            <img
                src="{{ asset('images/wellness/banana.jpg') }}"
                alt="Banana Scanning Signature Massage"
            >

            <div class="service-icon">
                ♡
            </div>

        </div>


        <div class="wellness-card-content">

            <h2>
                Banana Scanning Signature Massage
            </h2>

            <p>
                Our signature blend of massage designed to release
                tension and promote deep relaxation.
            </p>

        </div>

    </article>



    <!-- =====================================================
         CARD 2
    ====================================================== -->

    <article class="wellness-card">

        <div class="wellness-card-image">

            <img
                src="{{ asset('images/wellness/hot-stone.jpg') }}"
                alt="Hot Stone Massage"
            >

            <div class="service-icon">
                ≋
            </div>

        </div>


        <div class="wellness-card-content">

            <h2>
                Hot Stone Massage
            </h2>

            <p>
                Warm volcanic stones melt away stress and improve
                circulation and balance.
            </p>

        </div>

    </article>



    <!-- =====================================================
         CARD 3
    ====================================================== -->

    <article class="wellness-card">

        <div class="wellness-card-image">

            <img
                src="{{ asset('images/wellness/therapeutic.jpg') }}"
                alt="Therapeutic Massage"
            >

            <div class="service-icon">
                ◇
            </div>

        </div>


        <div class="wellness-card-content">

            <h2>
                Therapeutic Massage
            </h2>

            <p>
                Essential oils combined with soothing touch to calm
                your mind and restore harmony.
            </p>

        </div>

    </article>



    <!-- =====================================================
         CARD 4
    ====================================================== -->

    <article class="wellness-card">

        <div class="wellness-card-image">

            <img
                src="{{ asset('images/wellness/foot.jpg') }}"
                alt="Foot Massage"
            >

            <div class="service-icon">
                ♧
            </div>

        </div>


        <div class="wellness-card-content">

            <h2>
                Foot Massage
            </h2>

            <p>
                Relieve tired feet and improve circulation with a
                relaxing reflexology massage.
            </p>

        </div>

    </article>



    <!-- =====================================================
         CARD 5
    ====================================================== -->

    <article class="wellness-card">

        <div class="wellness-card-image">

            <img
                src="{{ asset('images/wellness/package.jpg') }}"
                alt="Wellness Package"
            >

            <div class="service-icon">
                ✧
            </div>

        </div>


        <div class="wellness-card-content">

            <h2>
                Wellness Package
            </h2>

            <p>
                Choose from carefully curated package for the
                ultimate wellness journey.
            </p>

        </div>

    </article>


</section>



<!-- =========================================================
     WELLNESS FEATURES / BOTTOM
========================================================= -->

<section class="wellness-features">


    <!-- PEACEFUL SANCTUARY -->

    <div class="feature-item">

        <div class="feature-icon">
            ✧
        </div>

        <div>

            <h3>
                Peaceful Sanctuary
            </h3>

            <p>
                A serene spa environment<br>
                designed for total relaxation.
            </p>

        </div>

    </div>



    <!-- NATURAL INGREDIENTS -->

    <div class="feature-item">

        <div class="feature-icon">
            ♧
        </div>

        <div>

            <h3>
                Natural Ingredients
            </h3>

            <p>
                We use high-quality, natural<br>
                products for your well-being.
            </p>

        </div>

    </div>



    <!-- EXPLORE BUTTON -->

    <div class="feature-action">

        <a
            href="#"
            class="spa-menu-button"
        >

            EXPLORE SPA MENU

        </a>

    </div>



    <!-- ADVANCE BOOKING -->

    <div class="feature-item">

        <div class="feature-icon">
            ▦
        </div>

        <div>

            <h3>
                Advance Booking
            </h3>

            <p>
                We recommend booking your<br>
                spa treatment in advance.
            </p>

        </div>

    </div>



    <!-- WELL-BEING -->

    <div class="feature-item">

        <div class="feature-icon">
            ♢
        </div>

        <div>

            <h3>
                Your Well-being Matters
            </h3>

            <p>
                Our therapists are dedicated<br>
                to your comfort and care.
            </p>

        </div>

    </div>


</section>


</main>

    @include('layouts.footer')

</body>

</html>