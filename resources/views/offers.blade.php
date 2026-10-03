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
    @php
        $img = fn (?string $path) => asset($path && file_exists(public_path($path)) ? $path : 'images/main.jpg');
        $href = fn (?string $url) => \Illuminate\Support\Str::startsWith($url ?? '', ['http', '#', 'mailto:', 'tel:']) ? $url : url($url ?: '/#contact');
    @endphp

    <main class="cv-page">
        <section class="cv-section">
            <span class="cv-eyebrow">{{ $page['header']['eyebrow'] }}</span>
            <div class="cv-rule"><i></i><span>✿</span><i></i></div>
            <h1 class="cv-title">{!! nl2br(e($page['header']['title'])) !!}</h1>
            <p class="cv-lead">{{ $page['header']['text'] }}</p>

            <div class="cv-grid">
                @foreach($page['items'] as $offer)
                    <article class="cv-card">
                        <img src="{{ $img($offer['image']) }}" alt="{{ $offer['title'] }}" loading="lazy">
                        @if($offer['icon'])
                            <div class="cv-ico" aria-hidden="true">{{ $offer['icon'] }}</div>
                        @endif
                        <h3>{{ $offer['title'] }}</h3>
                        <p>{{ $offer['text'] }}</p>
                        @if($offer['perk'])
                            <div class="cv-offer-perk"><b>{{ $offer['perk'] }}</b> {{ $offer['perk_text'] }}</div>
                        @endif
                        <div><a href="{{ $href($offer['button_url']) }}" class="cv-btn">{{ $offer['button_text'] }}</a></div>
                    </article>
                @endforeach
            </div>

            @if($page['note'])
                <div class="cv-note"><i></i><span>{{ $page['note'] }}</span><i></i></div>
            @endif
        </section>
    </main>

    @include('layouts.footer')
</body>
</html>
