@extends('admin.layout')

@section('title', 'Gallery photos')

@php
    $thumb = function (?string $path) {
        $path = ltrim((string) $path, '/');

        return $path !== '' && file_exists(public_path($path)) ? asset($path) : null;
    };

    // Same checkerboard as the Media library, so transparent pictures are easy to see.
    $checker = 'background-image:conic-gradient(#efeae0 25%,#fff 0 50%,#efeae0 0 75%,#fff 0);background-size:16px 16px';
    // A saved icon is either a picture path or a built-in icon name such as "cave".
    $iconSrc = function (?string $icon) use ($thumb) {
        $icon = trim((string) $icon);

        if ($icon === '') {
            return null;
        }

        foreach ([$icon, "images/icons/placeholders/{$icon}.svg", "images/icons/{$icon}.png"] as $candidate) {
            if ($src = $thumb($candidate)) {
                return $src;
            }
        }

        return null;
    };
    $fileInput = 'block w-full text-sm text-ink-soft file:mr-3 file:cursor-pointer file:rounded-lg file:border-0 file:bg-sand-200 file:px-3 file:py-2 file:text-sm file:font-medium file:text-forest-900 hover:file:bg-sand-300';
@endphp

@section('content')

    <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="page-title">Gallery photos</h1>
            <p class="mt-3 max-w-2xl text-ink-soft">
                Every photo on the Gallery page in one place. Choose a new picture on any photo and press <strong>Save</strong> to change it. The old picture file stays in the Media library.
            </p>
        </div>
        <a href="{{ url('/gallery') }}" target="_blank" rel="noopener" class="btn-secondary self-start whitespace-nowrap sm:self-auto">View Gallery page</a>
    </div>

    @if ($errors->any())
        <div role="alert" class="mt-6 rounded-lg border border-danger-200 bg-danger-50 px-4 py-3 text-danger-700">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- Jump links --}}
    <nav class="mt-8 flex flex-wrap gap-2" aria-label="Gallery sections">
        <a href="#overview" class="rounded-full border border-sand-300 bg-white px-3 py-1.5 text-sm text-forest-900 hover:border-forest-700">Overview tiles</a>
        @foreach ($sections as $section)
            <a href="#cat-{{ $section['key'] }}" class="rounded-full border border-sand-300 bg-white px-3 py-1.5 text-sm text-forest-900 hover:border-forest-700">{{ $section['title'] }} <span class="opacity-60">{{ $section['photos']->count() }}</span></a>
        @endforeach
        <a href="#banner" class="rounded-full border border-sand-300 bg-white px-3 py-1.5 text-sm text-forest-900 hover:border-forest-700">Booking banner</a>
    </nav>

    {{-- Overview mosaic --}}
    <section id="overview" class="mt-10 scroll-mt-24">
        <h2 class="text-xl font-semibold text-forest-900">Overview tiles</h2>
        <p class="mt-1 text-ink-soft">The {{ $overview->count() }} tiles at the top of the Gallery page. Each tile keeps its category; change the photo or the label.</p>

        <ul role="list" class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4">
            @foreach ($overview as $tile)
                <li class="flex flex-col overflow-hidden rounded-xl border border-sand-300 bg-white">
                    <div class="h-40 border-b border-sand-200 p-2" style="{{ $checker }}">
                        @if ($src = $thumb($tile->image))
                            <img src="{{ $src }}" alt="{{ $tile->title }}" loading="lazy" decoding="async" class="h-full w-full object-contain">
                        @else
                            <p class="flex h-full items-center justify-center text-sm text-ink-soft">Picture file not found</p>
                        @endif
                    </div>

                    <form method="POST" action="{{ route('admin.gallery.update', $tile) }}" enctype="multipart/form-data" class="flex flex-1 flex-col gap-3 p-4">
                        @csrf @method('PUT')
                        <p class="text-xs font-semibold uppercase tracking-wide text-ink-soft">Tile {{ $loop->iteration }}</p>
                        <label class="block">
                            <span class="field-label">Label on the tile</span>
                            <input type="text" name="title" value="{{ $tile->title }}" maxlength="160" class="field-input">
                        </label>
                        <label class="block">
                            <span class="field-label">New photo</span>
                            <input type="file" name="image" accept="image/jpeg,image/png,image/webp,image/gif" class="{{ $fileInput }}">
                        </label>
                        <button type="submit" class="btn-primary mt-auto self-start">Save</button>
                    </form>
                </li>
            @endforeach
        </ul>
    </section>

    {{-- Categories --}}
    @foreach ($sections as $section)
        <section id="cat-{{ $section['key'] }}" class="mt-12 scroll-mt-24">
            <h2 class="text-xl font-semibold text-forest-900">{{ $section['title'] }} <span class="text-base font-normal text-ink-soft">· {{ $section['photos']->count() }} {{ \Illuminate\Support\Str::plural('photo', $section['photos']->count()) }}</span></h2>
            <p class="mt-1 text-ink-soft">Shown on the Gallery page in this order. Photo 1 is the largest in the layout. Use the arrows to reorder.</p>

            <ul role="list" class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4">
                @foreach ($section['photos'] as $photo)
                    <li class="flex flex-col overflow-hidden rounded-xl border border-sand-300 bg-white">
                        <div class="h-40 border-b border-sand-200 p-2" style="{{ $checker }}">
                            @if ($src = $thumb($photo->image))
                                <img src="{{ $src }}" alt="{{ $photo->title }}" loading="lazy" decoding="async" class="h-full w-full object-contain">
                            @else
                                <p class="flex h-full items-center justify-center text-sm text-ink-soft">Picture file not found</p>
                            @endif
                        </div>

                        <form method="POST" action="{{ route('admin.gallery.update', $photo) }}" enctype="multipart/form-data" class="flex flex-col gap-3 p-4 pb-2">
                            @csrf @method('PUT')
                            <p class="text-xs font-semibold uppercase tracking-wide text-ink-soft">Photo {{ $loop->iteration }}</p>
                            <label class="block">
                                <span class="field-label">Caption</span>
                                <input type="text" name="title" value="{{ $photo->title }}" maxlength="160" class="field-input">
                            </label>
                            @if ($section['labelled'])
                                <label class="block">
                                    <span class="field-label">Label under the photo</span>
                                    <input type="text" name="subtitle" value="{{ $photo->subtitle }}" maxlength="60" class="field-input">
                                </label>
                            @endif
                            <label class="block">
                                <span class="field-label">New photo</span>
                                <input type="file" name="image" accept="image/jpeg,image/png,image/webp,image/gif" class="{{ $fileInput }}">
                            </label>
                            @if ($section['labelled'])
                                <div>
                                    <span class="field-label">Label icon</span>
                                    <div class="mt-1 flex items-center gap-3">
                                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-sand-200 bg-sand-50 p-1.5">
                                            @if ($src = $iconSrc($photo->icon))
                                                <img src="{{ $src }}" alt="" class="h-full w-full object-contain">
                                            @endif
                                        </span>
                                        <input type="file" name="icon_image" accept="image/png,image/jpeg,image/webp,image/gif,image/svg+xml" class="{{ $fileInput }}" aria-label="New label icon">
                                    </div>
                                </div>
                            @endif
                            <button type="submit" class="btn-primary self-start">Save</button>
                        </form>

                        <div class="mt-auto flex items-center gap-2 border-t border-sand-200 px-4 py-3">
                            <form method="POST" action="{{ route('admin.gallery.move', $photo) }}">
                                @csrf <input type="hidden" name="direction" value="up">
                                <button type="submit" class="btn-quiet" aria-label="Move photo {{ $loop->iteration }} earlier" @disabled($loop->first)>↑</button>
                            </form>
                            <form method="POST" action="{{ route('admin.gallery.move', $photo) }}">
                                @csrf <input type="hidden" name="direction" value="down">
                                <button type="submit" class="btn-quiet" aria-label="Move photo {{ $loop->iteration }} later" @disabled($loop->last)>↓</button>
                            </form>
                            <form method="POST" action="{{ route('admin.gallery.destroy', $photo) }}" class="ml-auto" onsubmit="return confirm('Remove this photo from the gallery?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-sm text-danger-600 underline-offset-2 hover:underline">Remove</button>
                            </form>
                        </div>
                    </li>
                @endforeach

                {{-- Add a photo --}}
                <li class="flex flex-col rounded-xl border border-dashed border-sand-300 bg-sand-50">
                    <form method="POST" action="{{ route('admin.gallery.store') }}" enctype="multipart/form-data" class="flex flex-1 flex-col gap-3 p-4">
                        @csrf
                        <input type="hidden" name="category" value="{{ $section['key'] }}">
                        <p class="text-sm font-semibold text-forest-900">Add a photo to {{ $section['title'] }}</p>
                        <label class="block">
                            <span class="field-label">Photo</span>
                            <input type="file" name="image" required accept="image/jpeg,image/png,image/webp,image/gif" class="{{ $fileInput }}">
                        </label>
                        <label class="block">
                            <span class="field-label">Caption</span>
                            <input type="text" name="title" maxlength="160" class="field-input" placeholder="Describe the photo">
                        </label>
                        @if ($section['labelled'])
                            <label class="block">
                                <span class="field-label">Label under the photo <span class="font-normal text-ink-soft">(optional)</span></span>
                                <input type="text" name="subtitle" maxlength="60" class="field-input">
                            </label>
                        @endif
                        <button type="submit" class="btn-secondary mt-auto self-start">Add photo</button>
                    </form>
                </li>
            </ul>
        </section>
    @endforeach

    {{-- Booking banner --}}
    <section id="banner" class="mt-12 scroll-mt-24">
        <h2 class="text-xl font-semibold text-forest-900">Booking banner</h2>
        <p class="mt-1 text-ink-soft">The "Ready to experience it in person?" bar at the bottom of the Gallery page. Colours, the button link and showing or hiding the bar are in Site content &rsaquo; Gallery.</p>

        <form method="POST" action="{{ route('admin.gallery.update', $banner) }}" enctype="multipart/form-data" class="mt-4 grid max-w-3xl gap-4 rounded-xl border border-sand-300 bg-white p-4 sm:grid-cols-2">
            @csrf @method('PUT')
            <div class="sm:col-span-2">
                <span class="field-label">Banner icon</span>
                <div class="mt-1 flex items-center gap-3">
                    <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-lg border border-sand-200 bg-sand-50 p-2">
                        @if ($src = $iconSrc($banner->icon))
                            <img src="{{ $src }}" alt="" class="h-full w-full object-contain">
                        @endif
                    </span>
                    <input type="file" name="icon_image" accept="image/png,image/jpeg,image/webp,image/gif,image/svg+xml" class="{{ $fileInput }}" aria-label="New banner icon">
                </div>
            </div>
            <label class="block">
                <span class="field-label">Heading</span>
                <input type="text" name="title" value="{{ $banner->title }}" maxlength="160" class="field-input">
            </label>
            <label class="block">
                <span class="field-label">Button text</span>
                <input type="text" name="button_text" value="{{ $banner->button_text }}" maxlength="60" class="field-input" placeholder="Book your stay">
            </label>
            <div class="sm:col-span-2">
                <button type="submit" class="btn-primary">Save</button>
            </div>
        </form>

        <p class="mt-4 text-sm text-ink-soft">The small arrows ("Back to gallery", "Next") are shared symbols; change them in Site content &rsaquo; Site-wide &rsaquo; symbols.</p>
    </section>

@endsection
