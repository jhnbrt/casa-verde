<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Experiences | Casa Verde Cliff Resort & Spa</title>

    @vite([
        'resources/css/header.css',
        'resources/css/experience.css',
        'resources/css/theme.css',
        'resources/css/footer.css',
        'resources/js/app.js'
    ])

    <style>
        /* Experiences page – matched to the design (inline so it shows without a rebuild;
           can be moved into resources/css/experience.css) */

        /* Hero */
        .experience-page .experience-hero {
            grid-template-columns: 38% minmax(0, 1fr);
            gap: 24px;
            padding: 96px 44px 18px;
            align-items: center;
        }
        .experience-page .hero-introduction { padding-left: 22px; }
        .experience-page .hero-label {
            font-family: var(--cv-sans, sans-serif);
            font-size: 1.05rem;
            letter-spacing: .3em;
            color: #dfa562;
            margin: 0 0 10px;
        }
        .experience-page .hero-introduction h1 {
            font-family: var(--cv-serif, serif);
            font-size: clamp(2rem, 3.1vw, 2.9rem);
            font-weight: 500;
            line-height: 1.12;
            color: #10231f;
            white-space: nowrap;
        }
        /* theme.css styles .hero-description for the dark home hero (cream text + shadow);
           reset it here so it reads on the cream background */
        .experience-page .hero-description {
            font-family: var(--cv-sans, sans-serif);
            font-size: 1.02rem;
            line-height: 1.5;
            max-width: 420px;
            margin-top: 18px;
            color: #1d2b27;
            text-shadow: none;
        }
        .experience-page .hero-image { height: 195px; border-radius: 18px; }

        /* Section headings */
        .experience-page .experiences-area { padding: 0 44px 14px; }
        .experience-page .section-heading { gap: 24px; margin: 0 0 16px; }
        .experience-page .section-heading h2 {
            font-family: var(--cv-serif, serif);
            font-size: 1.3rem;
            letter-spacing: .04em;
            color: #dfa562;
        }

        /* Signature cards */
        .experience-page .signature-grid { gap: 50px; }
        .experience-page .signature-card { border: 0; background: transparent; overflow: visible; }
        .experience-page .signature-image { height: 96px; border-radius: 16px 16px 0 0; }
        .experience-page .signature-title {
            height: 40px;
            border: 1px solid #e3b078;
            font-family: var(--cv-serif, serif);
            font-size: 1.45rem;
            color: #10231f;
        }

        /* More experiences */
        .experience-page .more-experiences { padding-bottom: 18px; }
        .experience-page .more-grid { gap: 22px; align-items: stretch; }
        .experience-page .more-card { border: 0; background: transparent; overflow: visible; display: flex; flex-direction: column; }
        .experience-page .more-image { flex: none; }
        .experience-page .more-image { height: 94px; border-radius: 16px 16px 0 0; }
        .experience-page .more-content {
            border: 1px solid #e3b078;
            padding: 10px 4px 10px;
            flex: 1;
        }
        .experience-page .more-content h3 {
            font-family: var(--cv-serif, serif);
            font-size: 1.15rem;
            font-weight: 700;
            color: #10231f;
            margin-bottom: 4px;
            white-space: nowrap;
        }
        .experience-page .more-content p {
            font-family: var(--cv-serif, serif);
            font-size: 1rem;
            color: #3a4540;
        }

        /* Concierge bar */
        .experience-page .concierge-bar { min-height: 78px; padding: 8px 42px; gap: 26px; background: #06211b; }
        .experience-page .concierge-item { gap: 16px; border-right: 1px solid #c58f55; min-width: 0; }
        .experience-page .concierge-item p br { display: none; }
        .experience-page .concierge-icon { width: 52px; height: 52px; border: 0; color: #f0e6d6; }
        .experience-page .concierge-item:first-child .concierge-icon { color: #e0a366; }
        .experience-page .concierge-icon svg { stroke-width: 1.2; }
        .experience-page .concierge-item h3 {
            font-family: var(--cv-serif, serif);
            font-size: 1.25rem;
            font-weight: 600;
            color: #fff;
            margin-bottom: 3px;
            white-space: nowrap;
        }
        .experience-page .concierge-item p {
            font-family: var(--cv-serif, serif);
            font-size: .98rem;
            line-height: 1.25;
            color: #e8e0d2;
        }
        .experience-page .plan-button {
            gap: 8px;
            padding: 6px 28px 6px 6px;
            border-radius: 40px;
            background: #dca265;
            font-family: var(--cv-sans, sans-serif);
            font-size: 1rem;
            letter-spacing: .02em;
        }
        .experience-page .plan-button .plan-logo {
            width: 48px; height: 48px;
            border: 1px solid rgba(255,255,255,.3);
            background: #06211b;
        }
        .experience-page .plan-logo img { width: 30px; height: 30px; object-fit: contain; }

        @media (max-width: 1100px) {
            .experience-page .more-grid { grid-template-columns: repeat(4, 1fr); }
            .experience-page .hero-introduction h1 { white-space: normal; }
            .experience-page .concierge-item { border-right: 0; }
            .experience-page .more-content h3,
            .experience-page .concierge-item h3 { white-space: normal; }
        }
        @media (max-width: 768px) {
            .experience-page .experience-hero { grid-template-columns: 1fr; padding: 96px 20px 20px; }
            .experience-page .hero-introduction { padding-left: 0; }
            .experience-page .experiences-area { padding: 0 20px 20px; }
            .experience-page .signature-grid { grid-template-columns: 1fr; gap: 18px; }
            .experience-page .signature-image { height: 160px; }
            .experience-page .more-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
            .experience-page .more-image { height: 110px; }
            .experience-page .concierge-bar { flex-direction: column; align-items: stretch; padding: 20px; }
            .experience-page .concierge-item { border-bottom: 1px solid rgba(197,143,85,.6); padding: 10px 0 14px; }
            .experience-page .plan-button { justify-content: center; }
        }
    </style>

</head>

<body>

@include('layouts.header')


<main class="experience-page">


<!-- =========================================================
     HERO
========================================================= -->

<section class="experience-hero">

    <div class="hero-introduction">

        <p class="hero-label">
            EXPERIENCES
        </p>

        <h1>
            Moments that make<br>
            your stay unforgettable.
        </h1>

        <p class="hero-description">
            Every day at Casa Verde invites you to slow down,
            explore and connect with the island
        </p>

    </div>


    <div class="hero-image">

        <img
            src="{{ asset('images/experiences/hero.jpg') }}"
            alt="Sunset experience"
        >

    </div>

</section>



<!-- =========================================================
     SIGNATURE EXPERIENCES
========================================================= -->

<section class="experiences-area">

    <div class="section-heading">

        <span></span>

        <h2>
            SIGNATURE EXPERIENCES
        </h2>

        <span></span>

    </div>


    <div class="signature-grid">


        <!-- CLIFFSIDE JACUZZI -->

        <article class="signature-card">

            <div class="signature-image">

                <img
                    src="{{ asset('images/experiences/cliffside-jacuzzi.jpg') }}"
                    alt="Cliffside Jacuzzi"
                >

            </div>

            <div class="signature-title">
                Cliffside Jacuzzi
            </div>

        </article>



        <!-- HIDDEN CAVE -->

        <article class="signature-card">

            <div class="signature-image">

                <img
                    src="{{ asset('images/experiences/hidden-cave.jpg') }}"
                    alt="Hidden Cave"
                >

            </div>

            <div class="signature-title">
                Hidden Cave
            </div>

        </article>



        <!-- INFINITY POOL -->

        <article class="signature-card">

            <div class="signature-image">

                <img
                    src="{{ asset('images/experiences/infinity-pool.jpg') }}"
                    alt="Infinity Pool"
                >

            </div>

            <div class="signature-title">
                Infinity Pool
            </div>

        </article>

    </div>

</section>



<!-- =========================================================
     MORE EXPERIENCES
========================================================= -->

<section class="experiences-area more-experiences">

    <div class="section-heading">

        <span></span>

        <h2>
            MORE EXPERIENCES
        </h2>

        <span></span>

    </div>


    <div class="more-grid">


        <!-- PADEL -->

        <article class="more-card">

            <div class="more-image">

                <img
                    src="{{ asset('images/experiences/padel.jpg') }}"
                    alt="Padel and Pickleball"
                >

            </div>

            <div class="more-content">

                <h3>
                    Padel & Pickleball
                </h3>

                <p>
                    Play with a sea view.
                </p>

            </div>

        </article>



        <!-- BILLIARDS -->

        <article class="more-card">

            <div class="more-image">

                <img
                    src="{{ asset('images/experiences/billiards.jpg') }}"
                    alt="Billiards Lounge"
                >

            </div>

            <div class="more-content">

                <h3>
                    Billiards Lounge
                </h3>

                <p>
                    Relax and unwind.
                </p>

            </div>

        </article>



        <!-- ISLAND HOPPING -->

        <article class="more-card">

            <div class="more-image">

                <img
                    src="{{ asset('images/experiences/island-hopping.jpg') }}"
                    alt="Island Hopping"
                >

            </div>

            <div class="more-content">

                <h3>
                    Island Hopping
                </h3>

                <p>
                    Discover nearby islands.
                </p>

            </div>

        </article>



        <!-- SNORKELING -->

        <article class="more-card">

            <div class="more-image">

                <img
                    src="{{ asset('images/experiences/snorkeling.jpg') }}"
                    alt="Snorkeling"
                >

            </div>

            <div class="more-content">

                <h3>
                    Snorkeling
                </h3>

                <p>
                    Explore vibrant reefs.
                </p>

            </div>

        </article>



        <!-- SUNSET RITUALS -->

        <article class="more-card">

            <div class="more-image">

                <img
                    src="{{ asset('images/experiences/sunset-rituals.jpg') }}"
                    alt="Sunset Rituals"
                >

            </div>

            <div class="more-content">

                <h3>
                    Sunset Rituals
                </h3>

                <p>
                    End the day mindfully.
                </p>

            </div>

        </article>



        <!-- FITNESS -->

        <article class="more-card">

            <div class="more-image">

                <img
                    src="{{ asset('images/experiences/open-air-fitness.jpg') }}"
                    alt="Open-Air Fitness"
                >

            </div>

            <div class="more-content">

                <h3>
                    Open-Air Fitness
                </h3>

                <p>
                    Move with nature.
                </p>

            </div>

        </article>



        <!-- YOGA -->

        <article class="more-card">

            <div class="more-image">

                <img
                    src="{{ asset('images/experiences/yoga.jpg') }}"
                    alt="Yoga Sessions"
                >

            </div>

            <div class="more-content">

                <h3>
                    Yoga Sessions
                </h3>

                <p>
                    Balance body and mind.
                </p>

            </div>

        </article>

    </div>

</section>



<!-- =========================================================
     BOTTOM CONCIERGE BAR
========================================================= -->

<section class="concierge-bar">


    <!-- CONCIERGE PLANNING -->

    <div class="concierge-item">

        <div class="concierge-icon">
            <x-icon name="cloche" :size="46" />
        </div>

        <div>

            <h3>
                Concierge Planning
            </h3>

            <p>
                Personalized experiences<br>
                crafted just for you.
            </p>

        </div>

    </div>



    <!-- WHATSAPP -->

    <div class="concierge-item">

        <div class="concierge-icon whatsapp">
            <x-icon name="whatsapp" :size="46" />
        </div>

        <div>

            <h3>
                WhatsApp Concierge
            </h3>

            <p>
                Chat with us anytime for instant<br>
                assistance.
            </p>

        </div>

    </div>



    <!-- RESERVATION -->

    <div class="concierge-item">

        <div class="concierge-icon">
            <x-icon name="calendar" :size="46" />
        </div>

        <div>

            <h3>
                Activities by Reservation
            </h3>

            <p>
                Thoughtfully planned for a seamless<br>
                experience.
            </p>

        </div>

    </div>



    <!-- BUTTON -->

    <a href="{{ url('/') }}#contact" class="plan-button">

        <span class="plan-logo"><img src="{{ asset($site->image ?? 'images/logo.png') }}" alt="" width="34" height="34"></span>

        PLAN YOUR EXPERIENCES

    </a>

</section>


</main>

    @include('layouts.footer')

</body>

</html>