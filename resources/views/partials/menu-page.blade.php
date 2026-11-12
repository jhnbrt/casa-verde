{{--
    A menu page built from the admin: small label + heading, up to four dishes (photo left or right, alternating),
    icon legend and footer. Everything shown here (texts, photos, icons, footer) comes from the admin:
    "Food menu book: cover / categories / dishes / icons".
--}}
@php
    $dishCount = count($page['dishes']);
    $brand = $page['brand'] ?? ['text' => 'Casa Verde Cliff Resort & Spa', 'icon' => ''];
    $logo = trim((string) ($brand['icon'] ?? '')) !== '' ? $brand['icon'] : 'lotus';
@endphp
<div class="mbp" data-count="{{ $dishCount }}">
    @if(trim((string) ($page['kicker'] ?? '')) !== '')
        <p class="mbp-kicker">{{ $page['kicker'] }}</p>
    @endif
    @if(trim((string) $page['heading']) !== '')
        <h3 class="mbp-title">{{ $page['heading'] }}</h3>
    @endif
    <div class="mbp-rule" aria-hidden="true">@include('partials.menu-ornament')</div>

    <div class="mbp-dishes">
        @foreach($page['dishes'] as $dish)
            <article class="mbp-dish">
                <div class="mbp-photo mbp-photo--{{ $dish['shape'] ?? 'round' }}">
                    @if($dish['image'])
                        <img src="{{ asset($dish['image']) }}" alt="{{ $dish['name'] }}" loading="lazy" decoding="async" draggable="false">
                    @endif
                </div>
                <div class="mbp-text">
                    <h4 class="mbp-name">
                        <span>{{ $dish['name'] }}@if($dish['price'] !== '') <span class="mbp-price">{{ $dish['price'] }}</span>@endif</span>
                        @foreach($dish['icons'] as $icon)
                            @if($icon['image'])
                                <img class="mbp-diet mbp-diet--img" src="{{ asset($icon['image']) }}" alt="{{ $icon['label'] }}" draggable="false">
                            @else
                                <svg class="mbp-diet" viewBox="0 0 24 24" fill="currentColor" aria-label="{{ $icon['label'] }}" role="img">{!! $icon['svg'] !!}</svg>
                            @endif
                        @endforeach
                    </h4>
                    @if(trim((string) $dish['text']) !== '')
                        <p class="mbp-desc">{{ $dish['text'] }}</p>
                    @endif
                </div>
            </article>
        @endforeach
    </div>

    <ul class="mbp-legend" aria-hidden="true">
        @foreach($page['legend'] ?? [] as $icon)
            <li>
                @if($icon['image'])
                    <img src="{{ asset($icon['image']) }}" alt="" draggable="false">
                @else
                    <svg viewBox="0 0 24 24" fill="currentColor">{!! $icon['svg'] !!}</svg>
                @endif
                <span>{{ $icon['label'] }}</span>
            </li>
        @endforeach
    </ul>
    <div class="mbp-rule mbp-rule--foot" aria-hidden="true">@include('partials.menu-ornament')</div>
    <div class="mbp-brand">
        <x-icon :name="$logo" :size="48" class="mbp-logo" />
        <span>{{ $brand['text'] }}</span>
    </div>
</div>
