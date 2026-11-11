{{--
    Menu book: a cover and pages that open like a real book.

    Needs: $id (dom id), $title (name of the book), $cover (image path), $hint (text of the "click here"
    hint), $help (helper line under the open book) and $pages (rows with ->image and ->title).
--}}
@php
    $faces = collect([['src' => $cover, 'alt' => $title.' – cover']])
        ->merge(collect($pages)->map(fn ($page): array => ['src' => $page->image, 'alt' => trim((string) $page->title) !== '' ? $page->title : $title]))
        ->values();

    // Two faces per sheet: the front (right hand side) and the back (left hand side once turned).
    $sheets = $faces->chunk(2)->map(fn ($sheet) => $sheet->values())->values();
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
                                <img src="{{ asset($sheet[0]['src']) }}" alt="{{ $sheet[0]['alt'] }}" draggable="false" @if($index > 1) loading="lazy" @endif>
                            </div>
                            <div class="mb-face mb-back">
                                @if(isset($sheet[1]))
                                    <img src="{{ asset($sheet[1]['src']) }}" alt="{{ $sheet[1]['alt'] }}" draggable="false" @if($index > 1) loading="lazy" @endif>
                                @endif
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>

            {{-- Cover hint: sits on the closed cover and goes away once the book is open --}}
            <button type="button" class="mb-hint" data-mb-open aria-label="Open the {{ $title }}">
                <span class="mb-hint-label">
                    <svg class="mb-hint-hand" viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 11V5.5a1.5 1.5 0 0 1 3 0V10m0-1.5a1.5 1.5 0 0 1 3 0V11m0-1a1.5 1.5 0 0 1 3 0v4.5a6.5 6.5 0 0 1-6.5 6.5h-1A6 6 0 0 1 6 17.5L4.4 14a1.4 1.4 0 0 1 2.3-1.5L9 14.5V11"/></svg>
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
        @if(trim((string) $help) !== '')
            <p class="mb-help" data-mb-help>{{ $help }}</p>
        @endif
    </div>

    {{-- Phones: after the cover opens, the pages are read by scrolling --}}
    <div class="mb-scroll" data-mb-scroll hidden>
        @foreach($faces->slice(1) as $face)
            <img src="{{ asset($face['src']) }}" alt="{{ $face['alt'] }}" loading="lazy">
        @endforeach
    </div>
</div>
