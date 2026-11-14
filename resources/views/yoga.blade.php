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
        // A photo is only shown when it is big enough to look sharp at its size on the page. A smaller picture
        // (or none) simply leaves it out and the section keeps a clean text-only design.
        $photo = function (?string $path, int $minWidth): ?string {
            if (! $path || ! is_file(public_path($path))) {
                return null;
            }

            if (strtolower(pathinfo($path, PATHINFO_EXTENSION)) === 'svg') {
                return asset($path);
            }

            try {
                $size = getimagesize(public_path($path));
            } catch (\Throwable $e) {
                $size = false;
            }

            return $size && $size[0] >= $minWidth ? asset($path) : null;
        };
        $href = fn (?string $url) => \Illuminate\Support\Str::startsWith($url ?? '', ['http', '#', 'mailto:', 'tel:']) ? $url : url($url ?: '/#contact');
        $chips = fn (?string $text): array => array_values(array_filter(array_map('trim', preg_split('/[·|]/u', (string) $text))));
        $logo = \App\Models\HomeContent::siteLogo();

        $heroPhoto = $photo($page['hero']['image'], 900);
        $personalPhoto = $photo($page['personal']['image'], 640);
        $bannerPhoto = $photo($page['cta']['image'], 900);

        // The sample quote ships with the placeholder name "Guest name"; it stays hidden until it is a real review.
        $hasQuote = filled($page['quote']['text']) && trim((string) $page['quote']['title']) !== 'Guest name';
    @endphp

    <main class="cv-page yg">

        {{-- Hero --}}
        <section class="yg-hero{{ $heroPhoto ? ' yg-hero--photo' : '' }}">
            <div class="yg-wrap yg-hero__inner">
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
                        @if(count($page['types']))
                            <a href="#classes" class="yg-ghost">See the classes</a>
                        @endif
                    </div>
                </div>
                @if($heroPhoto)
                    <figure class="yg-hero__photo">
                        <img src="{{ $heroPhoto }}" alt="{{ $page['hero']['title'] }}">
                    </figure>
                @endif
            </div>
        </section>

        {{-- Quick facts --}}
        @if(count($page['stats']))
            <section class="yg-stats" aria-label="At a glance">
                @foreach($page['stats'] as $stat)
                    <div class="yg-stat">
                        @if($stat['icon'])<span class="yg-stat__ico"><x-icon :name="$stat['icon']" :size="26" /></span>@endif
                        <strong>{{ $stat['title'] }}</strong>
                        <small>{{ $stat['eyebrow'] }}</small>
                    </div>
                @endforeach
            </section>
        @endif

        {{-- Classes --}}
        <section class="yg-section yg-classes" id="classes">
            <header class="yg-head">
                <span class="yg-eyebrow yg-eyebrow--dark">{{ $page['intro']['eyebrow'] }}</span>
                <x-rule />
                <h2>{{ $page['intro']['title'] }}</h2>
                <p>{{ $page['intro']['text'] }}</p>
            </header>

            <div class="yg-cards">
                @foreach($page['types'] as $type)
                    @php
                        $cardPhoto = $photo($type['image'] ?? null, 480);
                        $cardPrice = is_numeric($type['price'] ?? null) && (float) $type['price'] > 0 ? (float) $type['price'] : null;
                        $cardChips = $chips($type['eyebrow'] ?? null);
                    @endphp
                    <article class="yg-card{{ $cardPhoto ? '' : ' yg-card--plain' }}">
                        @if($cardPhoto)
                            <div class="yg-card__photo"><img src="{{ $cardPhoto }}" alt="{{ $type['title'] }}" loading="lazy"></div>
                        @endif
                        <div class="yg-card__body">
                            @if($type['icon'])
                                <span class="yg-card__ico"><x-icon :name="$type['icon']" :size="26" /></span>
                            @endif
                            <h3>{{ $type['title'] }}</h3>
                            @if(count($cardChips))
                                <ul class="yg-chips">
                                    @foreach($cardChips as $chip)<li>{{ $chip }}</li>@endforeach
                                </ul>
                            @endif
                            <p>{{ $type['text'] }}</p>
                            @if($cardPrice || $type['button_text'])
                                <div class="yg-card__foot">
                                    @if($cardPrice)
                                        <div class="yg-card__price">
                                            <small>From</small>
                                            <strong class="yg-price">₱{{ number_format($cardPrice) }}</strong>
                                            <small>per person</small>
                                        </div>
                                    @endif
                                    @if($type['button_text'])
                                        <a class="yg-btn" href="{{ $href($type['button_url']) }}">{{ $type['button_text'] }}</a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        </section>

        {{-- Daily rhythm --}}
        @if(count($page['schedule']['items']))
            <section class="yg-section yg-schedule" id="schedule">
                <header class="yg-head">
                    <span class="yg-eyebrow yg-eyebrow--dark">{{ $page['schedule']['eyebrow'] }}</span>
                    <h2>{{ $page['schedule']['title'] }}</h2>
                </header>
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

        {{-- Highlights --}}
        @if(count($page['features']['items']))
            <section class="yg-benefits">
                <h2>{{ $page['features']['title'] }}</h2>
                <x-rule />
                <div class="yg-benefits__grid">
                    @foreach($page['features']['items'] as $feature)
                        <div>
                            @if($feature['icon'])
                                <span class="yg-benefits__ico"><x-icon :name="$feature['icon']" :size="36" /></span>
                            @endif
                            <h3>{{ $feature['title'] }}</h3>
                            <p>{{ $feature['text'] }}</p>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- Teachers (only when added in the admin) --}}
        @if(count($page['teachers']['items']))
            <section class="yg-section yg-teachers" id="teachers">
                <header class="yg-head">
                    <span class="yg-eyebrow yg-eyebrow--dark">{{ $page['teachers']['eyebrow'] }}</span>
                    <h2>{{ $page['teachers']['title'] }}</h2>
                </header>
                <div class="yg-team">
                    @foreach($page['teachers']['items'] as $teacher)
                        @php $face = $photo($teacher['image'] ?? null, 240); @endphp
                        <article class="yg-teacher">
                            <span class="yg-teacher__face">
                                @if($face)
                                    <img src="{{ $face }}" alt="{{ $teacher['title'] }}" loading="lazy">
                                @else
                                    {{ mb_strtoupper(mb_substr(trim($teacher['title']), 0, 1)) }}
                                @endif
                            </span>
                            <h3>{{ $teacher['title'] }}</h3>
                            @if($teacher['eyebrow'])<small>{{ $teacher['eyebrow'] }}</small>@endif
                            @if($teacher['text'])<p>{{ $teacher['text'] }}</p>@endif
                        </article>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- Your pace --}}
        <section class="yg-split{{ $personalPhoto ? '' : ' yg-split--plain' }}">
            @if($personalPhoto)
                <img src="{{ $personalPhoto }}" alt="{{ $page['personal']['title'] }}" loading="lazy">
            @endif
            <div>
                <span class="yg-eyebrow yg-eyebrow--dark">{{ $page['personal']['eyebrow'] }}</span>
                <x-rule />
                <h2>{{ $page['personal']['title'] }}</h2>
                <p>{{ $page['personal']['text'] }}</p>
                @if($page['personal']['button_text'])
                    <a href="{{ $href($page['personal']['button_url']) }}" class="yg-btn">{{ $page['personal']['button_text'] }}</a>
                @endif
            </div>
        </section>

        {{-- Questions --}}
        @if(count($page['faq']['items']))
            <section class="yg-section yg-faq" id="faq">
                <header class="yg-head">
                    <span class="yg-eyebrow yg-eyebrow--dark">{{ $page['faq']['eyebrow'] }}</span>
                    <h2>{{ $page['faq']['title'] }}</h2>
                </header>
                <div class="yg-faq__list">
                    @foreach($page['faq']['items'] as $item)
                        <details class="yg-faq__item">
                            <summary>{{ $item['title'] }}</summary>
                            <p>{!! nl2br(e($item['text'])) !!}</p>
                        </details>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- Guest words --}}
        @if($hasQuote)
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
        <section class="yg-banner{{ $bannerPhoto ? ' yg-banner--photo' : '' }}"@if($bannerPhoto) style="background-image:url('{{ $bannerPhoto }}')"@endif>
            <h2>{{ $page['cta']['title'] }}</h2>
            <div class="yg-actions">
                @foreach($page['cta']['buttons'] as $button)
                    <a href="{{ $href($button['url']) }}" class="yg-btn {{ $loop->first ? 'yg-btn--gold' : 'yg-btn--light' }}">{{ $button['text'] }}</a>
                @endforeach
            </div>
        </section>
    </main>

    @include('layouts.footer')
</body>
</html>
