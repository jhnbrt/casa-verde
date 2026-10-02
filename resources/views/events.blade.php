<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events | Casa Verde Cliff Resort &amp; Spa</title>
    @vite([
        'resources/css/header.css',
        'resources/css/pages.css',
        'resources/css/events.css',
        'resources/css/theme.css',
        'resources/css/footer.css',
        'resources/js/app.js'
    ])
</head>
<body>
    @include('layouts.header')
    @php
        $img = fn (?string $path, string $fallback = 'images/main.jpg') => asset($path && file_exists(public_path($path)) ? $path : $fallback);
        $href = fn (?string $url) => \Illuminate\Support\Str::startsWith($url ?? '', ['http', '#', 'mailto:', 'tel:']) ? $url : url($url ?: '/#contact');
        $logo = $site->image ?? 'images/logo.png';
    @endphp

    <main class="cv-page ev">

        {{-- Hero --}}
        <section class="cv-hero ev-hero" style="background-image:url('{{ $img($page['hero']['image']) }}')">
            <span class="cv-eyebrow ev-hero__eyebrow">{{ $page['hero']['eyebrow'] }}</span>
            <h1>{{ $page['hero']['title'] }}</h1>
            <p>{{ $page['hero']['text'] }}</p>
            @if($page['hero']['button_text'])
                <div>
                    <a href="{{ $href($page['hero']['button_url']) }}" class="ev-pill">
                        <span class="ev-pill__logo"><img src="{{ asset($logo) }}" alt="" width="30" height="30"></span>
                        <span>{{ $page['hero']['button_text'] }}</span>
                    </a>
                </div>
            @endif
        </section>

        {{-- Intro + event types --}}
        <section class="cv-section ev-intro">
            <span class="cv-eyebrow">{{ $page['intro']['eyebrow'] }}</span>
            <div class="cv-rule"><i></i><x-icon name="lotus" :size="26" /><i></i></div>
            <h2 class="cv-title">{{ $page['intro']['title'] }}</h2>
            <p class="cv-lead">{{ $page['intro']['text'] }}</p>

            <div class="cv-grid ev-grid">
                @foreach($page['types'] as $type)
                    <article class="cv-card ev-card">
                        <img src="{{ $img($type['image'], 'images/exp1.jpg') }}" alt="{{ $type['title'] }}" loading="lazy">
                        @if($type['icon'])
                            <div class="cv-ico ev-ico"><x-icon :name="$type['icon']" :size="46" /></div>
                        @endif
                        <h3>{{ $type['title'] }}</h3>
                        <p>{{ $type['text'] }}</p>
                        @if($type['button_text'])
                            <a class="cv-link" href="{{ $href($type['button_url']) }}">{{ $type['button_text'] }}</a>
                        @endif
                    </article>
                @endforeach
            </div>
        </section>

        {{-- Features --}}
        <section class="cv-dark ev-features">
            <h2>{{ $page['features']['title'] }}</h2>
            <div class="cv-rule"><i></i><x-icon name="lotus" :size="26" /><i></i></div>
            <div class="cv-feats ev-feats">
                @foreach($page['features']['items'] as $feature)
                    <div>
                        @if($feature['icon'])
                            <span class="ev-feat-ico"><x-icon :name="$feature['icon']" :size="42" /></span>
                        @endif
                        <h3>{{ $feature['title'] }}</h3>
                        <p>{{ $feature['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- Made personal --}}
        <section class="cv-split ev-split">
            <img src="{{ $img($page['personal']['image'], 'images/dining3.jpg') }}" alt="{{ $page['personal']['title'] }}" loading="lazy">
            <div>
                <span class="cv-eyebrow">{{ $page['personal']['eyebrow'] }}</span>
                <div class="cv-rule"><i></i><x-icon name="lotus" :size="26" /><i></i></div>
                <h2>{{ $page['personal']['title'] }}</h2>
                <p>{{ $page['personal']['text'] }}</p>
                @if($page['personal']['button_text'])
                    <a href="{{ $href($page['personal']['button_url']) }}" class="cv-btn">{{ $page['personal']['button_text'] }}</a>
                @endif
            </div>
        </section>

        {{-- Closing banner --}}
        <section class="cv-banner ev-banner" style="background-image:url('{{ $img($page['cta']['image']) }}')">
            <h2>{{ $page['cta']['title'] }}</h2>
            <div class="cv-row">
                @foreach($page['cta']['buttons'] as $button)
                    <a href="{{ $href($button['url']) }}" class="cv-btn {{ $loop->first ? 'cv-btn--gold' : '' }}">{{ $button['text'] }}</a>
                @endforeach
            </div>
        </section>
    </main>

    @include('layouts.footer')
</body>
</html>
