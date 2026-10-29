<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v=20261017" sizes="any">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex">
    <title>Request received | Casa Verde Cliff Resort &amp; Spa</title>
    @vite([
        'resources/css/header.css',
        'resources/css/book.css',
        'resources/css/theme.css',
        'resources/css/footer.css', 'resources/css/rule.css',
        'resources/js/app.js',
    ])
</head>

<body>

@include('layouts.header')

<main class="bk">
    <section class="bk-done">
        <div class="bk-done-mark" aria-hidden="true">
            <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
        </div>
        <span class="bk-eyebrow">{{ $copy['eyebrow'] }}</span>
        <h1>{{ $copy['title'] }}</h1>
        <p class="bk-done-lead">{!! nl2br(e($copy['lead'])) !!}</p>

        <dl class="bk-done-card">
            <div><dt>Reference</dt><dd class="bk-ref">{{ $booking->reference }}</dd></div>
            <div><dt>Villa</dt><dd>{{ $booking->villa_title }}</dd></div>
            <div><dt>Check-in</dt><dd>{{ $booking->check_in->format('D, M j, Y') }}</dd></div>
            <div><dt>Check-out</dt><dd>{{ $booking->check_out->format('D, M j, Y') }}</dd></div>
            <div><dt>Stay</dt><dd>{{ $booking->nights }} {{ \Illuminate\Support\Str::plural('night', $booking->nights) }} · {{ $booking->adults }} adult(s){{ $booking->children ? ', '.$booking->children.' child(ren)' : '' }}</dd></div>
            <div class="is-total"><dt>Estimated total</dt><dd>₱{{ number_format($booking->total, 2) }}</dd></div>
        </dl>

        <div class="bk-done-actions">
            <a href="{{ url('/') }}" class="bk-btn-ghost">Back to home</a>
            <a href="{{ route('booking.index') }}" class="bk-btn-solid">Request another villa</a>
        </div>
    </section>
</main>

@include('layouts.footer')

</body>
</html>
