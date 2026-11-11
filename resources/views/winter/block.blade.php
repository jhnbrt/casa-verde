@php
    // Paragraphs of a text: a blank line starts a new one, a single line break stays a line break.
    $winterParagraphs = fn (?string $text): array => array_values(array_filter(
        array_map('trim', preg_split('/\n\s*\n/', str_replace(["\r\n", "\r"], "\n", (string) $text))),
        'strlen',
    ));
    $href = fn (?string $url) => \Illuminate\Support\Str::startsWith($url ?? '', ['http', '#', 'mailto:', 'tel:']) ? $url : url($url ?: '/');
    // The built-in gold placeholder pictures cannot take the page colours when shown as an image,
    // so they appear as the matching drawing, coloured by the page. Uploaded icons show as uploaded.
    $footerIcon = fn (?string $value) => preg_match('#^images/icons/placeholders/([a-z0-9-]+)\.svg$#i', trim((string) $value), $match) ? $match[1] : $value;
    $winterTabs = array_values(array_filter($winter['tabs'] ?? [], fn (array $tab): bool => trim((string) $tab['label']) !== ''));
@endphp

@if($winter && $winterTabs !== [])
<section class="wr wr--page" id="winterresidenz" lang="de" aria-labelledby="wr-title" data-wr>
    <div class="wr-inner">
        <header class="wr-head">
            @if(trim((string) $winter['header']['eyebrow']) !== '')
                <p class="wr-eyebrow">{{ $winter['header']['eyebrow'] }}</p>
            @endif
            <h1 id="wr-title">{{ $winter['header']['title'] }}</h1>
            <x-rule />
            @if(trim((string) $winter['header']['text']) !== '')
                <p class="wr-lead">{{ $winter['header']['text'] }}</p>
            @endif
        </header>

        <div class="wr-tabs" role="tablist" aria-label="{{ $winter['header']['title'] }}">
            @foreach($winterTabs as $tab)
                <button type="button" class="wr-tab" role="tab" id="wr-tab-{{ $tab['key'] }}" aria-controls="wr-panel-{{ $tab['key'] }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}" tabindex="{{ $loop->first ? '0' : '-1' }}" data-wr-tab>{{ $tab['label'] }}</button>
            @endforeach
        </div>

        @foreach($winterTabs as $tab)
            @php
                $hasImage = filled($tab['image']) && file_exists(public_path($tab['image']));
                $hasAside = $hasImage;
                // A line with only --- splits the text: before it above the photo cards, after it below them.
                // Next to a side photo there is no "below", so the text just continues.
                $textParts = preg_split('/\n[ \t]*-{3,}[ \t]*\n/', str_replace(["\r\n", "\r"], "\n", (string) $tab['text']), 2);
                $paragraphs = $winterParagraphs($hasAside ? implode("\n\n", $textParts) : $textParts[0]);
                $paragraphsBelow = $hasAside ? [] : $winterParagraphs($textParts[1] ?? '');
                // Badges with a description form the framed feature strip, and the tab becomes the cover.
                $featureStrip = collect($tab['badges'])->contains(fn (array $badge): bool => trim((string) ($badge['text'] ?? '')) !== '');
                $photoOnly = $tab['cards'] !== [] && collect($tab['cards'])->every(fn (array $card): bool => trim((string) $card['title']) === '' && trim((string) $card['text']) === '');
            @endphp
            <article @class(['wr-panel', 'wr-panel--cover' => $featureStrip]) role="tabpanel" id="wr-panel-{{ $tab['key'] }}" aria-labelledby="wr-tab-{{ $tab['key'] }}" data-wr-panel>
                <div class="wr-body {{ $hasAside ? 'wr-body--split' : '' }}">
                    <div class="wr-copy">
                        @if(trim((string) $tab['heading']) !== '')
                            <h3>{{ $tab['heading'] }}</h3>
                        @endif
                        @foreach($paragraphs as $paragraph)
                            <p>{!! nl2br(e($paragraph)) !!}</p>
                        @endforeach
                        @if(trim((string) $tab['closing']) !== '' && $hasAside)
                            <p class="wr-closing">{{ $tab['closing'] }}</p>
                        @endif
                    </div>
                    @if($hasImage)
                        <figure class="wr-photo"><img src="{{ asset($tab['image']) }}" alt="" loading="lazy"></figure>
                    @endif
                </div>

                @if($tab['cards'] !== [])
                    <ul @class(['wr-cards', 'wr-cards--'.min(count($tab['cards']), 4), 'wr-cards--photos' => $photoOnly])>
                        @foreach($tab['cards'] as $card)
                            <li class="wr-card">
                                @if(trim((string) $card['title']) !== '')
                                    <h4>{{ $card['title'] }}</h4>
                                @endif
                                @if(filled($card['image']) && file_exists(public_path($card['image'])))
                                    <img src="{{ asset($card['image']) }}" alt="{{ $card['title'] ?: $tab['heading'] }}" loading="lazy">
                                @endif
                                @if(trim((string) $card['text']) !== '')
                                    <p>{{ $card['text'] }}</p>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif

                @if($paragraphsBelow !== [])
                    <div class="wr-copy wr-copy--below">
                        @foreach($paragraphsBelow as $paragraph)
                            <p>{!! nl2br(e($paragraph)) !!}</p>
                        @endforeach
                    </div>
                @endif

                @if($tab['lists'] !== [])
                    <div class="wr-lists">
                        @foreach($tab['lists'] as $list)
                            <div class="wr-list">
                                <h4>
                                    @if(trim((string) $list['icon']) !== '')<x-icon :name="$footerIcon($list['icon'])" :size="34" />@endif
                                    <span>{{ $list['title'] }}</span>
                                </h4>
                                <ul>
                                    @foreach($list['items'] as $item)
                                        <li><b aria-hidden="true">{{ $list['marker'] }}</b>{{ $item }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if($tab['badges'] !== [])
                    <ul @class(['wr-badges', 'wr-badges--features' => $featureStrip])>
                        @foreach($tab['badges'] as $badge)
                            <li>
                                @if(trim((string) $badge['icon']) !== '')<x-icon :name="$footerIcon($badge['icon'])" :size="$featureStrip ? 56 : 34" />@endif
                                <strong>{{ $badge['title'] }}</strong>
                                @if(trim((string) ($badge['text'] ?? '')) !== '')
                                    <span class="wr-badge-text">{{ $badge['text'] }}</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif

                @if($tab['ticks'] !== [])
                    {{-- Short lines (Camotes Island, Ihr Aufenthalt) sit in one centred row like on the slides; long ones use two columns. --}}
                    @php $shortTicks = collect($tab['ticks'])->every(fn ($tick): bool => mb_strlen((string) $tick) <= 32); @endphp
                    <ul @class(['wr-ticks', 'wr-ticks--row' => $shortTicks])>
                        @foreach($tab['ticks'] as $tick)
                            <li><b aria-hidden="true">✓</b>{{ $tick }}</li>
                        @endforeach
                    </ul>
                @endif

                @if($tab['prices'] !== [])
                    <div class="wr-prices">
                        @foreach($tab['prices'] as $price)
                            @php
                                $amount = is_numeric($price['price'])
                                    ? number_format((float) $price['price'], ((float) $price['price'] == floor((float) $price['price'])) ? 0 : 2, ',', '.')
                                    : (string) $price['price'];
                                [$before, $after] = array_pad(explode('{price}', (string) $price['format'], 2), 2, '');
                            @endphp
                            <div class="wr-price">
                                <h4>{{ $price['title'] }}</h4>
                                <p class="wr-amount">{{ $before }}<strong>{{ $amount }}</strong>{{ $after }}</p>
                                @if(trim((string) $price['note']) !== '')
                                    <p class="wr-note">{{ $price['note'] }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                @if($tab['contact'] !== [])
                    <div class="wr-contact">
                        @foreach($tab['contact'] as $line)
                            @if(trim((string) $line['icon']) === '')
                                <h4>{{ $line['title'] }}</h4>
                            @else
                                <p class="wr-line">
                                    <x-icon :name="$footerIcon($line['icon'])" :size="24" />
                                    @if($line['url'])
                                        <a href="{{ $href($line['url']) }}" @if(\Illuminate\Support\Str::startsWith($line['url'], 'http')) target="_blank" rel="noopener" @endif>{{ $line['title'] }}</a>
                                    @else
                                        <span>{{ $line['title'] }}</span>
                                    @endif
                                </p>
                            @endif
                        @endforeach
                    </div>
                @endif

                @if(trim((string) $tab['closing']) !== '' && ! $hasAside)
                    <p class="wr-closing wr-closing--bar">{{ $tab['closing'] }}</p>
                @endif
            </article>
        @endforeach
    </div>

    <script>
        (function () {
            var root = document.querySelector('[data-wr]');
            if (!root) { return; }
            var tabs = Array.prototype.slice.call(root.querySelectorAll('[data-wr-tab]'));
            var panels = Array.prototype.slice.call(root.querySelectorAll('[data-wr-panel]'));
            if (!tabs.length || tabs.length !== panels.length) { return; }

            function show(index, focus) {
                tabs.forEach(function (tab, i) {
                    var on = i === index;
                    tab.setAttribute('aria-selected', on ? 'true' : 'false');
                    tab.setAttribute('tabindex', on ? '0' : '-1');
                    panels[i].hidden = !on;
                });
                if (focus) { tabs[index].focus(); }
            }

            tabs.forEach(function (tab, i) {
                tab.addEventListener('click', function () { show(i, false); });
                tab.addEventListener('keydown', function (event) {
                    var next = null;
                    if (event.key === 'ArrowRight') { next = (i + 1) % tabs.length; }
                    if (event.key === 'ArrowLeft') { next = (i - 1 + tabs.length) % tabs.length; }
                    if (event.key === 'Home') { next = 0; }
                    if (event.key === 'End') { next = tabs.length - 1; }
                    if (next !== null) { event.preventDefault(); show(next, true); }
                });
            });

            root.classList.add('is-tabs');
            show(0, false);
        })();
    </script>
</section>
@endif

