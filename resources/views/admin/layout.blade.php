<!DOCTYPE html>
<html lang="en" class="h-full bg-sand-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') · Casa Verde Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600&family=Jost:wght@400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full bg-sand-100 font-sans text-ink antialiased">
<div class="min-h-screen md:flex">

    {{-- Sidebar (desktop) --}}
    <aside class="hidden bg-forest-900 text-sand-100 md:sticky md:top-0 md:flex md:h-screen md:w-64 md:shrink-0 md:flex-col lg:w-72">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-6 pb-6 pt-7">
            <img src="{{ asset('images/logo.png') }}" alt="" class="h-11 w-11">
            <span>
                <span class="block font-display text-2xl font-semibold leading-none text-white">Casa Verde</span>
                <span class="mt-1 block text-sm text-gold-400">Site manager</span>
            </span>
        </a>

        <div class="flex-1 overflow-y-auto px-3 py-2">
            @include('admin.partials.nav')
        </div>

        <div class="border-t border-white/10 px-3 py-4">
            <a href="{{ url('/') }}" target="_blank" rel="noopener"
               class="flex items-center gap-3 rounded-lg px-3 py-2 text-[0.9375rem] text-sand-200 transition-colors hover:bg-white/5 hover:text-white">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                View live site
            </a>
            <p class="mt-3 truncate px-3 text-sm text-sand-200/70">{{ auth()->user()?->email }}</p>
            <form method="POST" action="{{ route('admin.logout') }}" class="mt-1">
                @csrf
                <button type="submit"
                        class="flex w-full items-center gap-3 rounded-lg px-3 py-2 text-left text-[0.9375rem] text-sand-200 transition-colors hover:bg-white/5 hover:text-white">
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/></svg>
                    Log out
                </button>
            </form>
        </div>
    </aside>

    <div class="flex min-w-0 flex-1 flex-col">

        {{-- Top bar with menu (mobile) --}}
        <header class="sticky top-0 z-30 bg-forest-900 text-sand-100 md:hidden">
            <details class="group">
                <summary class="flex h-14 cursor-pointer list-none items-center justify-between px-4 [&::-webkit-details-marker]:hidden">
                    <span class="flex items-center gap-2.5">
                        <img src="{{ asset('images/logo.png') }}" alt="" class="h-8 w-8">
                        <span class="font-display text-xl font-semibold text-white">Casa Verde</span>
                    </span>
                    <span class="flex items-center gap-2 text-sm">
                        Menu
                        <svg class="h-6 w-6 group-open:hidden" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5"/></svg>
                        <svg class="hidden h-6 w-6 group-open:block" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </span>
                </summary>

                <div class="max-h-[calc(100vh-3.5rem)] overflow-y-auto px-3 pb-5 pt-1">
                    @include('admin.partials.nav')

                    <div class="mt-4 border-t border-white/10 pt-3">
                        <a href="{{ url('/') }}" target="_blank" rel="noopener"
                           class="block rounded-lg px-3 py-2 text-[0.9375rem] text-sand-200 hover:bg-white/5 hover:text-white">View live site</a>
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit"
                                    class="block w-full rounded-lg px-3 py-2 text-left text-[0.9375rem] text-sand-200 hover:bg-white/5 hover:text-white">Log out</button>
                        </form>
                    </div>
                </div>
            </details>
        </header>

        <main class="flex-1 px-4 py-8 sm:px-8 lg:px-12 lg:py-12">
            <div class="mx-auto max-w-5xl">

                @if (session('status'))
                    <div role="status" class="mb-8 flex items-start gap-3 rounded-lg border border-forest-200 bg-forest-50 px-4 py-3 text-forest-900">
                        <svg class="mt-0.5 h-5 w-5 shrink-0 text-forest-700" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-[0.9375rem]">{{ session('status') }}</p>
                    </div>
                @endif

                @if ($errors->any())
                    <div role="alert" class="mb-8 flex items-start gap-3 rounded-lg border border-danger-200 bg-danger-50 px-4 py-3 text-danger-700">
                        <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                        <div class="text-[0.9375rem]">
                            <p class="font-medium">Please fix the following before saving:</p>
                            <ul class="mt-1 list-inside list-disc space-y-0.5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                @yield('content')

            </div>
        </main>
    </div>
</div>
</body>
</html>
