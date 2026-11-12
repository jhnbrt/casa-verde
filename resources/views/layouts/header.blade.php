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

        @foreach($contents->get('navigation', collect()) as $nav)

            @php
                $slug = strtolower(trim($nav->title));

                if (isset($routeFor[$slug])) {
                    $href   = route($routeFor[$slug]);
                    $active = request()->routeIs($routeFor[$slug], str_replace('.index', '.*', $routeFor[$slug]));
                } else {
                    // Section anchors (#about, #contact...) live on the home page,
                    // so they must work from every other page too.
                    $href   = str_starts_with((string) $nav->button_url, '#')
                        ? url('/') . $nav->button_url
                        : $nav->button_url;
                    $active = false;
                }
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
