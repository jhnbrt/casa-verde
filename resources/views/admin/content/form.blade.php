@extends('admin.layout')

@section('title', $item->exists ? 'Edit Entry' : 'New Entry')

@section('content')

    <div class="mb-6">
        <a href="{{ route('admin.content.index', $item->section ? ['section' => $item->section] : []) }}"
           class="text-sm text-slate-500 hover:text-slate-800">← Back to list</a>
        <h1 class="mt-2 text-2xl font-semibold text-slate-900">
            {{ $item->exists ? 'Edit entry' : 'New entry' }}
        </h1>
        @if ($item->exists)
            <p class="mt-1 text-sm text-slate-500">ID #{{ $item->id }} · section “{{ $item->section }}”</p>
        @endif
    </div>

    <form method="POST"
          action="{{ $item->exists ? route('admin.content.update', $item) : route('admin.content.store') }}"
          enctype="multipart/form-data"
          class="space-y-8">
        @csrf
        @if ($item->exists)
            @method('PUT')
        @endif

        <div class="bg-white rounded-xl border border-slate-200 p-6 grid grid-cols-1 sm:grid-cols-2 gap-6">

            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-1">Section</label>
                <input type="text" name="section" list="sections" value="{{ old('section', $item->section) }}" required
                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-slate-900 focus:ring-1 focus:ring-slate-900 outline-none">
                <datalist id="sections">
                    @foreach ($sections as $section)
                        <option value="{{ $section }}"></option>
                    @endforeach
                </datalist>
                <p class="mt-1 text-xs text-slate-400">
                    Groups this entry with others on the same page block, e.g. "villas", "wellness", "hero".
                </p>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Title</label>
                <input type="text" name="title" value="{{ old('title', $item->title) }}"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-slate-900 focus:ring-1 focus:ring-slate-900 outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Icon</label>
                <input type="text" name="icon" value="{{ old('icon', $item->icon) }}" placeholder="e.g. an emoji or icon class"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-slate-900 focus:ring-1 focus:ring-slate-900 outline-none">
            </div>

            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-1">Subtitle</label>
                <textarea name="subtitle" rows="2"
                          class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-slate-900 focus:ring-1 focus:ring-slate-900 outline-none">{{ old('subtitle', $item->subtitle) }}</textarea>
            </div>

            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-1">Description</label>
                <textarea name="description" rows="4"
                          class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-slate-900 focus:ring-1 focus:ring-slate-900 outline-none">{{ old('description', $item->description) }}</textarea>
            </div>

            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-slate-700 mb-1">Features</label>
                <textarea name="features" rows="4" placeholder="One feature per line"
                          class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-slate-900 focus:ring-1 focus:ring-slate-900 outline-none">{{ old('features', is_array($item->features) ? implode("\n", $item->features) : '') }}</textarea>
                <p class="mt-1 text-xs text-slate-400">One item per line, e.g. a bullet list of amenities.</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Price</label>
                <input type="number" step="0.01" name="price" value="{{ old('price', $item->price) }}"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-slate-900 focus:ring-1 focus:ring-slate-900 outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Sort order</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $item->sort_order ?? 0) }}"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-slate-900 focus:ring-1 focus:ring-slate-900 outline-none">
                <p class="mt-1 text-xs text-slate-400">Lower numbers appear first.</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Button text</label>
                <input type="text" name="button_text" value="{{ old('button_text', $item->button_text) }}"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-slate-900 focus:ring-1 focus:ring-slate-900 outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Button URL</label>
                <input type="text" name="button_url" value="{{ old('button_url', $item->button_url) }}"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-slate-900 focus:ring-1 focus:ring-slate-900 outline-none">
            </div>

            <div class="sm:col-span-2 flex items-center gap-2">
                <input type="hidden" name="active" value="0">
                <input type="checkbox" id="active" name="active" value="1"
                       {{ old('active', $item->active ?? true) ? 'checked' : '' }}
                       class="rounded border-slate-300">
                <label for="active" class="text-sm text-slate-700">Visible on the live site</label>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <label class="block text-sm font-medium text-slate-700 mb-3">Image</label>

            <div class="flex flex-col sm:flex-row sm:items-center gap-5">
                <div class="h-28 w-40 rounded-lg border border-slate-200 bg-slate-50 overflow-hidden flex items-center justify-center shrink-0">
                    @if ($item->image)
                        <img src="{{ asset($item->image) }}" alt="" class="h-full w-full object-cover">
                    @else
                        <span class="text-xs text-slate-400">No image</span>
                    @endif
                </div>

                <div>
                    <input type="file" name="image" accept="image/*"
                           class="block text-sm text-slate-600 file:mr-4 file:rounded-lg file:border-0 file:bg-slate-900 file:text-white file:text-sm file:font-medium file:px-4 file:py-2 hover:file:bg-slate-800">
                    <p class="mt-2 text-xs text-slate-400">
                        JPG, PNG or WebP, up to 5MB. Uploading a new image replaces the one shown above.
                    </p>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit"
                    class="rounded-lg bg-slate-900 text-white text-sm font-medium px-5 py-2.5 hover:bg-slate-800 transition">
                {{ $item->exists ? 'Save changes' : 'Create entry' }}
            </button>
            <a href="{{ route('admin.content.index', $item->section ? ['section' => $item->section] : []) }}"
               class="text-sm text-slate-500 hover:text-slate-800">Cancel</a>
        </div>
    </form>

@endsection
