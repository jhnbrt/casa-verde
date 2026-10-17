@php
    $home = url('/');
    $href = fn (?string $url) => \Illuminate\Support\Str::startsWith($url ?? '', ['http', '#', 'mailto:', 'tel:']) ? $url : url($url ?: '/');
    $logo = $footer['brand']['image'] ?? 'images/logo.png';
    // The built-in gold placeholder pictures (images/icons/placeholders/NAME.svg) cannot take the
    // footer colours when shown as an image, so they appear as the matching drawing instead,
    // coloured by the page. An icon the admin uploads is shown exactly as uploaded.
    $footerIcon = fn (?string $value) => preg_match('#^images/icons/placeholders/([a-z0-9-]+)\.svg$#i', trim((string) $value), $match) ? $match[1] : $value;
@endphp

@if(($footer['cta'] ?? null) && ! ($hideCta ?? false))
    <section class="cvf-cta">
        <h2>{{ $footer['cta']['title'] }}</h2>
        <div class="cvf-rule"><i></i><img src="{{ asset($logo) }}" alt="" width="34" height="34"><i></i></div>
        @if($footer['cta']['button_text'])
            <a href="{{ $href($footer['cta']['button_url']) }}" class="cvf-btn">{{ $footer['cta']['button_text'] }}</a>
        @endif
    </section>
@endif

<footer class="cvf" id="footer">
    <div class="cvf-grid">

        <div class="cvf-col cvf-brand">
            <img src="{{ asset($logo) }}" alt="Casa Verde lotus logo" width="64" height="64">
            <strong>{{ $footer['brand']['title'] }}</strong>
            <small>{{ $footer['brand']['subtitle'] }}</small>
        </div>

        <nav class="cvf-col" aria-label="Explore">
            <h3>Explore</h3>
            @foreach($footer['explore'] as $link)
                <a href="{{ $href($link['url']) }}">{{ $link['title'] }}</a>
            @endforeach
        </nav>

        <nav class="cvf-col" aria-label="Useful links">
            <h3>Useful Links</h3>
            @foreach($footer['useful'] as $link)
                <a href="{{ $href($link['url']) }}">{{ $link['title'] }}</a>
            @endforeach
        </nav>

        <div class="cvf-col">
            <h3>Contact</h3>
            @foreach($footer['contact'] as $line)
                <p class="cvf-line">
                    <x-icon :name="$line['icon'] ?: 'pin'" />
                    @if($line['url'])
                        <a href="{{ $href($line['url']) }}">{!! nl2br(e($line['title'])) !!}</a>
                    @else
                        <span>{!! nl2br(e($line['title'])) !!}</span>
                    @endif
                </p>
            @endforeach
        </div>

        <div class="cvf-col">
            <h3>Stay Connected</h3>
            <div class="cvf-social">
                @foreach($footer['social'] as $social)
                    <a href="{{ $href($social['url']) }}" aria-label="{{ $social['title'] }}"><x-icon :name="$footerIcon($social['icon'])" :size="22" style="color:#06251d !important;stroke:#06251d !important;opacity:1 !important" /></a>
                @endforeach
            </div>
            @if($footer['newsletter']['title'])
                <h3 class="cvf-sub">{{ $footer['newsletter']['title'] }}</h3>
            @endif
            @if($footer['newsletter']['text'])
                <p>{{ $footer['newsletter']['text'] }}</p>
            @endif
            <form class="cvf-news" action="#" onsubmit="return false">
                <input type="email" placeholder="Your email address" aria-label="Email address">
                <button type="submit" aria-label="Subscribe"><x-icon :name="$footerIcon(\App\Models\HomeContent::siteIcon('arrow'))" :size="22" /></button>
            </form>
        </div>

    </div>

    <div class="cvf-bottom">
        <span class="cvf-legal">
            @foreach($footer['legal'] as $link)
                @if(! $loop->first)<b></b>@endif
                <a href="{{ $href($link['url']) }}">{{ $link['title'] }}</a>
            @endforeach
        </span>
        <span>&copy; {{ date('Y') }} {{ $footer['bottom'] }}</span>
    </div>
</footer>
