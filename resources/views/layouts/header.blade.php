@php
    $cvVer = fn ($p) => file_exists(public_path($p)) ? filemtime(public_path($p)) : 1;
    // One logo, edited in the admin (Site-wide): header, button badges, footer.
    $cvLogo = \App\Models\HomeContent::siteLogo();
@endphp
<style>:root { --cv-logo: url('{{ asset($cvLogo) }}?v={{ $cvVer($cvLogo) }}'); }</style>

@php
    $routeFor = [
        'villas'      => 'villas.index',
        'experiences' => 'experiences.index',
        'wellness'    => 'wellness.index',
        'dining'      => 'dining.index',
        'long stay'   => 'longstay.index',
        'reviews'     => 'reviews.index',
        'yoga reset'  => 'yoga.index',
        'contact'     => 'contact.index',
        'about'       => 'about.index',
    ];

    // Menu items that share one dropdown. Each stays its own row in the admin (Site-wide > Navigation: rename,
    // reorder, switch on or off); the dropdown appears where the first of them is, and with fewer than two
    // switched-on items they are shown as plain links.
    $navGroup = ['label' => 'DISCOVER', 'slugs' => ['reviews', 'journal']];

    $resolveNav = function ($nav) use ($routeFor) {
        $slug = strtolower(trim($nav->title));

        if (isset($routeFor[$slug])) {
            return [
                route($routeFor[$slug]),
                request()->routeIs($routeFor[$slug], str_replace('.index', '.*', $routeFor[$slug])),
            ];
        }

        // Section anchors (#about, #contact...) live on the home page, so they must work from every other page too.
        $href = str_starts_with((string) $nav->button_url, '#') ? url('/') . $nav->button_url : (string) $nav->button_url;
        $path = trim((string) parse_url($href, PHP_URL_PATH), '/');

        return [$href, $path !== '' && request()->is($path, $path.'/*')];
    };

    $navRows = $contents->get('navigation', collect())->values();
    $groupRows = $navRows->filter(fn ($nav) => in_array(strtolower(trim($nav->title)), $navGroup['slugs'], true))->values();
    $groupFirst = $groupRows->count() >= 2 ? $groupRows->first()->id : null;

    // Pages that open from a dropdown under a menu item (the item itself still has its own page).
    $subNav = [];

    // The link text comes from the admin (Winterresidenz > Heading > "Menu link text"); null = page switched off.
    $winterMenu = \App\Support\WinterResidence::menuLabel();

    if ($winterMenu !== null) {
        $subNav['long stay'] = [
            ['title' => 'Long Stay', 'href' => route('longstay.index'), 'active' => request()->routeIs('longstay.*')],
            ['title' => $winterMenu, 'href' => route('winter.index'), 'active' => request()->routeIs('winter.*')],
        ];
    }
@endphp

<header class="navbar">

    <a href="{{ url('/') }}" class="logo-area" translate="no" aria-label="Casa Verde Cliff Resort & Spa – home">

        @if($site)
            <img src="{{ asset($cvLogo) }}?v={{ $cvVer($cvLogo) }}" alt="" width="42" height="42">

            <span class="logo-text">
                <span>{{ $site->title }}</span>
                <small>{{ $site->subtitle }}</small>
            </span>
        @endif

    </a>

    <button
        type="button"
        class="nav-toggle"
        aria-controls="primary-nav"
        aria-expanded="false"
        aria-label="Open menu"
    >
        <span></span>
    </button>

    <nav id="primary-nav" class="nav-links" aria-label="Main">

        @foreach($navRows as $nav)

            @if($groupFirst !== null && in_array(strtolower(trim($nav->title)), $navGroup['slugs'], true))
                @if($nav->id === $groupFirst)
                    @php
                        $groupItems = $groupRows->map(function ($row) use ($resolveNav) {
                            [$gHref, $gActive] = $resolveNav($row);

                            return ['title' => $row->title, 'href' => $gHref, 'active' => $gActive];
                        });
                        $groupActive = $groupItems->contains('active', true);
                    @endphp
                    <div class="nav-item nav-item--sub" data-nav-sub>
                        <a href="{{ $groupItems->first()['href'] }}" @class(['is-active' => $groupActive]) @if($groupActive) aria-current="page" @endif>{{ \Illuminate\Support\Str::upper($navGroup['label']) }}</a>
                        <button type="button" class="nav-sub-toggle" aria-expanded="false" aria-controls="nav-sub-discover" aria-label="Show {{ $navGroup['label'] }} pages" data-nav-sub-toggle><span aria-hidden="true"></span></button>
                        <div class="nav-sub" id="nav-sub-discover">
                            @foreach($groupItems as $sub)
                                <a href="{{ $sub['href'] }}" @class(['is-active' => $sub['active']]) @if($sub['active']) aria-current="page" @endif>{{ $sub['title'] }}</a>
                            @endforeach
                        </div>
                    </div>
                @endif
                @continue
            @endif

            @php
                $slug = strtolower(trim($nav->title));
                [$href, $active] = $resolveNav($nav);
            @endphp

            @if(isset($subNav[$slug]))
                @php $subActive = collect($subNav[$slug])->contains('active', true); @endphp
                <div class="nav-item nav-item--sub" data-nav-sub>
                    <a
                        href="{{ $href }}"
                        @class(['is-active' => $subActive])
                        @if($active) aria-current="page" @endif
                    >
                        {{ $nav->title }}
                    </a>
                    <button type="button" class="nav-sub-toggle" aria-expanded="false" aria-controls="nav-sub-{{ \Illuminate\Support\Str::slug($slug) }}" aria-label="Show {{ $nav->title }} pages" data-nav-sub-toggle><span aria-hidden="true"></span></button>
                    <div class="nav-sub" id="nav-sub-{{ \Illuminate\Support\Str::slug($slug) }}">
                        @foreach($subNav[$slug] as $sub)
                            <a href="{{ $sub['href'] }}" @class(['is-active' => $sub['active']]) @if($sub['active']) aria-current="page" @endif>{{ $loop->first ? $nav->title : $sub['title'] }}</a>
                        @endforeach
                    </div>
                </div>
            @else
                <a
                    href="{{ $href }}"
                    @class(['is-active' => $active])
                    @if($active) aria-current="page" @endif
                >
                    {{ $nav->title }}
                </a>
            @endif

        @endforeach

        {{-- Shown inside the mobile menu only --}}
        <a href="{{ route('booking.index') }}" class="book-button nav-book-mobile">
            <span class="book-icon" aria-hidden="true"><img src="{{ asset('images/logo-badge.png') }}" alt="" class="cv-booking-logo"></span>
            Book your stay
        </a>

    </nav>

    @include('layouts.language')

    <a href="{{ route('booking.index') }}" class="book-button nav-book-desktop">
        <span class="book-icon" aria-hidden="true"><img src="{{ asset('images/logo-badge.png') }}" alt="" class="cv-booking-logo"></span>
        Book your stay
    </a>

