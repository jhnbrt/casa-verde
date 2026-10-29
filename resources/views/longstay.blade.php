<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v=20261017" sizes="any">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=20261017">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Long Stay | Casa Verde Cliff Resort &amp; Spa</title>
    @vite([
        'resources/css/header.css',
        'resources/css/pages.css',
        'resources/css/longstay.css',
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

    <main class="ls">

        {{-- Banner: copy on the left, photo fading in from the right, highlights strip on top of the photo --}}
        <section class="ls-hero">
            <div class="ls-hero__photo" role="img" aria-label="{{ $page['hero']['title'] }}" style="background-image:url('{{ $img($page['hero']['image'], 'images/long-stay.jpg') }}')@if($page['hero']['focus']);background-position:{{ $page['hero']['focus'] }}@endif"></div>

            <div class="ls-hero__top">
                <div class="ls-hero__copy">
                    <span class="ls-hero__eyebrow">{{ $page['hero']['eyebrow'] }}</span>
                    <h1>{!! nl2br(e($page['hero']['title'])) !!}</h1>
                    <p>{{ $page['hero']['text'] }}</p>
                </div>

                <div class="ls-hero__foot">
                    @if($page['hero']['tagline'])
                        <p class="ls-hero__tagline">{{ $page['hero']['tagline'] }}</p>
                    @endif
                    @if($page['hero']['button_text'])
                        <a href="{{ $href($page['hero']['button_url']) }}" class="book-button ls-cta ls-cta--serif">
                            <span class="book-icon" aria-hidden="true"></span>
                            {{ $page['hero']['button_text'] }}
                        </a>
                    @endif
                </div>
            </div>

            <div class="ls-strip">
                @foreach($page['strip'] as $item)
                    <div class="ls-strip__item">
                        @if($item['icon'])<x-icon :name="$item['icon']" :size="46" />@endif
                        <h3>{{ $item['title'] }}</h3>
                        <p>{{ $item['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- Ways to stay --}}
        <section class="ls-uses">
            <h2>{{ $page['uses_header']['eyebrow'] }}</h2>
            <div class="ls-uses__grid">
                @foreach($page['uses'] as $use)
                    <div class="ls-uses__item">
                        @if($use['icon'])<x-icon :name="$use['icon']" :size="50" />@endif
                        <b>{{ $use['title'] }}</b>
                        <p>{{ $use['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- Introduction, three boxes and the photos beside them --}}
        <section class="ls-main">
            <div class="ls-main__content">
                <header class="ls-intro">
                    <span class="ls-intro__eyebrow">{{ $page['intro']['eyebrow'] }}</span>
                    <x-rule :ornament="$page['intro']['icon']" />
                    <h2>{{ $page['intro']['title'] }}</h2>
                    <p>{{ $page['intro']['text'] }}</p>
                </header>

                <div class="ls-panels">
                    @foreach($page['panels'] as $panel)
                        <div class="ls-panel">
                            <div class="ls-panel__in">
                                <h3>{{ $panel['title'] }}</h3>
                                <x-rule />

                                @if($panel['numbered'])
                                    <ol class="ls-steps">
                                        @foreach($panel['items'] as $step)
                                            <li><em>{{ $loop->iteration }}</em><span>{{ $step['text'] }}</span></li>
                                        @endforeach
                                    </ol>
                                @else
                                    <ul class="ls-list">
                                        @foreach($panel['items'] as $point)
                                            <li>
                                                <span class="ls-list__icon">@if($point['icon'])<x-icon :name="$point['icon']" :size="44" />@endif</span>
                                                <span>{{ $point['text'] }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <aside class="ls-side">
                <div class="ls-frame">
                    <div class="ls-frame__in">
                        <img src="{{ $img($page['gallery'][0]['image'], 'images/main.jpg') }}" alt="{{ $page['gallery'][0]['alt'] }}" loading="lazy">
                    </div>
                </div>
                <img class="ls-side__wide" src="{{ $img($page['gallery'][1]['image'], 'images/wellness1.jpg') }}" alt="{{ $page['gallery'][1]['alt'] }}" loading="lazy">
            </aside>
        </section>

        {{-- Closing bar --}}
        <section class="ls-bar">
            <div class="ls-bar__brand">
                <img src="{{ asset($logo) }}" alt="" width="46" height="46">
                @if($site)
                    <span>{{ $site->title }}</span>
                    <small>{{ $site->subtitle }}</small>
                @endif
            </div>

            <p class="ls-bar__text">
                {{ $page['bar']['text'] }}
                @if($page['bar']['text_second'])
                    <em>{{ $page['bar']['text_second'] }}</em>
                @endif
            </p>

            @if($page['bar']['button_text'])
                <a href="{{ $href($page['bar']['button_url']) }}" class="book-button ls-cta ls-cta--bar">
                    <span class="book-icon" aria-hidden="true"></span>
                    {{ $page['bar']['button_text'] }}
                </a>
            @endif
        </section>
    </main>

    @include('layouts.footer')
</body>
</html>
