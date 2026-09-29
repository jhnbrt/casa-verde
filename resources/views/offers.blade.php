<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Special Offers | Casa Verde Cliff Resort &amp; Spa</title>
    @vite([
        'resources/css/header.css',
        'resources/css/pages.css',
        'resources/css/theme.css',
        'resources/css/footer.css',
        'resources/js/app.js'
    ])
</head>
<body>
    @include('layouts.header')
    @php $book = url('/') . '#contact'; @endphp

    <main class="cv-page">
        <section class="cv-section">
            <span class="cv-eyebrow">Special Offers</span>
            <div class="cv-rule"><i></i><span>✿</span><i></i></div>
            <h1 class="cv-title">Stay a little longer.<br>Experience a little more.</h1>
            <p class="cv-lead">Exclusive offers designed to help you relax deeper, explore more, and create unforgettable memories at Casa Verde Cliff Resort &amp; Spa.</p>

            <div class="cv-grid">
                @foreach([
                    ['Stay 3, Pay 2', '🏝️', 'The more nights you stay, the more you save. Unwind longer in paradise.', '1 NIGHT FREE', 'when you stay 2 nights or more', 'images/experiences/sunset-rituals.jpg'],
                    ['Wellness Escape', '🪷', 'Restore your body and mind with a rejuvenating retreat designed for total well-being.', '15% OFF SPA', 'treatments and wellness experiences', 'images/wellness/package.jpg'],
                    ['Romantic Getaway', '♡', 'Celebrate love with special touches and unforgettable moments for two.', 'ROMANTIC DINNER', 'and amenities included', 'images/dining3.jpg'],
                ] as [$name, $icon, $text, $perk, $perkText, $photo])
                    <article class="cv-card">
                        <img src="{{ asset($photo) }}" alt="{{ $name }}" loading="lazy">
                        <div class="cv-ico" aria-hidden="true">{{ $icon }}</div>
                        <h3>{{ $name }}</h3>
                        <p>{{ $text }}</p>
                        <div class="cv-offer-perk"><b>{{ $perk }}</b> {{ $perkText }}</div>
                        <div><a href="{{ $book }}" class="cv-btn">View offer</a></div>
                    </article>
                @endforeach
            </div>

            <div class="cv-note"><i></i><span>Book direct for our best available benefits.</span><i></i></div>
        </section>
    </main>

    @include('layouts.footer')
</body>
</html>