</header>
<script>window.cvFitNav && window.cvFitNav();</script>
<style>
/* A short, even fade between pages (browsers without cross-page transitions just load the page as usual).
   The header is deliberately NOT given its own transition layer: an open dropdown makes that layer a different
   size from the one on the next page, and the header visibly jumped. */
@view-transition { navigation: auto; }
::view-transition-group(root) { animation-duration: .2s; }
@media (prefers-reduced-motion: reduce) { @view-transition { navigation: none; } }

/* The moment a menu link is clicked, any open dropdown closes at once, so it cannot hang open (or reopen under
   the mouse) while the next page loads. */
.navbar.is-leaving .nav-sub { opacity: 0 !important; visibility: hidden !important; pointer-events: none; transition: none !important; }
</style>
<script>
(function () {
    var bar = document.querySelector('.navbar');
    if (!bar) return;

    var timer = null;

    bar.addEventListener('click', function (e) {
        var link = e.target.closest ? e.target.closest('a[href]') : null;

        // Only a click that really opens another page: not a new tab, not a link to a place on this same page.
        if (!link || e.defaultPrevented || e.button || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
        if (link.target && link.target !== '_self') return;
        if (link.origin !== location.origin || (link.pathname === location.pathname && link.search === location.search)) return;

        bar.classList.add('is-leaving');
        clearTimeout(timer);
        timer = setTimeout(function () { bar.classList.remove('is-leaving'); }, 1500);   // in case the visit is cancelled
    });

    // Coming back with the browser's Back button shows the page as it was left: open the dropdowns again.
    window.addEventListener('pageshow', function () { bar.classList.remove('is-leaving'); });
})();
</script>
<div id="cv-currency-control" class="cv-currency-control" aria-label="Display currency">
  <label for="cv-currency-select">Currency</label>
  <select id="cv-currency-select" aria-label="Choose display currency">
    @foreach(['PHP','USD','EUR','GBP','AUD','CAD','JPY','SGD','KRW','CNY','HKD','AED','INR','NZD'] as $code)
      <option value="{{ $code }}">{{ $code }}</option>
    @endforeach
  </select>
</div>
<style>
.cv-currency-control{position:fixed;bottom:18px;right:18px;z-index:900;background:#fff;color:#18372b;box-shadow:0 3px 20px #0002;border:1px solid #d7ded8;border-radius:12px;padding:9px 12px;display:flex;align-items:center;gap:9px;font:600 12px system-ui,sans-serif}
.cv-currency-control select{background:transparent;color:inherit;border:0;font:600 13px system-ui,sans-serif;cursor:pointer;max-width:85px}
@media(max-width:600px){.cv-currency-control{bottom:10px;right:10px;padding:7px 9px}}
</style>
<script defer src="{{ asset('js/casa-currency.js') }}?v=1" data-rates-url="{{ route('currency.rates') }}"></script>
