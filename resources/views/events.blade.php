<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events | Casa Verde Cliff Resort &amp; Spa</title>
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
    @php
        $img = fn (string $path, string $fallback) => asset(file_exists(public_path($path)) ? $path : $fallback);
        $book = url('/') . '#contact';
    @endphp

    <main class="cv-page">

        <section class="cv-hero" style="background-image:url('{{ $img('images/events/hero.jpg', 'images/main.jpg') }}')">
            <span class="cv-eyebrow">Events at Casa Verde</span>
            <h1>Gather. Celebrate. Remember.</h1>
            <p>Meaningful occasions, framed by sea, sky and the warmth of island hospitality.</p>
            <div><a href="{{ $book }}" class="cv-btn cv-btn--gold">Plan your event</a></div>
        </section>

        <section class="cv-section">
            <span class="cv-eyebrow">Your moment, your way</span>
            <div class="cv-rule"><i></i><span>✿</span><i></i></div>
            <h2 class="cv-title">Extraordinary settings for unforgettable gatherings.</h2>
            <p class="cv-lead">Exclusive offers designed to help you relax deeper, explore more, and create unforgettable memories at Casa Verde Cliff Resort &amp; Spa.</p>

            <div class="cv-grid">
                @foreach([
                    ['Intimate Weddings', '💍', 'Romantic and intimate ceremonies with breathtaking ocean views.', 'images/events/wedding.jpg', 'images/exp1.jpg'],
                    ['Private Celebrations', '🎂', 'Birthdays, anniversaries and life’s special moments, made memorable.', 'images/events/celebration.jpg', 'images/dining3.jpg'],
                    ['Retreats & Gatherings', '🪷', 'Inspiring spaces for wellness retreats, team offsites and meaningful getaways.', 'images/events/retreat.jpg', 'images/experiences/yoga.jpg'],
                ] as [$name, $icon, $text, $photo, $fallback])
                    <article class="cv-card">
                        <img src="{{ $img($photo, $fallback) }}" alt="{{ $name }}" loading="lazy">
                        <div class="cv-ico" aria-hidden="true">{{ $icon }}</div>
                        <h3>{{ $name }}</h3>
                        <p>{{ $text }}</p>
                        <a class="cv-link" href="{{ $book }}">Discover more</a>
                    </article>
                @endforeach
            </div>
        </section>

        <section class="cv-dark cv-section">
            <h2>Everything, beautifully considered.</h2>
            <div class="cv-feats">
                @foreach([
                    ['Cliffside Venues', 'Spectacular settings with unrivalled views.'],
                    ['Thoughtful Menus', 'Seasonal, locally inspired culinary experiences.'],
                    ['Personal Planning', 'Dedicated support to bring your vision to life.'],
                    ['Island Hospitality', 'Warm, intuitive service rooted in care.'],
                ] as [$t, $d])
                    <div><h3>{{ $t }}</h3><p>{{ $d }}</p></div>
                @endforeach
            </div>
        </section>

        <section class="cv-split">
            <img src="{{ $img('images/events/setup.jpg', 'images/dining2.jpg') }}" alt="Cliffside event table setting" loading="lazy">
            <div>
                <span class="cv-eyebrow">Made personal</span>
                <div class="cv-rule"><i></i><span>✿</span><i></i></div>
                <h2>From first idea<br>to final toast.</h2>
                <p>Our events team is here to listen, guide and craft every detail seamlessly and with heart, so you can be fully present in the moments that matter.</p>
                <a href="{{ $book }}" class="cv-btn">Start planning</a>
            </div>
        </section>

        <section class="cv-banner" style="background-image:url('{{ $img('images/events/cta.jpg', 'images/main.jpg') }}')">
            <h2>Let’s create something unforgettable.</h2>
            <div class="cv-row">
                <a href="{{ $book }}" class="cv-btn cv-btn--gold">Send an enquiry</a>
                <a href="{{ $book }}" class="cv-btn">Contact our team</a>
            </div>
        </section>
    </main>

    @include('layouts.footer')
</body>
</html>
