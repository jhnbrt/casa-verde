{{--
    AI chat bubble + counter-offer popup, on every public page that includes the footer.
    Content and rules come from the admin (Chat & offers); behaviour is in resources/js/widgets.js.
--}}
@php
    $cvw = \App\Support\SiteWidgets::data();
    $cvwImg = fn (?string $path) => $path ? asset($path) : null;
@endphp

@if ($cvw['chat'] || $cvw['offer'])
    @vite(['resources/css/widgets.css', 'resources/js/widgets.js'])

    <script type="application/json" id="cvw-config">{!! json_encode([
        'chat' => $cvw['chat'] ? ['url' => $cvw['chat']['url'], 'name' => $cvw['chat']['name'], 'greeting' => $cvw['chat']['greeting']] : null,
        'offer' => $cvw['offer'] ? ['link' => \App\Support\SiteWidgets::link($cvw['offer']['button_url'])] : null,
        'rules' => $cvw['rules'],
        'csrf' => csrf_token(),
    ], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
@endif

@if ($cvw['chat'])
    <div class="cvw-chat" data-cvw-chat>
        <section id="cvw-chat-panel" class="cvw-panel" role="dialog" aria-label="{{ $cvw['chat']['name'] }}" hidden>
            <header class="cvw-panel__head">
                @if ($cvw['chat']['avatar'])
                    <img src="{{ asset($cvw['chat']['avatar']) }}" alt="" width="38" height="38" class="cvw-panel__avatar">
                @endif
                <div class="cvw-panel__who">
                    <strong>{{ $cvw['chat']['name'] }}</strong>
                    <span>{{ $cvw['chat']['mode'] === 'ai' ? 'AI assistant' : 'Instant answers' }}</span>
                </div>
                <button type="button" class="cvw-panel__close" data-cvw-chat-close aria-label="Close chat">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18"/></svg>
                </button>
            </header>

            <div class="cvw-log" role="log" aria-live="polite" data-cvw-log></div>

            @if ($cvw['chat']['suggestions'])
                <div class="cvw-chips" data-cvw-chips>
                    @foreach ($cvw['chat']['suggestions'] as $suggestion)
                        <button type="button" data-cvw-chip>{{ $suggestion }}</button>
                    @endforeach
                </div>
            @endif

            <form class="cvw-form" data-cvw-form autocomplete="off">
                <input type="text" name="message" maxlength="500" placeholder="Type your question..." aria-label="Your message" data-cvw-input>
                <button type="submit" aria-label="Send message">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 12 20 4l-4 16-4-6-8-2Z"/></svg>
                </button>
            </form>

            <p class="cvw-note">
                @if ($cvw['chat']['mode'] === 'ai')
                    AI assistant: answers may not always be accurate. Please confirm bookings with our team.
                @else
                    Automatic answers. For anything else, send an enquiry and our team will reply personally.
                @endif
            </p>
        </section>

        <button type="button" class="cvw-toggle" data-cvw-chat-toggle aria-expanded="false" aria-controls="cvw-chat-panel">
            @if ($cvw['chat']['icon'])
                <img src="{{ asset($cvw['chat']['icon']) }}" alt="" width="24" height="24">
            @else
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 5h16v11H9l-5 4V5Z"/></svg>
            @endif
            <span>{{ $cvw['chat']['button_text'] }}</span>
        </button>
    </div>
@endif

@if ($cvw['offer'])
    <dialog class="cvw-offer" data-cvw-offer aria-labelledby="cvw-offer-title">
        <div class="cvw-offer__card">
            <button type="button" class="cvw-offer__close" data-cvw-offer-close aria-label="Close">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18"/></svg>
            </button>

            @if ($cvw['offer']['image'])
                <div class="cvw-offer__photo"><img src="{{ asset($cvw['offer']['image']) }}" alt="" loading="lazy"></div>
            @endif

            <div class="cvw-offer__body">
                <p class="cvw-offer__label">{{ $cvw['offer']['label'] }}</p>
                <h2 id="cvw-offer-title">{{ $cvw['offer']['title'] }}</h2>
                <p class="cvw-offer__text">{{ $cvw['offer']['text'] }}</p>

                @if ($cvw['offer']['code'] !== '')
                    <div class="cvw-offer__code">
                        <span data-cvw-code>{{ $cvw['offer']['code'] }}</span>
                        <button type="button" data-cvw-copy>Copy code</button>
                    </div>
                @endif

                <a class="cvw-offer__cta" href="{{ \App\Support\SiteWidgets::link($cvw['offer']['button_url']) }}" data-cvw-offer-cta>{{ $cvw['offer']['button_text'] }}</a>
                <button type="button" class="cvw-offer__decline" data-cvw-offer-close>{{ $cvw['offer']['decline'] }}</button>
            </div>
        </div>
    </dialog>
@endif
