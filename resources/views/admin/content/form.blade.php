@extends('admin.layout')

@section('title', $item->exists ? 'Edit entry' : 'New entry')

@section('content')

    @php
        $meta = \App\Models\HomeContent::sectionMeta(old('section', $item->section));
        $label = fn (string $field, string $default) => $meta['fields'][$field] ?? $default;
        $show = fn (string $field) => ! in_array($field, $meta['hide'] ?? [], true);
        $hint = fn (string $field) => $meta['hints'][$field] ?? null;
    @endphp

    <a href="{{ route('admin.content.index', $item->section ? ['section' => $item->section] : []) }}"
       class="inline-flex items-center gap-1 text-sm text-forest-700 hover:text-forest-900 hover:underline">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
        Back to {{ $item->section ? $item->section_name : 'site content' }}
    </a>

    <h1 class="page-title mt-4">{{ $item->exists ? $item->display_title : 'New entry' }}</h1>
    <p class="mt-3 text-ink-soft">
        @if ($item->exists)
            Entry in {{ $item->section_name }}. Changes appear on the live site as soon as you save.
        @else
            Add a photo or block of text to the site.
        @endif
    </p>

    @if (! empty($meta['help']))
        <div class="mt-5 flex gap-3 rounded-xl border border-gold-400/40 bg-gold-400/10 px-4 py-3 text-sm text-forest-900" role="note">
            <svg class="mt-0.5 h-5 w-5 shrink-0 text-gold-600" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.04-.02a.75.75 0 011.06.85l-.7 2.8a.75.75 0 001.06.85M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.01"/></svg>
            <p><span class="font-medium">{{ $item->section_name }}.</span> {{ $meta['help'] }}</p>
        </div>
    @endif

    <form method="POST"
          action="{{ $item->exists ? route('admin.content.update', $item) : route('admin.content.store') }}"
          enctype="multipart/form-data"
          class="mt-8 grid gap-6 pb-8 lg:grid-cols-[minmax(0,1fr)_20rem] lg:items-start">
        @csrf
        @if ($item->exists)
            @method('PUT')
        @endif

        <div class="space-y-6">

            @if ($show('title') || $show('subtitle') || $show('description') || $show('features'))
            <section class="panel" aria-labelledby="text-heading">
                <h2 id="text-heading" class="section-title">Text</h2>

                <div class="mt-5 space-y-5">
                    @if ($show('title'))
                    <div>
                        <label for="title" class="field-label">{{ $label('title', 'Title') }}</label>
                        <textarea id="title" name="title" rows="2" class="field-input">{{ old('title', $item->title) }}</textarea>
                        <p class="field-hint">{{ $hint('title') ?? 'Press Enter to start a new line. Some headings are shown on separate lines on the site.' }}</p>
                    </div>
                    @endif

                    @if ($show('subtitle'))
                    <div>
                        <label for="subtitle" class="field-label">{{ $label('subtitle', 'Subtitle') }}</label>
                        <textarea id="subtitle" name="subtitle" rows="2" class="field-input">{{ old('subtitle', $item->subtitle) }}</textarea>
                        @if ($hint('subtitle'))
                            <p class="field-hint">{{ $hint('subtitle') }}</p>
                        @endif
                    </div>
                    @endif

                    @if ($show('description'))
                    <div>
                        <label for="description" class="field-label">{{ $label('description', 'Description') }}</label>
                        <textarea id="description" name="description" rows="{{ $meta['rows']['description'] ?? 5 }}" class="field-input">{{ old('description', $item->description) }}</textarea>
                    </div>
                    @endif

                    @if ($show('features'))
                    <div>
                        <label for="features" class="field-label">{{ $label('features', 'Features') }}</label>
                        <textarea id="features" name="features" rows="4" placeholder="One feature per line" class="field-input">{{ old('features', is_array($item->features) ? implode("\n", $item->features) : '') }}</textarea>
                        <p class="field-hint">Shown as a bullet list, one item per line, for example the amenities of a villa.</p>
                    </div>
                    @endif
                </div>
            </section>
            @endif

            @if ($show('image'))
            <section class="panel" aria-labelledby="photo-heading">
                <h2 id="photo-heading" class="section-title">{{ $label('image', 'Photo') }}</h2>

                <div class="mt-5 flex flex-col gap-5 sm:flex-row sm:items-start">
                    <div class="aspect-[4/3] w-full shrink-0 overflow-hidden rounded-lg border border-sand-300 bg-sand-100 sm:w-60">
                        <img data-image-preview src="{{ $item->image ? asset($item->image) : '' }}" alt="Current photo"
                             class="h-full w-full object-cover {{ $item->image ? '' : 'hidden' }}">
                        <div data-image-empty class="h-full items-center justify-center text-sm text-ink-soft {{ $item->image ? 'hidden' : 'flex' }}">
                            No photo yet
                        </div>
                    </div>

                    <div>
                        <input type="file" id="image" name="image" accept="image/*,.svg" data-image-input class="peer sr-only">
                        <label for="image" class="btn-secondary cursor-pointer peer-focus-visible:ring-4 peer-focus-visible:ring-gold-400/40">
                            {{ $item->image ? 'Replace photo' : 'Choose photo' }}
                        </label>
                        <p data-image-name class="mt-3 text-sm text-forest-800" aria-live="polite"></p>
                        <p class="field-hint">JPG, PNG, WebP or SVG, up to 5 MB. A new photo replaces the current one.</p>
                    </div>
                </div>
            </section>
            @endif

            @if (! empty($meta['gallery']))
            @php
                $galleryPhotos = array_values(array_filter((array) old('gallery', $item->gallery ?? []), 'is_string'));
            @endphp
            <section class="panel" aria-labelledby="gallery-heading" data-gallery-box>
                <h2 id="gallery-heading" class="section-title">Gallery photos</h2>
                <p class="field-hint">These photos slide open, one after another, when a visitor clicks the picture of this villa. The first photo opens in the middle. Add as many as you like (8 looks best).</p>

                <input type="hidden" name="gallery_present" value="1">

                <ul data-gallery-list class="mt-5 grid gap-4 sm:grid-cols-2 xl:grid-cols-3" aria-label="Gallery photos, in the order visitors see them">
                    @foreach ($galleryPhotos as $path)
                        <li data-gallery-item class="overflow-hidden rounded-lg border border-sand-300 bg-white">
                            <input type="hidden" name="gallery[]" value="{{ $path }}">
                            <div class="relative aspect-[4/3] bg-sand-100">
                                <img src="{{ asset($path) }}" alt="" class="h-full w-full object-cover" loading="lazy">
                                <span data-gallery-number class="absolute left-2 top-2 rounded-full bg-forest-900/80 px-2.5 py-0.5 text-xs font-medium text-white">{{ $loop->iteration }}</span>
                            </div>
                            <div class="flex items-center justify-between gap-2 px-2.5 py-2">
                                <div class="flex gap-1.5">
                                    <button type="button" data-gallery-move="-1" class="inline-flex h-8 w-8 items-center justify-center rounded-full border border-sand-300 bg-white text-forest-800 hover:bg-sand-100 focus-visible:ring-4 focus-visible:ring-gold-400/40" aria-label="Move this photo earlier">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                                    </button>
                                    <button type="button" data-gallery-move="1" class="inline-flex h-8 w-8 items-center justify-center rounded-full border border-sand-300 bg-white text-forest-800 hover:bg-sand-100 focus-visible:ring-4 focus-visible:ring-gold-400/40" aria-label="Move this photo later">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                                    </button>
                                </div>
                                <button type="button" data-gallery-remove class="rounded-full px-3 py-1 text-sm text-red-700 hover:bg-red-50 focus-visible:ring-4 focus-visible:ring-red-200">Remove</button>
                            </div>
                        </li>
                    @endforeach
                </ul>

                <p data-gallery-empty class="mt-5 rounded-lg border border-dashed border-sand-300 px-4 py-6 text-center text-sm text-ink-soft {{ count($galleryPhotos) ? 'hidden' : '' }}">
                    No gallery photos yet. Add some below. Until you do, the villa uses its card photo and the photos that came with the site.
                </p>

                <div class="mt-6 border-t border-sand-300 pt-5">
                    <input type="file" id="gallery_new" name="gallery_new[]" multiple accept="image/jpeg,image/png,image/webp,image/gif" data-gallery-input class="peer sr-only">
                    <label for="gallery_new" class="btn-secondary cursor-pointer peer-focus-visible:ring-4 peer-focus-visible:ring-gold-400/40">Add photos</label>
                    <p data-gallery-new-note class="mt-3 text-sm text-forest-800" aria-live="polite"></p>
                    <div data-gallery-new-preview class="mt-3 grid gap-3 sm:grid-cols-3 xl:grid-cols-4"></div>
                    <p class="field-hint">Choose one or several photos (JPG, PNG, WebP or GIF, up to 5 MB each, up to 20 at a time). Landscape photos at least 900 px wide look best. New photos are added at the end; save, then use the arrows to put them in order. If saving fails, add fewer photos at a time.</p>
                    @error('gallery_new.*')<p class="mt-2 text-sm text-red-700" role="alert">{{ $message }}</p>@enderror
                    @error('gallery_new')<p class="mt-2 text-sm text-red-700" role="alert">{{ $message }}</p>@enderror
                </div>

                <p class="field-hint mt-4">Remove, add and reorder as you like, then press Save changes. Nothing changes on the live site until you save.</p>
            </section>
            @endif

            @if ($show('button_text') || $show('button_url'))
            <section class="panel" aria-labelledby="button-heading">
                <h2 id="button-heading" class="section-title">{{ $show('button_text') ? 'Button' : 'Link' }}</h2>
                @if ($show('button_text'))
                    <p class="field-hint">Leave both empty if this entry has no button.</p>
                @endif

                <div class="mt-4 grid gap-5 sm:grid-cols-2">
                    @if ($show('button_text'))
                    <div>
                        <label for="button_text" class="field-label">{{ $label('button_text', 'Button text') }}</label>
                        <input type="text" id="button_text" name="button_text" value="{{ old('button_text', $item->button_text) }}" class="field-input">
                    </div>
                    @endif
                    @if ($show('button_url'))
                    <div>
                        <label for="button_url" class="field-label">{{ $label('button_url', 'Button link') }}</label>
                        <input type="text" id="button_url" name="button_url" value="{{ old('button_url', $item->button_url) }}" class="field-input">
                        @if ($hint('button_url'))
                            <p class="field-hint">{{ $hint('button_url') }}</p>
                        @endif
                    </div>
                    @endif
                </div>
            </section>
            @endif
        </div>

        <aside class="space-y-6">
            <section class="panel" aria-labelledby="settings-heading">
                <h2 id="settings-heading" class="section-title">Settings</h2>

                <div class="mt-5 space-y-5">
                    <label class="flex cursor-pointer items-start gap-3">
                        <input type="hidden" name="active" value="0">
                        <input type="checkbox" id="active" name="active" value="1" class="peer sr-only"
                               {{ old('active', $item->active ?? true) ? 'checked' : '' }}>
                        <span class="relative mt-0.5 h-6 w-11 shrink-0 rounded-full bg-sand-300 transition-colors after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:shadow-sm after:transition-transform peer-checked:bg-forest-700 peer-checked:after:translate-x-5 peer-focus-visible:ring-4 peer-focus-visible:ring-gold-400/40"></span>
                        <span>
                            <span class="block text-sm font-medium text-forest-900">Show on the live site</span>
                            <span class="block text-sm text-ink-soft">Turn off to hide this entry without deleting it.</span>
                        </span>
                    </label>

                    <div>
                        <label for="section" class="field-label">Section</label>
                        <input type="text" id="section" name="section" list="sections" value="{{ old('section', $item->section) }}" required class="field-input">
                        <datalist id="sections">
                            @foreach ($sections as $section)
                                <option value="{{ $section }}"></option>
                            @endforeach
                        </datalist>
                        <p class="field-hint">Entries with the same section appear together, for example "villas" or "wellness".</p>
                    </div>

                    <div>
                        <label for="sort_order" class="field-label">Position</label>
                        <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $item->sort_order ?? 0) }}" class="field-input">
                        <p class="field-hint">Lower numbers appear first.</p>
                    </div>

                    @if ($show('price'))
                    <div>
                        <label for="price" class="field-label">{{ $label('price', 'Price') }}</label>
                        <input type="number" step="0.01" id="price" name="price" value="{{ old('price', $item->price) }}" class="field-input">
                    </div>
                    @endif

                    @if (true) {{-- Every content entry can have its own admin-managed icon or SVG. --}}
                    @php
                        $iconIsImage = (bool) preg_match('/\.(png|jpe?g|webp|gif|svg)$/i', (string) old('icon', $item->icon));
                    @endphp
                    <div>
                        <label for="icon" class="field-label">{{ $label('icon', 'Icon') }}</label>

                        @if ($iconIsImage)
                            <div class="mb-3 flex items-center gap-3">
                                <span class="flex h-14 w-14 items-center justify-center rounded-lg border border-sand-300 bg-sand-100">
                                    <img src="{{ asset(old('icon', $item->icon)) }}" alt="Current icon image" class="h-10 w-10 object-contain">
                                </span>
                                <span class="text-sm text-ink-soft">Current icon image</span>
                            </div>
                        @endif

                        <input type="file" id="icon_image" name="icon_image" accept="image/png,image/jpeg,image/webp,image/gif,image/svg+xml" class="field-input">
                        <p class="field-hint">Upload an icon image (PNG, static SVG, JPG or WebP, up to 2 MB). A square image with a transparent background looks best. It replaces the current icon.</p>

                        <label for="icon" class="field-label mt-4">Or type an emoji / icon name</label>
                        <input type="text" id="icon" name="icon" value="{{ old('icon', $item->icon) }}" placeholder="e.g. 🌿 or lotus" class="field-input">
                        <p class="field-hint">{{ $hint('icon') ?? 'An emoji (🌿) or an icon name (lotus, leaf, island). Upload a file above to replace the icon; clear this box to remove it (when no new file is selected).' }}</p>
                    </div>
                    @endif
                </div>
            </section>
        </aside>

        <div class="sticky bottom-0 z-10 -mx-4 flex items-center gap-3 border-t border-sand-300 bg-sand-100/95 px-4 py-4 backdrop-blur sm:-mx-8 sm:px-8 lg:-mx-12 lg:col-span-2 lg:px-12">
            <button type="submit" class="btn-primary px-6">{{ $item->exists ? 'Save changes' : 'Create entry' }}</button>
            <a href="{{ route('admin.content.index', $item->section ? ['section' => $item->section] : []) }}" class="btn-secondary">Cancel</a>
        </div>
    </form>

@endsection
