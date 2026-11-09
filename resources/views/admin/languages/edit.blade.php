@extends('admin.layout')

@section('title', 'Languages')

@section('content')

    <div>
        <h1 class="page-title">Languages</h1>
        <p class="mt-3 max-w-2xl text-ink-soft">
            Visitors from other countries can read the website in their own language. They choose it from the globe menu in the header,
            or leave it on <strong>Automatic</strong> and the site picks a language from where they are.
            Pages are translated automatically, including everything you edit here in the admin, so there is nothing to translate by hand.
        </p>
    </div>

    <form method="POST" action="{{ route('admin.languages.update') }}" class="mt-8 max-w-3xl space-y-8">
        @csrf
        @method('PUT')

        <fieldset class="rounded-xl border border-sand-300 bg-white p-5">
            <legend class="px-2 text-sm font-medium text-forest-900">Language menu</legend>

            <label class="flex items-start gap-3">
                <input type="checkbox" name="show" value="1" class="mt-1 h-4 w-4" @checked($settings['show'])>
                <span>
                    <span class="block font-medium text-forest-900">Show the language menu in the website header</span>
                    <span class="block text-sm text-ink-soft">Turn this off to hide the menu and keep the site in English only.</span>
                </span>
            </label>

            <label class="mt-5 flex items-start gap-3">
                <input type="checkbox" name="auto" value="1" class="mt-1 h-4 w-4" @checked($settings['auto'])>
                <span>
                    <span class="block font-medium text-forest-900">Pick the language automatically on a visitor's first visit</span>
                    <span class="block text-sm text-ink-soft">
                        By the visitor's country (a visitor from Korea sees Korean), then by the language of their browser, otherwise English.
                        Visitors can always change it, and their choice is remembered.
                    </span>
                </span>
            </label>

            <label class="mt-5 flex items-start gap-3">
                <input type="checkbox" name="ip_lookup" value="1" class="mt-1 h-4 w-4" @checked($settings['ip_lookup'])>
                <span>
                    <span class="block font-medium text-forest-900">Find the visitor's country from their internet (IP) address</span>
                    <span class="block text-sm text-ink-soft">
                        Needed for "by country" unless your host already tells us (Cloudflare does). A free lookup service is asked once per visitor
                        and the answer is kept for a day. Turn this off if you prefer not to send visitors' IP addresses to a third party:
                        the site then uses the language of their browser instead.
                    </span>
                </span>
            </label>
        </fieldset>

        <fieldset class="rounded-xl border border-sand-300 bg-white p-5">
            <legend class="px-2 text-sm font-medium text-forest-900">Languages offered</legend>
            <p class="mb-4 text-sm text-ink-soft">English is the language of the website and is always offered. Tick the others you want visitors to choose from.</p>

            <div class="grid gap-3 sm:grid-cols-2">
                @foreach ($supported as $code => $language)
                    <label class="flex items-center gap-3 rounded-lg border border-sand-200 px-3 py-2">
                        <input type="checkbox" name="languages[]" value="{{ $code }}" class="h-4 w-4"
                               @checked(in_array($code, $settings['codes'], true)) @disabled($code === 'en')>
                        <span class="text-[0.9375rem]">
                            <span class="font-medium text-forest-900">{{ $language['native'] }}</span>
                            @if ($language['native'] !== $language['name'])<span class="text-ink-soft"> · {{ $language['name'] }}</span>@endif
                        </span>
                    </label>
                @endforeach
            </div>
        </fieldset>

        <details class="rounded-xl border border-sand-300 bg-white p-5">
            <summary class="cursor-pointer font-medium text-forest-900">Which country gets which language?</summary>
            <p class="mt-3 text-sm text-ink-soft">
                Only for the <strong>Automatic</strong> choice, and only if that language is ticked above. Countries not listed (the Philippines, the USA, the UK...)
                stay in English unless the visitor's browser asks for another language. The list lives in <code>config/languages.php</code>.
            </p>
            <dl class="mt-3 grid grid-cols-2 gap-x-6 gap-y-1 text-sm sm:grid-cols-4">
                @foreach ($countries as $country => $code)
                    <div class="flex justify-between gap-2"><dt class="font-mono">{{ $country }}</dt><dd class="text-ink-soft">{{ $supported[$code]['native'] ?? $code }}</dd></div>
                @endforeach
            </dl>
        </details>

        <div class="flex items-center gap-4">
            <button type="submit" class="btn-primary">Save</button>
            <a href="{{ url('/') }}" target="_blank" rel="noopener" class="btn-secondary">View website</a>
        </div>
    </form>

@endsection
