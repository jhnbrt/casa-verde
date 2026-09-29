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

</head>

<body>

@include('layouts.header')


<main>


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
            ♧
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
            ◉
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
            ▦
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

        <span>
            ✧
        </span>

        PLAN YOUR EXPERIENCES

    </a>

</section>


</main>

    @include('layouts.footer')

</body>

</html>