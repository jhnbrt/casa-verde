<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v=20261017" sizes="any">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=20261017">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews | Casa Verde Cliff Resort &amp; Spa</title>
    @vite([
        'resources/css/header.css',
        'resources/css/pages.css',
        'resources/css/reviews.css',
        'resources/css/theme.css',
        'resources/css/footer.css',
        'resources/js/reviews.js',
        'resources/js/app.js'
    ])
</head>
<body>
    @include('layouts.header')
    @php
        $img = fn (?string $path, string $fallback = 'images/main.jpg') => asset($path && file_exists(public_path($path)) ? $path : $fallback);
        $href = fn (?string $url) => \Illuminate\Support\Str::startsWith($url ?? '', ['http', '#', 'mailto:', 'tel:']) ? $url : url($url ?: '/#contact');
        $lineColor = \App\Models\HomeContent::divider()['color'];
        $count = count($page['items']);
        $isImage = fn (?string $value) => filled($value) && preg_match('/\.(png|jpe?g|webp|gif|svg)$/i', $value) && file_exists(public_path($value));
    @endphp

    <svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false">
        <symbol id="rv-star" viewBox="0 0 24 24"><path d="M12 2.6l2.9 6 6.6.9-4.8 4.6 1.2 6.5L12 17.4l-5.9 3.2 1.2-6.5-4.8-4.6 6.6-.9L12 2.6Z"/></symbol>
    </svg>

    @php
        $bg = $page['header']['background'];
        $mainStyle = trim(
            ($lineColor ? '--rv-line:'.$lineColor.';' : '')
            .($bg && file_exists(public_path($bg)) ? "background-image:linear-gradient(rgba(239,233,223,.88),rgba(232,225,214,.93)),url('".asset($bg)."');background-size:cover;background-position:center;" : '')
        );
    @endphp

    <main class="cv-page rv" @if($mainStyle !== '') style="{{ $mainStyle }}" @endif>

        {{-- Heading --}}
        <header class="rv-head">
            <span class="rv-eyebrow">{{ $page['header']['eyebrow'] }}</span>
            <span class="rv-tick" aria-hidden="true"></span>
            <h1>{{ $page['header']['title'] }}</h1>
            <p>{{ $page['header']['text'] }}</p>
        </header>

        {{-- Carousel --}}
        @if($count > 0)
        <section class="rv-carousel" id="rv-carousel" data-rv-carousel data-autoplay="{{ (int) round($page['header']['autoplay'] * 1000) }}" aria-roledescription="carousel" aria-label="Guest reviews">

            @if($count > 1)
                <button type="button" class="rv-arrow rv-arrow--prev" data-rv-prev aria-label="Previous review">
                    @if($page['icons']['prev'])
                        <img src="{{ asset($page['icons']['prev']) }}" alt="" width="20" height="20">
                    @else
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m14.5 5-7 7 7 7"/></svg>
                    @endif
                </button>
            @endif

            <div class="rv-viewport" tabindex="0" aria-label="Use the left and right arrow keys to change review">
                <div class="rv-track">
                    @foreach($page['items'] as $review)
                        <article class="rv-slide" data-pos="{{ $loop->first ? '0' : ($loop->iteration === 2 ? '1' : ($loop->last ? '-1' : '2')) }}" role="group" aria-roledescription="slide" aria-label="{{ $loop->iteration }} of {{ $count }}">
                            <div class="rv-card">
                                <div class="rv-card__body">
                                    <div class="rv-stars" role="img" aria-label="{{ $review['rating'] }} out of 5 stars">
                                        @for($star = 1; $star <= 5; $star++)
                                            @if($page['icons']['star'])
                                                <img @class(['rv-star', 'rv-star--img', 'is-off' => $star > $review['rating']]) src="{{ asset($page['icons']['star']) }}" alt="" width="20" height="20">
                                            @else
                                                <svg @class(['rv-star', 'is-off' => $star > $review['rating']]) aria-hidden="true"><use href="#rv-star"/></svg>
                                            @endif
                                        @endfor
                                    </div>
                                    <blockquote><p>{{ $review['quote'] }}</p></blockquote>
                                    <span class="rv-card__rule" aria-hidden="true"></span>
                                    <p class="rv-card__name">{{ $review['name'] }}</p>
                                    @if(filled($review['place']))
                                        <p class="rv-card__place">{{ $review['place'] }}</p>
                                    @endif
                                </div>
                                <div class="rv-card__photo">
                                    <img src="{{ $img($review['image']) }}" alt="" loading="{{ $loop->first ? 'eager' : 'lazy' }}">
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>

            @if($count > 1)
                <button type="button" class="rv-arrow rv-arrow--next" data-rv-next aria-label="Next review">
                    @if($page['icons']['next'])
                        <img src="{{ asset($page['icons']['next']) }}" alt="" width="20" height="20">
                    @else
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m9.5 5 7 7-7 7"/></svg>
                    @endif
                </button>
            @endif

            {{-- Guest avatars --}}
            <div class="rv-avatars" role="group" aria-label="Choose a review">
                @foreach($page['items'] as $review)
                    <button type="button" class="rv-avatar" data-rv-go="{{ $loop->index }}" aria-label="{{ $review['name'] }}, {{ $review['place'] }}" @if($loop->first) aria-current="true" @endif>
                        @if($isImage($review['avatar']))
                            <img src="{{ asset($review['avatar']) }}" alt="" width="52" height="52" loading="lazy">
                        @else
                            <span class="rv-avatar__initial" aria-hidden="true">{{ \Illuminate\Support\Str::of($review['name'])->substr(0, 1)->upper() }}</span>
                        @endif
                    </button>
                @endforeach

                @if($page['header']['button_text'])
                    <a class="rv-avatar rv-avatar--more" href="{{ $href($page['header']['button_url']) }}" aria-label="{{ $page['header']['button_text'] }}" title="{{ $page['header']['button_text'] }}">
                        @if($page['header']['button_icon'])
                            <img src="{{ asset($page['header']['button_icon']) }}" alt="" width="22" height="22">
                        @else
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 6v12M6 12h12"/></svg>
                        @endif
                    </a>
                @endif
            </div>
        </section>
        @endif

        {{-- Figures: the digits roll like slot-machine reels when scrolled into view --}}
        <section class="rv-stats" aria-label="Guest satisfaction in numbers">
            @foreach($page['stats'] as $stat)
                <div class="rv-stat">
                    <span class="rv-stat__value" data-slot="{{ $stat['value'] }}" role="img" aria-label="{{ $stat['value'] }}">{{ $stat['value'] }}</span>
                    <span class="rv-stat__label">{{ $stat['label'] }}</span>
                </div>
            @endforeach
        </section>

        {{-- Share your experience: guests rate their stay and it appears in the carousel above --}}
        <section class="rv-share" id="rv-share" aria-labelledby="rv-share-title">
            <span class="rv-tick" aria-hidden="true"></span>
            <h2 id="rv-share-title">{{ $page['header']['button_text'] ?: 'Share your experience' }}</h2>
            <p class="rv-share__lead">Stayed with us? Rate your visit and tell other travellers what it was like.</p>

            @if($guestSummary)
                <p class="rv-share__summary">
                    <svg class="rv-star" aria-hidden="true"><use href="#rv-star"/></svg>
                    <strong>{{ number_format($guestSummary['average'], 1) }} / 5</strong>
                    from {{ $guestSummary['count'] }} {{ \Illuminate\Support\Str::plural('guest review', $guestSummary['count']) }} on this site
                </p>
            @endif

            @if(session('review_status'))
                <div class="rv-thanks" role="status">
                    <p>{{ session('review_status') }}</p>
                    @if(session('review_published'))
                        <a href="#rv-carousel">See it at the top of this page</a>
                    @endif
                </div>
            @else
                <form class="rv-form" method="POST" action="{{ route('reviews.store') }}">
                    @csrf

                    {{-- Hidden trap field for spam bots; leave it empty --}}
                    <div class="rv-hp" aria-hidden="true">
                        <label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label>
                    </div>

                    <fieldset class="rv-rate">
                        <legend>Your rating</legend>
                        <div class="rv-rate__stars">
                            @for($value = 5; $value >= 1; $value--)
                                <input type="radio" name="rating" id="rv-rate-{{ $value }}" value="{{ $value }}" @checked((int) old('rating') === $value) required>
                                <label for="rv-rate-{{ $value }}" title="{{ $value }} out of 5">
                                    <svg class="rv-star" aria-hidden="true"><use href="#rv-star"/></svg>
                                    <span class="rv-sr">{{ $value }} {{ \Illuminate\Support\Str::plural('star', $value) }}</span>
                                </label>
                            @endfor
                        </div>
                        @error('rating') <p class="rv-err" role="alert">{{ $message }}</p> @enderror
                    </fieldset>

                    <div class="rv-form__row">
                        <div class="rv-field">
                            <label for="rv-name">Your name</label>
                            <input type="text" id="rv-name" name="name" value="{{ old('name') }}" maxlength="60" required autocomplete="name" placeholder="e.g. Maria S.">
                            @error('name') <p class="rv-err" role="alert">{{ $message }}</p> @enderror
                        </div>
                        <div class="rv-field">
                            <label for="rv-place">Where are you from? <span>(optional)</span></label>
                            <input type="text" id="rv-place" name="place" value="{{ old('place') }}" maxlength="80" autocomplete="address-level2" placeholder="e.g. Cebu City">
                            @error('place') <p class="rv-err" role="alert">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="rv-field">
                        <label for="rv-comment">Your review</label>
                        <textarea id="rv-comment" name="comment" rows="4" maxlength="400" required placeholder="What did you enjoy most about your stay?">{{ old('comment') }}</textarea>
                        @error('comment') <p class="rv-err" role="alert">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" class="cv-btn rv-submit">Submit review</button>
                </form>
            @endif
        </section>
    </main>

    @include('layouts.footer')
</body>
</html>
