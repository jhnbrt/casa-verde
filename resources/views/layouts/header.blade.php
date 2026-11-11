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