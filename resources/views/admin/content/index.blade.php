@extends('admin.layout')

@section('title', 'Site content')

@section('content')

    <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="page-title">Site content</h1>
            <p class="mt-3 max-w-xl text-ink-soft">
                Every photo and block of text on the public site. Entries are listed in the order they appear.
            </p>
        </div>
        <a href="{{ route('admin.content.create', $currentSection ? ['section' => $currentSection] : []) }}"
           class="btn-primary self-start whitespace-nowrap sm:self-auto">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            New entry
        </a>
    </div>

    {{-- Area of the site --}}
    <nav aria-label="Areas of the site" class="-mx-4 mt-8 flex gap-2 overflow-x-auto px-4 pb-1 sm:mx-0 sm:flex-wrap sm:overflow-visible sm:px-0">
        <a href="{{ route('admin.content.index') }}"
           @if (! $currentGroup) aria-current="page" @endif
           class="whitespace-nowrap rounded-full border px-4 py-1.5 text-sm font-medium transition-colors {{ ! $currentGroup ? 'border-forest-900 bg-forest-900 text-sand-50' : 'border-sand-300 bg-white text-ink-soft hover:border-forest-700 hover:text-forest-900' }}">
            All <span class="ml-1 opacity-70">{{ $totalEntries }}</span>
        </a>
        @foreach ($groups as $group)
            <a href="{{ route('admin.content.index', ['group' => $group['key']]) }}"
               @if ($currentGroup && $currentGroup['key'] === $group['key']) aria-current="page" @endif
               class="whitespace-nowrap rounded-full border px-4 py-1.5 text-sm font-medium transition-colors {{ $currentGroup && $currentGroup['key'] === $group['key'] ? 'border-forest-900 bg-forest-900 text-sand-50' : 'border-sand-300 bg-white text-ink-soft hover:border-forest-700 hover:text-forest-900' }}">
                {{ $group['label'] }} <span class="ml-1 opacity-70">{{ $group['total'] }}</span>
            </a>
        @endforeach
    </nav>

    {{-- Sections inside the chosen area --}}
    @if ($currentGroup && count($currentGroup['sections']) > 1)
        <nav aria-label="Sections in {{ $currentGroup['label'] }}" class="mt-4 flex flex-wrap items-center gap-x-5 gap-y-1 text-sm">
            <a href="{{ route('admin.content.index', ['group' => $currentGroup['key']]) }}"
               @if (! $currentSection) aria-current="page" @endif
               class="border-b-2 py-1 {{ ! $currentSection ? 'border-gold-600 font-medium text-forest-900' : 'border-transparent text-ink-soft hover:text-forest-900' }}">
                All sections
            </a>
            @foreach ($currentGroup['sections'] as $section)
                <a href="{{ route('admin.content.index', ['group' => $currentGroup['key'], 'section' => $section['name']]) }}"
                   @if ($currentSection === $section['name']) aria-current="page" @endif
                   class="border-b-2 py-1 {{ $currentSection === $section['name'] ? 'border-gold-600 font-medium text-forest-900' : 'border-transparent text-ink-soft hover:text-forest-900' }}">
                    {{ $section['label'] }}
                </a>
            @endforeach
        </nav>
    @endif

    <div class="mt-8 space-y-10">
        @forelse ($blocks as $block)
            <section aria-labelledby="section-{{ $block['name'] }}">
                <div class="mb-3 flex items-baseline justify-between gap-4">
                    <h2 id="section-{{ $block['name'] }}" class="section-title">
                        {{ $block['label'] }}
                        <span class="ml-1 font-sans text-sm font-normal text-ink-soft">{{ count($block['items']) }}</span>
                    </h2>
                    <a href="{{ route('admin.content.create', ['section' => $block['name']]) }}"
                       class="text-sm font-medium text-forest-700 hover:text-forest-900 hover:underline">Add entry</a>
                </div>

                <ul role="list" class="divide-y divide-sand-200 overflow-hidden rounded-xl border border-sand-300 bg-white">
                    @foreach ($block['items'] as $item)
                        <li class="flex flex-wrap items-center gap-x-4 gap-y-3 px-4 py-3 sm:flex-nowrap">
                            <a href="{{ route('admin.content.edit', $item) }}" class="shrink-0" tabindex="-1" aria-hidden="true">
                                @if ($item->image)
                                    <img src="{{ asset($item->image) }}" alt="" loading="lazy"
                                         class="h-14 w-20 rounded-md border border-sand-200 bg-sand-100 object-cover">
                                @else
                                    <span class="flex h-14 w-20 items-center justify-center rounded-md bg-sand-100 text-ink-soft">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6.5h16M4 12h16M4 17.5h10"/></svg>
                                    </span>
                                @endif
                            </a>

                            <div class="min-w-0 flex-1 basis-40">
                                <a href="{{ route('admin.content.edit', $item) }}" class="block truncate font-medium text-forest-900 hover:underline">{{ $item->display_title }}</a>
                                @if ($item->excerpt)
                                    <p class="truncate text-sm text-ink-soft">{{ $item->excerpt }}</p>
                                @endif
                            </div>

                            <div class="flex w-full items-center justify-between gap-4 sm:w-auto sm:justify-end">
                                <span class="inline-flex w-20 items-center gap-2 text-sm {{ $item->active ? 'text-forest-800' : 'text-ink-soft' }}">
                                    <span class="h-2 w-2 rounded-full {{ $item->active ? 'bg-forest-700' : 'bg-sand-300' }}"></span>
                                    {{ $item->active ? 'Visible' : 'Hidden' }}
                                </span>

                                <div class="flex items-center gap-1">
                                    <a href="{{ route('admin.content.edit', $item) }}" class="btn-secondary px-3 py-1.5">Edit</a>
                                    <form action="{{ route('admin.content.destroy', $item) }}" method="POST"
                                          onsubmit="return confirm('Delete this entry? This cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-quiet-danger">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </section>
        @empty
            <div class="rounded-xl border border-dashed border-sand-300 bg-white px-6 py-14 text-center">
                <p class="font-medium text-forest-900">No entries here yet</p>
                <p class="mt-1 text-ink-soft">Add an entry to show new content on the site.</p>
                <a href="{{ route('admin.content.create', $currentSection ? ['section' => $currentSection] : []) }}" class="btn-primary mt-5">New entry</a>
            </div>
        @endforelse
    </div>

@endsection
