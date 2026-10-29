<!DOCTYPE html>
<html lang="en">

<head>

    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v=20261017" sizes="any">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=20261017">
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
        'resources/css/footer.css','resources/css/rule.css',
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

    @php
        $pc = fn (string $section) => \App\Models\HomeContent::pageRows($contents, $section);
        $img = fn (?string $path, string $fallback = 'images/main.jpg') => asset($path && file_exists(public_path($path)) ? $path : $fallback);
        $href = fn (?string $url) => \Illuminate\Support\Str::startsWith($url ?? '', ['http', '#', 'mailto:', 'tel:']) ? $url : url($url ?: '/#contact');
        $lines = fn (?string $text) => array_pad(explode("\n", str_replace("\r", '', trim((string) $text)), 2), 2, '');
        $hero = $pc('wellness_page_hero')->first();
        $perks = $pc('wellness_page_perks');
        $menu = $pc('wellness_page_menu')->first();
    @endphp

<main class="wellness-page">

    {{-- Hero --}}
    <section class="wellness-hero">
        <div class="wellness-introduction">
            <p class="wellness-label">{{ $hero?->subtitle }}</p>
            <h1>{!! nl2br(e($hero?->title)) !!}</h1>
            <p class="wellness-description">{{ $hero?->description }}</p>
        </div>

        <div class="wellness-hero-image">
            <img src="{{ $img($hero?->image, 'images/wellness1.jpg') }}" alt="{{ trim(strtok((string) $hero?->title, "\n")) }}">
        </div>
    </section>

    {{-- Treatments --}}
    <section class="wellness-services">
        @foreach($pc('wellness_page_treatments') as $treatment)
            <article class="wellness-card">
                <div class="wellness-card-image">
                    <img src="{{ $img($treatment->image, 'images/wellness2.jpg') }}" alt="{{ $treatment->title }}">
                </div>
                <div class="wellness-card-content">
                    <h2>{{ $treatment->title }}</h2>
                    <p>{{ $treatment->description }}</p>
                </div>
            </article>
        @endforeach
    </section>

    {{-- Highlights and spa menu button --}}
    <section class="wellness-features">
        @foreach($perks as $item)
            @if($loop->index === 2 && $menu && filled($menu->button_text))
                <div class="feature-action">
                    <a href="{{ $href($menu->button_url ?: '#') }}" class="spa-menu-button">{{ $menu->button_text }}</a>
                </div>
            @endif

            <div class="feature-item">
                @if(filled($item->icon))
                    <div class="feature-icon">
                        <x-icon :name="$item->icon" :size="48" />
                    </div>
                @endif
                <div>
                    <h3>{{ $item->title }}</h3>
                    <p>{!! nl2br(e($item->description)) !!}</p>
                </div>
            </div>
        @endforeach

        @if($perks->count() <= 2 && $menu && filled($menu->button_text))
            <div class="feature-action">
                <a href="{{ $href($menu->button_url ?: '#') }}" class="spa-menu-button">{{ $menu->button_text }}</a>
            </div>
        @endif
    </section>

</main>

    @include('layouts.footer')

</body>

</html>