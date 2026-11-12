{{--
    Menu book: a cover and pages that open like a real book.

    Needs: $id (dom id), $title (name of the book), $cover (image path), $hint (text of the "click here"
    hint), $help (helper line under the open book) and $pages (from App\\Support\\FoodMenu::pages()).
--}}
@php
    // Faces in reading order: the cover, then every page (dish pages built from the admin, or a picture).
    $faces = collect([['type' => 'image', 'image' => $cover, 'alt' => $title.' – cover']])
        ->merge(collect($pages)->map(fn (array $page): array => $page['type'] === 'image'
            ? ['type' => 'image', 'image' => $page['image'], 'alt' => trim((string) $page['title']) !== '' ? $page['title'] : $title]
            : $page))
        ->values();

    // Two faces per sheet: the front (right hand side) and the back (left hand side once turned).
    // chunk(2, false) renumbers every sheet from 0 (the default keeps the faces' own numbers, so a later sheet would have no [0]).
    $sheets = $faces->chunk(2, false)->values();
    $pageCount = $faces->count() - 1;
    // With an odd number of faces the last sheet has a blank back, so it is never turned.
    $turns = $faces->count() % 2 === 0 ? $sheets->count() : $sheets->count() - 1;
@endphp

<div class="mb" id="{{ $id }}" role="dialog" aria-modal="true" aria-label="{{ $title }}" hidden data-mb data-turns="{{ $turns }}" data-sheets="{{ $sheets->count() }}" data-pages="{{ $pageCount }}">
    <div class="mb-backdrop" data-mb-close></div>
    <button type="button" class="mb-close" data-mb-close aria-label="Close menu"><span aria-hidden="true">×</span></button>

    <div class="mb-stage" data-mb-stage>
        <div class="mb-shadow">
            <div class="mb-persp">
                <div class="mb-book is-closed" data-mb-book>
                    <div class="mb-end" aria-hidden="true"></div>
                    <div class="mb-spine" aria-hidden="true"></div>

                    @foreach($sheets as $index => $sheet)
                        <div @class(['mb-sheet', 'mb-sheet--cover' => $index === 0]) data-mb-sheet>
                            <div class="mb-face mb-front">
                                @if($index > 0)<span class="mb-page-number mb-page-number--right" aria-hidden="true">{{ $index * 2 }}</span>@endif
                                @include('partials.menu-face', ['face' => $sheet[0], 'lazy' => $index > 1])
                            </div>
                            <div class="mb-face mb-back">
                                @if(isset($sheet[1]))
                                    <span class="mb-page-number mb-page-number--left" aria-hidden="true">{{ $index * 2 + 1 }}</span>
                                    @include('partials.menu-face', ['face' => $sheet[1], 'lazy' => $index > 1])
                                @endif
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>

            {{-- Cover hint: sits on the closed cover and goes away once the book is open --}}
            <button type="button" class="mb-hint" data-mb-open aria-label="{{ $hint }}: {{ $title }}">
                <span class="mb-hint-label">
                    <span>{{ $hint }}</span>
                </span>
            </button>

            {{-- Click a page to turn it --}}
            <button type="button" class="mb-zone mb-zone-prev" data-mb-prev tabindex="-1" aria-label="Previous page"></button>
            <button type="button" class="mb-zone mb-zone-next" data-mb-next tabindex="-1" aria-label="Next page"></button>
        </div>

        <div class="mb-controls" data-mb-controls>
            <button type="button" class="mb-arrow" data-mb-prev aria-label="Previous page"><svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 5-7 7 7 7"/></svg></button>
            <span class="mb-count" data-mb-count aria-live="polite"></span>
            <button type="button" class="mb-arrow" data-mb-next aria-label="Next page"><svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 5 7 7-7 7"/></svg></button>
        </div>
    </div>

    {{-- Phones: after the cover opens, the pages are read by scrolling --}}
    <div class="mb-scroll" data-mb-scroll hidden>
        @foreach($faces->slice(1) as $face)
            <div class="mb-scroll-page">@include('partials.menu-face', ['face' => $face, 'lazy' => true])</div>
        @endforeach
    </div>
</div>
