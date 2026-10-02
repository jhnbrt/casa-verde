@php
    $cvVer = fn ($p) => file_exists(public_path($p)) ? filemtime(public_path($p)) : 1;
@endphp
<style>:root { --cv-logo: url('{{ asset('images/logo.png') }}?v={{ $cvVer('images/logo.png') }}'); }</style>

@php
    $routeFor = [
        'villas'      => 'villas.index',
        'experiences' => 'experiences.index',
        'wellness'    => 'wellness.index',
        'dining'      => 'dining.index',
        'long stay'   => 'longstay.index',
    ];
@endphp

<header class="navbar">

    <a href="{{ url('/') }}" class="logo-area" aria-label="Casa Verde Cliff Resort & Spa – home">

        @if($site)
            <img src="{{ asset($site->image) }}?v={{ $cvVer($site->image) }}" alt="" width="42" height="42">

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

            <a
                href="{{ $href }}"
                @class(['is-active' => $active])
                @if($active) aria-current="page" @endif
            >
                {{ $nav->title }}
            </a>

        @endforeach

        {{-- Shown inside the mobile menu only --}}
        <a href="{{ url('/') }}#contact" class="book-button nav-book-mobile">
            <span class="book-icon" aria-hidden="true"></span>
            Book your stay
        </a>

    </nav>

    <a href="{{ url('/') }}#contact" class="book-button nav-book-desktop">
        <span class="book-icon" aria-hidden="true"></span>
        Book your stay
    </a>

</header>