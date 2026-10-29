<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v=20261017" sizes="any">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=20261017">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book your stay | Casa Verde Cliff Resort &amp; Spa</title>
    <meta name="description" content="Choose your villa, pick your dates and send your booking request to Casa Verde Cliff Resort &amp; Spa.">

    @vite([
        'resources/css/header.css',
        'resources/css/book.css',
        'resources/css/theme.css',
        'resources/css/footer.css', 'resources/css/rule.css',
        'resources/js/app.js',
        'resources/js/book.js',
    ])
</head>

<body>

@include('layouts.header')

@php
    $openSlug = $open ?: old('_villa');
    $peso = fn ($n) => '₱'.number_format((float) $n, 2);
    $dateLabel = fn ($d) => $d->format('M d');
@endphp

<main class="bk">

    <header class="bk-head">
        <span class="bk-eyebrow">{{ $page->subtitle ?? 'Book your stay' }}</span>
        <span class="bk-tick" aria-hidden="true"></span>
        <h1>{{ $page->title ?? 'Reserve your villa' }}</h1>
        <p>{{ $page->description ?? 'Choose your villa, pick your dates and send your request. Our team confirms it by email.' }}</p>

        <ol class="bk-steps" aria-label="How booking works">
            <li><b>1</b> {{ $L[1] }}</li>
            <li><b>2</b> {{ $L[2] }}</li>
            <li><b>3</b> {{ $L[3] }}</li>
        </ol>
    </header>

    @if (! $bookingOpen)
        <div class="bk-paused" role="status">
            <p>{!! nl2br(e($closedMessage)) !!}</p>
            <a href="{{ route('contact.index') }}" class="bk-btn-solid">Contact us</a>
        </div>
    @elseif ($villas === [])
        <p class="bk-empty">Online booking is not available yet. Please <a href="{{ route('contact.index') }}">contact us</a> to reserve.</p>
    @endif

    @foreach ($villas as $slug => $villa)
        @php
            $promoOn = \App\Support\BookingRates::promoVisible($villa);
            $isOpen = $openSlug === $slug;
            $mine = old('_villa') === $slug;          // this form was just submitted with errors
            $shownRate = $promoOn ? $villa['promo'] : $villa['regular'];
            $photos = $villa['photos'];
            $rates = [
                'regular' => $villa['regular'],
                'promo' => $promoOn ? $villa['promo'] : null,
                'from' => $promoOn ? $villa['from']->toDateString() : null,
                'to' => $promoOn ? $villa['to']->toDateString() : null,
                'maxNights' => $maxNights,
            ];
        @endphp

        <article class="bk-villa" id="{{ $slug }}" data-bk-villa data-open="{{ $isOpen ? 'true' : 'false' }}">

            @php($total = count($photos))
            <div class="bk-media" data-bk-gallery>
                @if ($total)
                    <div class="bk-mosaic bk-n{{ min($total, 5) }}">
                        @foreach (array_slice($photos, 0, 5) as $i => $photo)
                            <button type="button" class="bk-tile bk-t{{ $i + 1 }}" data-bk-open="{{ $i }}" aria-label="Open photo {{ $i + 1 }} of {{ $total }}">
                                <img src="{{ asset($photo) }}" alt="{{ $villa['title'] }} photo {{ $i + 1 }}" data-bk-img @if ($i > 0) loading="lazy" @endif>
                            </button>
                        @endforeach

                        {{-- Photos past the fifth only open in the viewer --}}
                        @foreach (array_slice($photos, 5) as $j => $photo)
                            <img src="{{ asset($photo) }}" alt="{{ $villa['title'] }} photo {{ $j + 6 }}" data-bk-img hidden>
                        @endforeach

                        <a href="{{ route('villas.index') }}" class="bk-chip bk-back">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                            Go back
                        </a>

                        <button type="button" class="bk-chip bk-expand" data-bk-open="0" aria-label="Expand photos">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8 3H5a2 2 0 0 0-2 2v3M16 3h3a2 2 0 0 1 2 2v3M8 21H5a2 2 0 0 1-2-2v-3M16 21h3a2 2 0 0 0 2-2v-3"/></svg>
                            <span>Expand</span>
                        </button>

                        @if ($total > 1)
                            <button type="button" class="bk-chip bk-all" data-bk-open="0">Show all {{ $total }} photos</button>
                        @endif
                    </div>
                @else
                    <div class="bk-nophoto"><span>{{ $villa['title'] }}</span></div>
                @endif
            </div>

            <div class="bk-body">

                <div class="bk-summary">
                    <h2>{{ $villa['title'] }}</h2>

                    <p class="bk-price">
                        @if ($promoOn)
                            <s>{{ $peso($villa['regular']) }}</s>
                        @endif
                        <strong>{{ $peso($shownRate) }}</strong><span> {{ $L[6] }}</span>
                    </p>

                    @if ($promoOn)
                        <span class="bk-promo">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2c1 4-3 5-3 9a3 3 0 0 0 6 0c0-1-.4-1.8-1-2.5 2.5 1 5 3.6 5 7a7 7 0 0 1-14 0c0-6 7-8 7-13.5z"/></svg>
                            {{ $L[8] }} {{ strtoupper($dateLabel($villa['from'])) }} – {{ strtoupper($dateLabel($villa['to'])) }}, {{ $villa['to']->format('Y') }}
                        </span>
                    @endif

                    <p class="bk-note">{{ $L[7] }}</p>
                </div>

                <div class="bk-about">
                    <p>{{ $villa['description'] }}</p>

                    <button type="button" class="bk-reserve" data-bk-toggle aria-expanded="{{ $isOpen ? 'true' : 'false' }}" aria-controls="panel-{{ $slug }}" data-open-text="{{ $L[4] }}" data-close-text="{{ $L[5] }}">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4.5" width="18" height="16.5" rx="2"/><path d="M8 2.5v4M16 2.5v4M3 10h18"/></svg>
                        <span data-bk-toggle-text>{{ $isOpen ? $L[5] : $L[4] }}</span>
                    </button>
                </div>

            </div>

            {{-- ===================== Request form ===================== --}}
            <div class="bk-panel" id="panel-{{ $slug }}" @unless ($isOpen) hidden @endunless>
                <form method="POST" action="{{ route('booking.store', $slug) }}" class="bk-form" novalidate data-bk-form
                      data-rates='@json($rates)'>
                    @csrf
                    <input type="hidden" name="_villa" value="{{ $slug }}">
                    <div class="bk-hp" aria-hidden="true"><label>Leave this empty <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>

                    @if ($mine && $errors->any())
                        <div class="bk-alert" role="alert" tabindex="-1" data-bk-alert>
                            <strong>Please check the highlighted fields.</strong>
                            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                        </div>
                    @endif

                    <div class="bk-cols">
                        <div class="bk-fields">

                            <fieldset class="bk-group">
                                <legend><b>1</b> {{ $L[9] }}</legend>
                                <div class="bk-row">
                                    <label class="bk-field">
                                        <span>Check-in</span>
                                        <input type="date" name="check_in" required min="{{ now()->toDateString() }}" value="{{ $mine ? old('check_in') : '' }}" data-bk-in
                                               @class(['is-invalid' => $mine && $errors->has('check_in')])>
                                    </label>
                                    <label class="bk-field">
                                        <span>Check-out</span>
                                        <input type="date" name="check_out" required min="{{ now()->addDay()->toDateString() }}" value="{{ $mine ? old('check_out') : '' }}" data-bk-out
                                               @class(['is-invalid' => $mine && $errors->has('check_out')])>
                                    </label>
                                </div>
                                <p class="bk-hint" data-bk-nights-hint>Select your dates to see the price.</p>
                            </fieldset>

                            <fieldset class="bk-group">
                                <legend><b>2</b> {{ $L[10] }}</legend>
                                <div class="bk-row">
                                    @foreach ([['adults', 'Adults', 'Age 13+', 1], ['children', 'Children', 'Age 0–12', 0]] as [$key, $label, $sub, $min])
                                        <div class="bk-field">
                                            <span>{{ $label }} <small>{{ $sub }}</small></span>
                                            <div class="bk-stepper" data-bk-stepper data-min="{{ $min }}" data-max="{{ $maxGuests }}">
                                                <button type="button" data-step="-1" aria-label="Fewer {{ strtolower($label) }}">−</button>
                                                <input type="number" name="{{ $key }}" min="{{ $min }}" max="{{ $maxGuests }}" inputmode="numeric" required
                                                       value="{{ $mine ? old($key, $key === 'adults' ? 2 : 0) : ($key === 'adults' ? 2 : 0) }}" aria-label="{{ $label }}">
                                                <button type="button" data-step="1" aria-label="More {{ strtolower($label) }}">+</button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </fieldset>

                            <fieldset class="bk-group">
                                <legend><b>3</b> {{ $L[11] }}</legend>
                                <label class="bk-field">
                                    <span>Full name</span>
                                    <input type="text" name="name" required maxlength="120" autocomplete="name" placeholder="Your full name"
                                           value="{{ $mine ? old('name') : '' }}" @class(['is-invalid' => $mine && $errors->has('name')])>
                                </label>
                                <div class="bk-row">
                                    <label class="bk-field">
                                        <span>Email address</span>
                                        <input type="email" name="email" required maxlength="150" autocomplete="email" placeholder="you@example.com"
                                               value="{{ $mine ? old('email') : '' }}" @class(['is-invalid' => $mine && $errors->has('email')])>
                                    </label>
                                    <label class="bk-field">
                                        <span>Phone number</span>
                                        <input type="tel" name="phone" required maxlength="40" autocomplete="tel" placeholder="+63 900 000 0000"
                                               value="{{ $mine ? old('phone') : '' }}" @class(['is-invalid' => $mine && $errors->has('phone')])>
                                    </label>
                                </div>
                                <label class="bk-field">
                                    <span>Country of origin</span>
                                    <input type="text" name="country" required maxlength="80" autocomplete="country-name" placeholder="Country of origin"
                                           value="{{ $mine ? old('country') : '' }}" @class(['is-invalid' => $mine && $errors->has('country')])>
                                </label>
                                <label class="bk-field">
                                    <span>Special requests <small>Optional</small></span>
                                    <textarea name="special_requests" rows="3" maxlength="1000" placeholder="Arrival time, celebrations, dietary needs…">{{ $mine ? old('special_requests') : '' }}</textarea>
                                </label>
                            </fieldset>

                        </div>

                        <aside class="bk-side">
                            <div class="bk-quote" data-bk-quote aria-live="polite">
                                <h3>{{ $L[16] }}</h3>
                                <p class="bk-quote-empty" data-bk-empty>Choose your check-in and check-out dates to see your total.</p>
                                <dl data-bk-lines hidden>
                                    <div><dt data-bk-line-label></dt><dd data-bk-line-amount></dd></div>
                                    <div data-bk-save-row hidden class="is-save"><dt>Promo savings</dt><dd data-bk-save></dd></div>
                                    <div class="is-total"><dt>{{ $L[17] }}</dt><dd data-bk-total></dd></div>
                                </dl>
                                <p class="bk-quote-foot">{{ $L[13] }}</p>
                            </div>

                            <div class="bk-terms">
                                <h3>
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z"/><path d="M14 3v5h5M9 13h6M9 17h6"/></svg>
                                    {{ $L[15] }}
                                </h3>
                                <div class="bk-terms-text" tabindex="0" role="region" aria-label="Terms and conditions for {{ $villa['title'] }}">{!! nl2br(e($villa['terms'])) !!}</div>
                                <label class="bk-check">
                                    <input type="checkbox" name="terms" value="1" required @checked($mine && old('terms')) @class(['is-invalid' => $mine && $errors->has('terms')])>
                                    <span>{{ $L[14] }}</span>
                                </label>
                            </div>

                            <button type="submit" class="bk-submit" data-bk-submit>
                                <span>{{ $L[12] }}</span>
                            </button>
                            <p class="bk-secure">Your details are only used to handle this booking.</p>
                        </aside>
                    </div>
                </form>
            </div>

        </article>
    @endforeach

</main>

{{-- Full-screen photo viewer shared by every villa --}}
<dialog class="bk-lightbox" data-bk-lightbox aria-label="Photo viewer">
    <button type="button" class="bk-lb-close" data-bk-lb-close aria-label="Close">×</button>
    <button type="button" class="bk-lb-nav bk-lb-prev" data-bk-lb-prev aria-label="Previous photo">‹</button>
    <img alt="" data-bk-lb-img>
    <button type="button" class="bk-lb-nav bk-lb-next" data-bk-lb-next aria-label="Next photo">›</button>
    <span class="bk-lb-count" data-bk-lb-count></span>
</dialog>

@include('layouts.footer')

</body>
</html>
