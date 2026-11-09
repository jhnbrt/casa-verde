<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v=20261017" sizes="any">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=20261017">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yoga Reset | Casa Verde Cliff Resort &amp; Spa</title>
    @vite([
        'resources/css/header.css',
        'resources/css/pages.css',
        'resources/css/yoga.css',
        'resources/css/theme.css',
        'resources/css/footer.css','resources/css/rule.css',
        'resources/js/app.js'
    ])
</head>
<body>
    @include('layouts.header')
    @php
        $img = fn (?string $path, string $fallback = 'images/main.jpg') => asset($path && file_exists(public_path($path)) ? $path : $fallback);
        $href = fn (?string $url) => \Illuminate\Support\Str::startsWith($url ?? '', ['http', '#', 'mailto:', 'tel:']) ? $url : url($url ?: '/#contact');
        $logo = \App\Models\HomeContent::siteLogo();
    @endphp

    <main class="cv-page yg">

        {{-- Hero --}}
        <section class="yg-hero" style="background-image:url('{{ $img($page['hero']['image']) }}')">
            <div class="yg-hero__copy">
                <span class="yg-eyebrow">{{ $page['hero']['eyebrow'] }}</span>
                <h1>{{ $page['hero']['title'] }}</h1>
                <p>{{ $page['hero']['text'] }}</p>
                <div class="yg-actions">
                    @if($page['hero']['button_text'])
                        <a href="{{ $href($page['hero']['button_url']) }}" class="yg-pill">
                            <span class="yg-pill__logo"><img src="{{ asset($logo) }}" alt="" width="30" height="30"></span>
                            <span>{{ $page['hero']['button_text'] }}</span>
                        </a>
                    @endif
                    @if(count($page['schedule']['items']))
                        <a href="#schedule" class="yg-ghost">See the daily rhythm</a>
                    @endif
                </div>
            </div>
        </section>

        {{-- Quick facts --}}
        @if(count($page['stats']))
            <section class="yg-stats" aria-label="At a glance">
                @foreach($page['stats'] as $stat)
                    <div class="yg-stat">
                        @if($stat['icon'])<span class="yg-stat__ico"><x-icon :name="$stat['icon']" :size="28" /></span>@endif
                        <strong>{{ $stat['title'] }}</strong>
                        <small>{{ $stat['eyebrow'] }}</small>
                    </div>
                @endforeach
            </section>
        @endif

        {{-- Intro --}}
        <section class="yg-intro">
            <span class="yg-eyebrow yg-eyebrow--dark">{{ $page['intro']['eyebrow'] }}</span>
            <x-rule />
            <h2>{{ $page['intro']['title'] }}</h2>
            <p>{{ $page['intro']['text'] }}</p>
        </section>

        {{-- Sessions --}}
        <section class="yg-sessions">
            @foreach($page['types'] as $type)
                <article class="yg-card">
                    <div class="yg-card__photo">
                        <img src="{{ $img($type['image'], 'images/experiences/yoga.jpg') }}" alt="{{ $type['title'] }}" loading="lazy">
                        @if($type['icon'])
                            <span class="yg-card__ico"><x-icon :name="$type['icon']" :size="30" /></span>
                        @endif
                    </div>
                    <div class="yg-card__body">
                        @if($type['eyebrow'])
                            <ul class="yg-chips">
                                @foreach(array_filter(array_map('trim', preg_split('/[·|]/u', $type['eyebrow']))) as $chip)
                                    <li>{{ $chip }}</li>
                                @endforeach
                            </ul>
                        @endif
                        <h3>{{ $type['title'] }}</h3>
                        <p>{{ $type['text'] }}</p>
                        @if($type['button_text'])
                            <a class="yg-link" href="{{ $href($type['button_url']) }}">{{ $type['button_text'] }} <span aria-hidden="true">→</span></a>
                        @endif
                    </div>
                </article>
            @endforeach
        </section>

        {{-- Daily rhythm --}}
        @if(count($page['schedule']['items']))
            <section class="yg-schedule" id="schedule">
                <span class="yg-eyebrow yg-eyebrow--dark">{{ $page['schedule']['eyebrow'] }}</span>
                <h2>{{ $page['schedule']['title'] }}</h2>
                <ol class="yg-timeline">
                    @foreach($page['schedule']['items'] as $slot)
                        <li>
                            <time>{{ $slot['title'] }}</time>
                            <div>
                                <h3>{{ $slot['eyebrow'] }}</h3>
                                @if($slot['text'])<p>{{ $slot['text'] }}</p>@endif
                            </div>
                        </li>
                    @endforeach
                </ol>
            </section>
        @endif

        {{-- Benefits --}}
        <section class="yg-benefits">
            <h2>{{ $page['features']['title'] }}</h2>
            <x-rule />
            <div class="yg-benefits__grid">
                @foreach($page['features']['items'] as $feature)
                    <div>
                        @if($feature['icon'])
                            <span class="yg-benefits__ico"><x-icon :name="$feature['icon']" :size="40" /></span>
                        @endif
                        <h3>{{ $feature['title'] }}</h3>
                        <p>{{ $feature['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- Personal --}}
        <section class="yg-split">
            <img src="{{ $img($page['personal']['image'], 'images/experiences/yoga.jpg') }}" alt="{{ $page['personal']['title'] }}" loading="lazy">
            <div>
                <span class="yg-eyebrow yg-eyebrow--dark">{{ $page['personal']['eyebrow'] }}</span>
                <x-rule />
                <h2>{{ $page['personal']['title'] }}</h2>
                <p>{{ $page['personal']['text'] }}</p>
                @if($page['personal']['button_text'])
                    <a href="{{ $href($page['personal']['button_url']) }}" class="cv-btn">{{ $page['personal']['button_text'] }}</a>
                @endif
            </div>
        </section>

        {{-- Guest words --}}
        @if($page['quote']['text'])
            <section class="yg-quote">
                <blockquote>
                    <p>“{{ $page['quote']['text'] }}”</p>
                    <footer>
                        <strong>{{ $page['quote']['title'] }}</strong>
                        @if($page['quote']['eyebrow'])<span>{{ $page['quote']['eyebrow'] }}</span>@endif
                    </footer>
                </blockquote>
            </section>
        @endif

        {{-- Closing banner --}}
        <section class="yg-banner" style="background-image:url('{{ $img($page['cta']['image']) }}')">
            <h2>{{ $page['cta']['title'] }}</h2>
            <div class="yg-actions">
                @foreach($page['cta']['buttons'] as $button)
                    <a href="{{ $href($button['url']) }}" class="cv-btn {{ $loop->first ? 'cv-btn--gold' : '' }}">{{ $button['text'] }}</a>
                @endforeach
            </div>
        </section>
    </main>

    @include('layouts.footer')
</body>
</html>
