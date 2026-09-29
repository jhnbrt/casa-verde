<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Long Stay | Casa Verde Cliff Resort &amp; Spa</title>
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
    @php $book = url('/') . '#contact'; @endphp

    <main class="cv-page">

        <section class="cv-hero" style="background-image:url('{{ asset('images/long-stay.jpg') }}')">
            <span class="cv-eyebrow">Long Stay</span>
            <h1>Stay Longer.<br>Live Deeper.</h1>
            <p>Whether you are working remotely, escaping the winter or beginning a slower chapter, Casa Verde gives you the space, comfort and rhythm to feel at home in paradise.</p>
            <div><a href="{{ $book }}" class="cv-btn cv-btn--gold">Plan your long stay</a></div>
        </section>

        <div class="cv-strip">
            @foreach([
                ['Flexible Stays', 'From 4 weeks up to 12 months'],
                ['Feel at Home', 'Comfortable villas with everything you need'],
                ['Work & Connect', 'High-speed internet, quiet spaces, total focus'],
                ['Wellness & Balance', 'Wellness programs, healthy food and island living'],
                ['Warm Hospitality', 'Personal service and a community that cares'],
            ] as [$t, $d])
                <div><h3>{{ $t }}</h3><p>{{ $d }}</p></div>
            @endforeach
        </div>

        <section class="cv-section">
            <span class="cv-eyebrow" style="font-size:1.6rem">Designed for the way you want to live.</span>
            <div class="cv-uses">
                @foreach([
                    ['Workation', 'Work from paradise'],
                    ['Wellness Retreat', 'Rest, reset and recharge'],
                    ['Winter Escape', 'Sunshine when you need it most'],
                    ['Family Long Stay', 'Quality time in a safe and relaxing place'],
                    ['Extended Island Living', 'Enjoy your golden years in comfort'],
                    ['Sabbatical', 'Take a break. Explore life.'],
                ] as [$t, $d])
                    <div><b>{{ $t }}</b><p>{{ $d }}</p></div>
                @endforeach
            </div>
        </section>

        <section class="cv-section" style="padding-top:0">
            <span class="cv-eyebrow">Long stay living</span>
            <div class="cv-rule"><i></i><span>✿</span><i></i></div>
            <h2 class="cv-title" style="letter-spacing:.01em;font-weight:400">Everything you need to feel at home.</h2>
            <p class="cv-lead">Designed for guests who want more than a holiday: space, comfort, connection and the rhythm of island life.</p>

            <div class="cv-panels">
                <div class="cv-panel">
                    <h3>Who it is for</h3>
                    <ul><li>Workation</li><li>Winter Escape</li><li>Sabbatical</li><li>Family Time</li></ul>
                </div>
                <div class="cv-panel">
                    <h3>What is included</h3>
                    <ul><li>Private Villa</li><li>Breakfast</li><li>High-Speed Wi-Fi</li><li>Pool &amp; Wellness</li><li>Housekeeping</li></ul>
                </div>
                <div class="cv-panel">
                    <h3>How it works</h3>
                    <ol>
                        <li><em>1</em>Share your preferred dates</li>
                        <li><em>2</em>Choose your villa</li>
                        <li><em>3</em>Settle into island life</li>
                    </ol>
                </div>
            </div>
        </section>

        <div class="cv-bar">
            <span>Stay for a month. Stay for a season. Stay as long as you need.</span>
            <a href="{{ $book }}" class="cv-btn cv-btn--gold">Talk to our long stay concierge</a>
        </div>
    </main>

    @include('layouts.footer')
</body>
</html>
