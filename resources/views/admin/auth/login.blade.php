<!DOCTYPE html>
<html lang="en" class="h-full bg-sand-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in · Casa Verde Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600&family=Jost:wght@400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans text-ink antialiased">

<div class="grid min-h-full lg:grid-cols-[minmax(0,1.05fr)_minmax(0,1fr)]">

    {{-- Photo panel --}}
    <div class="relative hidden bg-forest-900 lg:block">
        <img src="{{ asset('images/main.jpg') }}" alt="" class="absolute inset-0 h-full w-full object-cover">
        <div class="absolute inset-0 bg-forest-950/35"></div>
        <div class="absolute inset-x-0 bottom-0 h-2/5 bg-linear-to-t from-forest-950/85 to-transparent"></div>

        <div class="relative flex h-full flex-col justify-between p-12 text-white">
            <img src="{{ asset('images/logo.png') }}" alt="" class="h-16 w-16">
            <div>
                <p class="font-display text-5xl font-semibold leading-[1.05]">Casa Verde<br>Cliff Resort &amp; Spa</p>
                <p class="mt-3 text-lg text-sand-100/90">Camotes Island, Philippines</p>
            </div>
        </div>
    </div>

    {{-- Form --}}
    <div class="flex items-center justify-center px-6 py-12 sm:px-12">
        <div class="w-full max-w-sm">

            <div class="mb-8 flex h-14 w-14 items-center justify-center rounded-xl bg-forest-900 lg:hidden">
                <img src="{{ asset('images/logo.png') }}" alt="" class="h-10 w-10">
            </div>

            <h1 class="page-title">Sign in</h1>
            <p class="mt-3 text-ink-soft">Manage the photos and text on the Casa Verde website.</p>

            @if ($errors->any())
                <div role="alert" class="mt-6 rounded-lg border border-danger-200 bg-danger-50 px-4 py-3 text-[0.9375rem] text-danger-700">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login') }}" class="mt-8 space-y-5">
                @csrf

                <div>
                    <label for="email" class="field-label">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                           class="field-input">
                </div>

                <div>
                    <label for="password" class="field-label">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                           class="field-input">
                </div>

                <label class="flex items-center gap-2.5 text-[0.9375rem] text-ink-soft">
                    <input type="checkbox" name="remember" class="h-4 w-4 rounded border-sand-300 accent-forest-700">
                    Keep me signed in
                </label>

                <button type="submit" class="btn-primary w-full py-3">Sign in</button>
            </form>

            <p class="mt-8 text-sm">
                <a href="{{ url('/') }}" class="text-forest-700 hover:text-forest-900 hover:underline">Back to the website</a>
            </p>
        </div>
    </div>
</div>

</body>
</html>
