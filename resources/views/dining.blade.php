<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dining | Casa Verde Cliff Resort & Spa</title>

    @vite([
        'resources/css/header.css',
        'resources/css/dining.css',
        'resources/css/theme.css',
        'resources/css/footer.css',
        'resources/js/app.js'
    ])

    <style>
        /* Dining page – matched to the design (inline so it shows without a rebuild;
           can be moved into resources/css/dining.css) */
        .dining-page { padding-top: 96px; background: #f7f2ea; overflow: visible; }

        /* Hero */
        .dining-page .dining-hero {
            grid-template-columns: 39% 61%;
            min-height: 250px;
            border-bottom: 1px solid #ddd5c8;
            background: #f7f2ea;
        }
        .dining-page .dining-hero-content {
            padding: 20px 4% 20px 7%;
            background:
                radial-gradient(ellipse 60% 70% at 12% 18%, rgba(70, 60, 40, .07), transparent 70%),
                #f7f2ea;
        }
        .dining-page .dining-label {
            align-self: flex-start;
            font-family: var(--cv-serif, serif);
            font-size: 1.15rem;
            font-weight: 600;
            letter-spacing: .02em;
            margin: 0 0 4px;
        }
        .dining-page .dining-label::after { width: 100%; height: 1.5px; margin-top: 2px; }
        .dining-page .dining-hero h1 {
            font-family: var(--cv-serif, serif);
            font-size: clamp(1.9rem, 2.9vw, 2.7rem);
            font-weight: 600;
            letter-spacing: .03em;
            line-height: 1.1;
            white-space: nowrap;
        }
        .dining-page .dining-hero h2 {
            margin: 2px 0 16px;
            font-family: var(--cv-serif, serif);
            font-style: italic;
            font-weight: 400;
            font-size: clamp(1.5rem, 2.4vw, 2.2rem);
            letter-spacing: .05em;
            line-height: 1.15;
            color: #e0a366;
        }
        .dining-page .dining-hero p {
            font-family: var(--cv-serif, serif);
            font-size: 1.05rem;
            line-height: 1.5;
            max-width: 390px;
            margin-left: 4px;
            color: #2f403b;
        }
        .dining-page .dining-hero-image { min-height: 250px; }

        /* Features */
        .dining-page .dining-features { gap: 16px; padding: 12px 3% 20px; }
        .dining-page .dining-feature { display: flex; flex-direction: column; align-items: center; }
        .dining-page .feature-icon {
            width: 54px; height: 54px; margin-bottom: 12px;
            border: 1px solid #e3b078; color: #dba46a;
        }
        .dining-page .feature-icon svg { stroke-width: 1; }
        .dining-page .dining-feature h3 {
            font-family: var(--cv-serif, serif);
            font-size: 1.05rem;
            font-weight: 700;
            letter-spacing: 0;
            margin-bottom: 6px;
        }
        .dining-page .feature-description {
            font-family: var(--cv-serif, serif);
            font-size: 1.05rem;
            line-height: 1.45;
            min-height: 46px;
            max-width: 250px;
            margin: 0 auto 12px;
        }
        .dining-page .dining-feature img {
            width: 76%;
            max-width: 210px;
            height: auto;
            aspect-ratio: 188 / 158;
            margin: auto auto 0;
            border-radius: 22px;
            box-shadow: none;
        }

        /* Bottom row */
        .dining-page .dining-bottom { gap: 20px; padding: 8px 4% 28px; grid-template-columns: 1fr 1.9fr 1fr; }
        .dining-page .bottom-icon { min-width: 56px; color: #dba46a; }
        .dining-page .bottom-icon svg { stroke-width: 1; }
        .dining-page .bottom-info h3 {
            font-family: var(--cv-serif, serif);
            font-size: 1.1rem;
            font-weight: 700;
        }
        .dining-page .bottom-info p {
            font-family: var(--cv-serif, serif);
            font-size: 1.05rem;
            line-height: 1.4;
        }
        .dining-page .dining-buttons { gap: 46px; }
        .dining-page .menu-button {
            min-width: 258px;
            padding: 20px 28px;
            border-radius: 44px;
            background: #06211b;
            color: #dca265;
            font-family: var(--cv-serif, serif);
            font-size: 1.3rem;
            font-weight: 600;
            letter-spacing: .02em;
        }

        @media (max-width: 1100px) {
            .dining-page .dining-hero { grid-template-columns: 1fr 1fr; }
            .dining-page .dining-hero h1,
            .dining-page .dining-hero h2 { white-space: normal; }
            .dining-page .dining-features { grid-template-columns: repeat(3, 1fr); }
            .dining-page .dining-bottom { grid-template-columns: 1fr; }
        }
        @media (max-width: 700px) {
            .dining-page { padding-top: 76px; }
            .dining-page .dining-hero { grid-template-columns: 1fr; }
            .dining-page .dining-hero-content { padding: 32px 24px; }
            .dining-page .dining-features { grid-template-columns: 1fr 1fr; }
            .dining-page .dining-feature img { width: 90%; }
            .dining-page .menu-button { min-width: 0; width: 100%; max-width: 320px; }
        }
    </style>
</head>

<body>

    @include('layouts.header')


    <main class="dining-page">

        {{-- =========================================
             HERO SECTION
        ========================================== --}}
        <section class="dining-hero">

            <div class="dining-hero-content">

                <span class="dining-label">
                    DINING
                </span>

                <h1>
                    TASTE THE ISLAND.
                </h1>

                <h2>
                    Every meal becomes a memory.
                </h2>

                <p>
                    Savor a curated dining experience inspired by fresh local
                    ingredients, international flavors and breathtaking views.
                    From sunrise breakfast to sunset cocktails, every moment is
                    crafted to delight.
                </p>

            </div>

            <div class="dining-hero-image">
                <img
                    src="{{ asset('images/dining/dining-hero.jpg') }}"
                    alt="Casa Verde Dining"
                >
            </div>

        </section>


        {{-- =========================================
             DINING FEATURES
        ========================================== --}}
        <section class="dining-features">

            {{-- 1. ASIAN & WESTERN --}}
            <div class="dining-feature">

                <div class="feature-icon">
                    <x-icon name="cloche" :size="34" />
                </div>

                <h3>
                    ASIAN & WESTERN CUISINE
                </h3>

                <p class="feature-description">
                    Thoughtfully crafted dishes made with passion and precision.
                </p>

                <img
                    src="{{ asset('images/dining/asian-western.jpg') }}"
                    alt="Asian and Western Cuisine"
                >

            </div>


            {{-- 2. COCKTAILS --}}
            <div class="dining-feature">

                <div class="feature-icon">
                    <x-icon name="cocktail" :size="34" />
                </div>

                <h3>
                    SIGNATURE COCKTAILS
                </h3>

                <p class="feature-description">
                    Creative blends and tropical favorites to complement every moment.
                </p>

                <img
                    src="{{ asset('images/dining/signature-cocktails.jpg') }}"
                    alt="Signature Cocktails"
                >

            </div>


            {{-- 3. SEAFOOD --}}
            <div class="dining-feature">

                <div class="feature-icon">
                    <x-icon name="fish" :size="34" />
                </div>

                <h3>
                    FRESH SEAFOOD
                </h3>

                <p class="feature-description">
                    Daily catch and freshest seafood, prepared to perfection.
                </p>

                <img
                    src="{{ asset('images/dining/fresh-seafood.jpg') }}"
                    alt="Fresh Seafood"
                >

            </div>


            {{-- 4. SUNSET --}}
            <div class="dining-feature">

                <div class="feature-icon">
                    <x-icon name="sunset" :size="34" />
                </div>

                <h3>
                    SUNSET DINNERS
                </h3>

                <p class="feature-description">
                    Unforgettable sunsets paired with exquisite dining experiences.
                </p>

                <img
                    src="{{ asset('images/dining/sunset-dinners.jpg') }}"
                    alt="Sunset Dinner"
                >

            </div>


            {{-- 5. BREAKFAST --}}
            <div class="dining-feature">

                <div class="feature-icon">
                    <x-icon name="coffee" :size="34" />
                </div>

                <h3>
                    BREAKFAST
                </h3>

                <p class="feature-description">
                    Wholesome and delicious start to your day, every day.
                </p>

                <img
                    src="{{ asset('images/dining/breakfast.jpg') }}"
                    alt="Breakfast"
                >

            </div>

        </section>


        {{-- =========================================
             BOTTOM INFORMATION
        ========================================== --}}
        <section class="dining-bottom">

            <div class="bottom-info">

                <div class="bottom-icon">
                    <x-icon name="cutlery" :size="46" />
                </div>

                <div>
                    <h3>
                        EXCEPTIONAL CUISINE
                    </h3>

                    <p>
                        A culinary journey that celebrates local flavors
                        and global inspiration.
                    </p>
                </div>

            </div>


            <div class="dining-buttons">

                <a href="#" class="menu-button">
                    EXPLORE FOOD MENU
                </a>

                <a href="#" class="menu-button">
                    EXPLORE DRINKS MENU
                </a>

            </div>


            <div class="bottom-info">

                <div class="bottom-icon">
                    <x-icon name="wine" :size="46" />
                </div>

                <div>
                    <h3>
                        PERFECT PAIRINGS
                    </h3>

                    <p>
                        From fine wines to handcrafted cocktails,
                        find your perfect match.
                    </p>
                </div>

            </div>

        </section>

    </main>

    @include('layouts.footer')

</body>
</html>