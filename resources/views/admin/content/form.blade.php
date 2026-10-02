@extends('admin.layout')

@section('title', $item->exists ? 'Edit entry' : 'New entry')

@section('content')

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

    <form method="POST"
          action="{{ $item->exists ? route('admin.content.update', $item) : route('admin.content.store') }}"
          enctype="multipart/form-data"
          class="mt-8 grid gap-6 lg:grid-cols-[minmax(0,1fr)_20rem] lg:items-start">
        @csrf
        @if ($item->exists)
            @method('PUT')
        @endif

        <div class="space-y-6">

            <section class="panel" aria-labelledby="text-heading">
                <h2 id="text-heading" class="section-title">Text</h2>

                <div class="mt-5 space-y-5">
                    <div>
                        <label for="title" class="field-label">Title</label>
                        <input type="text" id="title" name="title" value="{{ old('title', $item->title) }}" class="field-input">
                    </div>

                    <div>
                        <label for="subtitle" class="field-label">Subtitle</label>
                        <textarea id="subtitle" name="subtitle" rows="2" class="field-input">{{ old('subtitle', $item->subtitle) }}</textarea>
                    </div>

                    <div>
                        <label for="description" class="field-label">Description</label>
                        <textarea id="description" name="description" rows="5" class="field-input">{{ old('description', $item->description) }}</textarea>
                    </div>

                    <div>
                        <label for="features" class="field-label">Features</label>
                        <textarea id="features" name="features" rows="4" placeholder="One feature per line" class="field-input">{{ old('features', is_array($item->features) ? implode("\n", $item->features) : '') }}</textarea>
                        <p class="field-hint">Shown as a bullet list, one item per line, for example the amenities of a villa.</p>
                    </div>
                </div>
            </section>

            <section class="panel" aria-labelledby="photo-heading">
                <h2 id="photo-heading" class="section-title">Photo</h2>

                <div class="mt-5 flex flex-col gap-5 sm:flex-row sm:items-start">
                    <div class="aspect-[4/3] w-full shrink-0 overflow-hidden rounded-lg border border-sand-300 bg-sand-100 sm:w-60">
                        <img data-image-preview src="{{ $item->image ? asset($item->image) : '' }}" alt="Current photo"
                             class="h-full w-full object-cover {{ $item->image ? '' : 'hidden' }}">
                        <div data-image-empty class="h-full items-center justify-center text-sm text-ink-soft {{ $item->image ? 'hidden' : 'flex' }}">
                            No photo yet
                        </div>
                    </div>

                    <div>
                        <input type="file" id="image" name="image" accept="image/*" data-image-input class="peer sr-only">
                        <label for="image" class="btn-secondary cursor-pointer peer-focus-visible:ring-4 peer-focus-visible:ring-gold-400/40">
                            {{ $item->image ? 'Replace photo' : 'Choose photo' }}
                        </label>
                        <p data-image-name class="mt-3 text-sm text-forest-800" aria-live="polite"></p>
                        <p class="field-hint">JPG, PNG or WebP, up to 5 MB. A new photo replaces the current one.</p>
                    </div>
                </div>
            </section>

            <section class="panel" aria-labelledby="button-heading">
                <h2 id="button-heading" class="section-title">Button</h2>
                <p class="field-hint">Leave both empty if this entry has no button.</p>

                <div class="mt-4 grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="button_text" class="field-label">Button text</label>
                        <input type="text" id="button_text" name="button_text" value="{{ old('button_text', $item->button_text) }}" class="field-input">
                    </div>
                    <div>
                        <label for="button_url" class="field-label">Button link</label>
                        <input type="text" id="button_url" name="button_url" value="{{ old('button_url', $item->button_url) }}" class="field-input">
                    </div>
                </div>
            </section>
        </div>

        <aside class="space-y-6 lg:sticky lg:top-8">
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

                    <div>
                        <label for="price" class="field-label">Price</label>
                        <input type="number" step="0.01" id="price" name="price" value="{{ old('price', $item->price) }}" class="field-input">
                    </div>

                    <div>
                        <label for="icon" class="field-label">Icon</label>
                        <input type="text" id="icon" name="icon" value="{{ old('icon', $item->icon) }}" placeholder="e.g. an emoji or icon class" class="field-input">
                    </div>
                </div>
            </section>
        </aside>

        <div class="sticky bottom-0 z-10 -mx-4 flex items-center gap-3 border-t border-sand-300 bg-sand-100/95 px-4 py-4 backdrop-blur sm:-mx-8 sm:px-8 lg:-mx-12 lg:col-span-2 lg:px-12">
            <button type="submit" class="btn-primary px-6">{{ $item->exists ? 'Save changes' : 'Create entry' }}</button>
            <a href="{{ route('admin.content.index', $item->section ? ['section' => $item->section] : []) }}" class="btn-secondary">Cancel</a>
        </div>
    </form>

@endsection
