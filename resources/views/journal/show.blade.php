<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v=20261017" sizes="any">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=20261017">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $article['title'] }} | Journal | Casa Verde Cliff Resort &amp; Spa</title>
    <meta name="description" content="{{ $article['excerpt'] }}">
    @vite([
        'resources/css/header.css',
        'resources/css/pages.css',
        'resources/css/theme.css',
        'resources/css/footer.css','resources/css/rule.css',
        'resources/js/app.js'
    ])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,400;0,700;1,400&family=Playfair+Display:wght@500;700&display=swap">
    @include('journal._style')
</head>
<body data-no-photo-viewer>
    @include('layouts.header')

    <main class="cv-page jn jn--article">
        <header class="jn-art-head">
            <a class="jn-back" href="{{ route('journal.index') }}">&larr; Back to Journal</a>
            <h1>{{ $article['title'] }}</h1>
            @if($article['date'] !== '')<p class="jn-pub">Published on {{ $article['date'] }}</p>@endif
        </header>

        <article class="jn-article">
            @if($article['image'])
                <img class="jn-hero" src="{{ asset($article['image']) }}" alt="{{ $article['title'] }}">
            @endif
            @php
                // Light formatting for the article text: ## heading, ### smaller heading, - bullet list, **bold**.
                $bold = fn (string $t): string => preg_replace('/\*\*(.+?)\*\*/s', '<strong>$1</strong>', e($t));
            @endphp
            @foreach($article['paragraphs'] as $paragraph)
                @php
                    $block = trim($paragraph);
                    $lines = preg_split('/\R/', $block);
                    $isList = collect($lines)->every(fn ($line) => preg_match('/^\s*[-*]\s+/', $line) === 1);
                    $rest = trim(implode("\n", array_slice($lines, 1)));
                @endphp
                @if(str_starts_with($block, '####'))
                    <h4>{{ trim(ltrim($lines[0], '#')) }}</h4>
                    @if($rest !== '')<p>{!! nl2br($bold($rest)) !!}</p>@endif
                @elseif(str_starts_with($block, '###'))
                    <h3>{{ trim(ltrim($lines[0], '#')) }}</h3>
                    @if($rest !== '')<p>{!! nl2br($bold($rest)) !!}</p>@endif
                @elseif(str_starts_with($block, '##'))
                    <h2>{{ trim(ltrim($lines[0], '#')) }}</h2>
                    @if($rest !== '')<p>{!! nl2br($bold($rest)) !!}</p>@endif
                @elseif($isList)
                    <ul>
                        @foreach($lines as $line)
                            <li>{!! $bold(preg_replace('/^\s*[-*]\s+/', '', $line)) !!}</li>
                        @endforeach
                    </ul>
                @else
                    <p>{!! nl2br($bold($block)) !!}</p>
                @endif
            @endforeach

            @if(count($article['gallery']) > 0)
                <div class="jn-gallery">
                    @foreach($article['gallery'] as $photo)
                        <figure>
                            <img src="{{ asset($photo['image']) }}" alt="{{ $photo['caption'] ?: $article['title'] }}" loading="lazy">
                            @if($photo['caption'] !== '')<figcaption>{{ $photo['caption'] }}</figcaption>@endif
                        </figure>
                    @endforeach
                </div>
            @endif
        </article>

        @if($more->isNotEmpty())
            <section class="jn-others">
                <h2>{{ $moreHeading }}</h2>
                <div class="jn-grid">
                    @foreach($more as $other)
                        <a class="jn-card" href="{{ route('journal.show', $other['slug']) }}">
                            @if($other['image'])
                                <img src="{{ asset($other['image']) }}" alt="" loading="lazy">
                            @else
                                <span class="jn-ph" aria-hidden="true"></span>
                            @endif
                            <span class="jn-body">
                                <h2>{{ $other['title'] }}</h2>
                                <span class="jn-more">{{ $readLabel }} &rarr;</span>
                            </span>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif
    </main>

    @include('layouts.footer')
</body>
</html>
