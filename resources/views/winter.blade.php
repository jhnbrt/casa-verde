<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v=20261017" sizes="any">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=20261017">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $winter['header']['title'] }} | Casa Verde Cliff Resort &amp; Spa</title>
    <meta name="description" content="{{ $winter['header']['text'] }}">
    @vite([
        'resources/css/header.css',
        'resources/css/pages.css',
        'resources/css/theme.css',
        'resources/css/footer.css','resources/css/rule.css',
        'resources/js/app.js'
    ])
</head>
<body>
    @include('layouts.header')

    <main class="cv-page wr-main">
        @include('winter.block', ['winter' => $winter])
    </main>

    @include('layouts.footer')
</body>
</html>
