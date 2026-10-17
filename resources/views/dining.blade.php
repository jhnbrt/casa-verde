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


        @php
        $pc = fn (string $section) => \App\Models\HomeContent::pageRows($contents, $section);
        $img = fn (?string $path, string $fallback = 'images/main.jpg') => asset($path && file_exists(public_path($path)) ? $path : $fallback);
        $href = fn (?string $url) => \Illuminate\Support\Str::startsWith($url ?? '', ['http', '#', 'mailto:', 'tel:']) ? $url : url($url ?: '/#contact');
        $lines = fn (?string $text) => array_pad(explode("\n", str_replace("\r", '', trim((string) $text)), 2), 2, '');
        $hero = $pc('dining_page_hero')->first();
        [$heroTitle, $heroLine] = $lines($hero?->title);
        $info = $pc('dining_page_info');
        $menus = $pc('dining_page_menus');
    @endphp

    <main class="dining-page">

        {{-- Hero --}}
        <section class="dining-hero">
            <div class="dining-hero-content">
                <span class="dining-label">{{ $hero?->subtitle }}</span>
                <h1>{{ $heroTitle }}</h1>
                @if($heroLine !== '')
                    <h2>{{ $heroLine }}</h2>
                @endif
                <p>{{ $hero?->description }}</p>
            </div>

            <div class="dining-hero-image">
                <img src="{{ $img($hero?->image, 'images/dining1.jpg') }}" alt="{{ trim($heroTitle) }}">
            </div>
        </section>

        {{-- Dining highlights --}}
        <section class="dining-features">
            @foreach($pc('dining_page_features') as $feature)
                <div class="dining-feature">
                    @if(filled($feature->icon))
                        <div class="feature-icon">
                            <x-icon :name="$feature->icon" :size="34" />
                        </div>
                    @endif

                    <h3>{{ $feature->title }}</h3>
                    <p class="feature-description">{{ $feature->description }}</p>

                    <img src="{{ $img($feature->image, 'images/dining2.jpg') }}" alt="{{ $feature->title }}">
                </div>
            @endforeach
        </section>

        {{-- Bottom information --}}
        <section class="dining-bottom">
            @if($left = $info->get(0))
                <div class="bottom-info">
                    @if(filled($left->icon))
                        <div class="bottom-icon"><x-icon :name="$left->icon" :size="46" /></div>
                    @endif
                    <div>
                        <h3>{{ $left->title }}</h3>
                        <p>{{ $left->description }}</p>
                    </div>
                </div>
            @endif

            <div class="dining-buttons">
                @foreach($menus as $menu)
                    @if(filled($menu->button_text))
                        <a href="{{ $href($menu->button_url ?: '#') }}" class="menu-button">{{ $menu->button_text }}</a>
                    @endif
                @endforeach
            </div>

            @if($right = $info->get(1))
                <div class="bottom-info">
                    @if(filled($right->icon))
                        <div class="bottom-icon"><x-icon :name="$right->icon" :size="46" /></div>
                    @endif
                    <div>
                        <h3>{{ $right->title }}</h3>
                        <p>{{ $right->description }}</p>
                    </div>
                </div>
            @endif
        </section>

    </main>

    @include('layouts.footer')

</body>
</html>