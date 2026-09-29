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
                    🍽
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
                    🍸
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
                    🐟
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
                    🌅
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
                    ☕
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
                    🍴
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
                    🍷
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