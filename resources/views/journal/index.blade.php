<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v=20261017" sizes="any">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=20261017">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} | Casa Verde Cliff Resort &amp; Spa</title>
    <meta name="description" content="{{ $text }}">
    @vite([
        'resources/css/header.css',
        'resources/css/pages.css',
        'resources/css/theme.css',
        'resources/css/footer.css','resources/css/rule.css',
        'resources/js/app.js'
    ])
    @include('journal._style')
</head>
<body data-no-photo-viewer>
    @include('layouts.header')

    <main class="cv-page jn">
        <header class="jn-head">
            <h1>{{ $title }}</h1>
            <x-rule />
            <p>{{ $text }}</p>
        </header>

        @if($articles->isEmpty())
            <p class="jn-empty">Our first stories are on their way.</p>
        @else
            <div class="jn-grid">
                @foreach($articles as $article)
                    <a class="jn-card" href="{{ route('journal.show', $article['slug']) }}">
                        @if($article['image'])
                            <img src="{{ asset($article['image']) }}" alt="" loading="{{ $loop->index < 3 ? 'eager' : 'lazy' }}">
                        @else
                            <span class="jn-ph" aria-hidden="true"></span>
                        @endif
                        <span class="jn-body">
                            @if($article['date'] !== '')<span class="jn-date">{{ $article['date'] }}</span>@endif
                            <h2>{{ $article['title'] }}</h2>
                            @if($article['excerpt'] !== '')<p>{{ $article['excerpt'] }}</p>@endif
                            <span class="jn-more">{{ $readLabel }} &rarr;</span>
                        </span>
                    </a>
                @endforeach
            </div>
        @endif
    </main>

    @include('layouts.footer')
</body>
</html>
