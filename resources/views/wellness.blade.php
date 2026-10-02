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

    <style>
/* =========================================================
   HERO – photo blended into the text, kept inside the container
   (scoped to .wellness-page so it wins over theme.css)
========================================================= */

.wellness-page .wellness-hero {
    position: relative;
    display: block;
    max-width: 1440px;
    margin: 0 auto;
    min-height: 365px;
    padding-top: 96px;
    overflow: hidden;
    isolation: isolate;
    background: var(--cream);
}

.wellness-page .wellness-hero-image {
    position: absolute;
    top: 96px;
    right: 0;
    bottom: 0;
    z-index: 0;
    width: 64%;
    height: auto;
    overflow: hidden;
    border-radius: 0;
    box-shadow: none;
    -webkit-mask-image: linear-gradient(to right, transparent 0, rgba(0, 0, 0, .5) 18%, #000 46%);
            mask-image: linear-gradient(to right, transparent 0, rgba(0, 0, 0, .5) 18%, #000 46%);
}

.wellness-page .wellness-hero-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: 60% 40%;
}

.wellness-page .wellness-introduction {
    position: relative;
    z-index: 1;
    width: min(56%, 640px);
    min-height: 269px;
    padding: 24px 30px 24px clamp(24px, 3.4vw, 47px);
}

.wellness-page .wellness-label {
    font-family: var(--cv-serif, serif);
    font-size: 1.1rem;
    letter-spacing: .04em;
    text-transform: uppercase;
}

.wellness-page .wellness-introduction h1 {
    font-family: var(--cv-serif, serif);
    font-size: clamp(2rem, 3.2vw, 3rem);
    font-weight: 600;
    line-height: 1.04;
    color: var(--cv-forest, #06211b);
}

.wellness-page .wellness-description {
    font-family: var(--cv-serif, serif);
    font-size: 1.15rem;
    line-height: 1.45;
    max-width: 430px;
    color: #31443f;
}

/* Cards + feature strip: serif type like the design */
.wellness-page .wellness-card-content h2,
.wellness-page .feature-item h3 {
    font-family: var(--cv-serif, serif);
    font-size: 1.05rem;
    font-weight: 700;
}

.wellness-page .wellness-card-content p,
.wellness-page .feature-item p {
    font-family: var(--cv-serif, serif);
    font-size: .98rem;
    line-height: 1.35;
}

.wellness-page .spa-menu-button {
    font-family: var(--cv-serif, serif);
    font-size: 1rem;
    letter-spacing: .04em;
}

@media (max-width: 1100px) {
    .wellness-page .wellness-introduction { width: min(60%, 640px); }
    .wellness-page .wellness-hero-image { width: 60%; }
}

@media (max-width: 768px) {
    .wellness-page .wellness-hero { min-height: 0; padding-top: 76px; }
    .wellness-page .wellness-introduction { width: 100%; min-height: 0; padding: 28px 24px 150px; }
    .wellness-page .wellness-hero-image {
        top: auto;
        left: 0;
        right: 0;
        bottom: 0;
        width: 100%;
        height: 320px;
        -webkit-mask-image: linear-gradient(to bottom, transparent 0, rgba(0, 0, 0, .55) 14%, #000 42%);
                mask-image: linear-gradient(to bottom, transparent 0, rgba(0, 0, 0, .55) 14%, #000 42%);
    }
}


/* Line icons (replace the text glyphs) */
.wellness-page .feature-icon svg { stroke-width: 1.2; }

    </style>

</head>

<body>

@include('layouts.header')

<main class="wellness-page">


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
            <x-icon name="lotus" :size="34" />
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
            <x-icon name="leaf" :size="34" />
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
            <x-icon name="calendar" :size="34" />
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
            <x-icon name="shield" :size="34" />
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