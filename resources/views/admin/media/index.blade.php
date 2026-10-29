@extends('admin.layout')

@section('title', 'Media library')

@php
    $chip = fn (bool $active) => 'inline-flex items-center gap-1.5 rounded-full border px-3 py-1.5 text-sm transition-colors '
        .($active ? 'border-forest-900 bg-forest-900 text-sand-50' : 'border-sand-300 bg-white text-forest-900 hover:border-forest-700');

    $badge = [
        'in_use' => 'bg-forest-50 text-forest-800',
        'default' => 'bg-gold-400/20 text-gold-600',
        'hidden' => 'bg-sand-200 text-ink-soft',
        'unused' => 'bg-danger-50 text-danger-600',
    ];
@endphp

@section('content')

    <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="page-title">Media library</h1>
            <p class="mt-3 max-w-2xl text-ink-soft">
                Every photo, icon, symbol and logo on the site, newest first. The label on each picture tells you whether the live site is using it right now.
            </p>
        </div>
        <a href="{{ route('admin.content.index') }}" class="btn-secondary self-start whitespace-nowrap sm:self-auto">Edit site content</a>
    </div>

    {{-- Filters --}}
    <div class="mt-8 space-y-4">
        <div class="flex flex-wrap items-center gap-2">
            <span class="mr-1 text-sm font-medium text-forest-900">Type</span>
            <a href="{{ request()->fullUrlWithQuery(['type' => null]) }}" class="{{ $chip(! $type) }}">All <span class="opacity-70">{{ $total }}</span></a>
            @foreach ($types as $key => $label)
                <a href="{{ request()->fullUrlWithQuery(['type' => $key]) }}" class="{{ $chip($type === $key) }}">{{ $label }} <span class="opacity-70">{{ $typeCounts[$key] ?? 0 }}</span></a>
            @endforeach
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <span class="mr-1 text-sm font-medium text-forest-900">Status</span>
            <a href="{{ request()->fullUrlWithQuery(['status' => null]) }}" class="{{ $chip(! $status) }}">All</a>
            @foreach ($statuses as $key => $label)
                <a href="{{ request()->fullUrlWithQuery(['status' => $key]) }}" class="{{ $chip($status === $key) }}">{{ $label }} <span class="opacity-70">{{ $statusCounts[$key] ?? 0 }}</span></a>
            @endforeach
        </div>

        <form method="GET" action="{{ route('admin.media.index') }}" class="flex flex-wrap items-center gap-3">
            @if ($type) <input type="hidden" name="type" value="{{ $type }}"> @endif
            @if ($status) <input type="hidden" name="status" value="{{ $status }}"> @endif
            <input type="search" name="q" value="{{ $search }}" placeholder="Search by file name" class="field-input !mt-0 max-w-xs" aria-label="Search by file name">
            <select name="sort" class="field-input !mt-0 w-auto" aria-label="Sort">
                <option value="newest" @selected($sort === 'newest')>Newest first</option>
                <option value="oldest" @selected($sort === 'oldest')>Oldest first</option>
                <option value="name" @selected($sort === 'name')>File name</option>
            </select>
            <button type="submit" class="btn-secondary">Apply</button>
            @if ($search !== '' || $sort !== 'newest')
                <a href="{{ request()->fullUrlWithQuery(['q' => null, 'sort' => null]) }}" class="text-sm text-ink-soft underline">Clear</a>
            @endif
        </form>
    </div>

    <p class="mt-6 text-sm text-ink-soft">
        Showing {{ $items->count() }} of {{ $total }} {{ \Illuminate\Support\Str::plural('file', $total) }}.
        “In use now” covers pictures in active admin entries and pictures built into the page design. Symbols the code picks by name may not be detected.
    </p>

    @if ($items->isEmpty())
        <div class="mt-4 rounded-xl border border-dashed border-sand-300 bg-white px-6 py-14 text-center">
            <p class="font-medium text-forest-900">No pictures match</p>
            <p class="mt-1 text-ink-soft">Try another type or status.</p>
        </div>
    @else
        <ul role="list" class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4">
            @foreach ($items as $file)
                <li class="flex flex-col overflow-hidden rounded-xl border border-sand-300 bg-white">
                    <a href="{{ asset($file['path']) }}" target="_blank" rel="noopener"
                       class="block h-44 border-b border-sand-200 p-3"
                       style="background-image:conic-gradient(#efeae0 25%,#fff 0 50%,#efeae0 0 75%,#fff 0);background-size:16px 16px"
                       title="Open full size">
                        <img src="{{ asset($file['path']) }}" alt="{{ $file['name'] }}" loading="lazy" decoding="async" class="h-full w-full object-contain">
                    </a>

                    <div class="flex flex-1 flex-col gap-2 p-4">
                        <div class="flex flex-wrap items-center gap-1.5">
                            <span class="rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $badge[$file['status']] }}">{{ $statuses[$file['status']] }}</span>
                            <span class="rounded-full bg-sand-100 px-2.5 py-0.5 text-xs text-ink-soft">{{ rtrim($types[$file['type']], 's') }}</span>
                            @if ($file['is_new'])
                                <span class="rounded-full bg-gold-400 px-2.5 py-0.5 text-xs font-semibold text-forest-900">New this week</span>
                            @endif
                            @if ($file['newer'])
                                <span class="rounded-full bg-sand-200 px-2.5 py-0.5 text-xs text-ink-soft" title="{{ $file['versions'] }} uploads of this picture; a newer one exists">Older version</span>
                            @elseif ($file['versions'] > 1)
                                <span class="rounded-full bg-forest-50 px-2.5 py-0.5 text-xs text-forest-800" title="{{ $file['versions'] }} uploads of this picture; this is the newest">Latest version</span>
                            @endif
                        </div>

                        <p class="select-all break-all text-sm font-medium text-forest-900">{{ $file['path'] }}</p>

                        <p class="text-xs text-ink-soft">
                            Uploaded {{ $file['uploaded']->format('M j, Y · g:i A') }}<br>
                            @if ($file['width']) {{ $file['width'] }} × {{ $file['height'] }} px · @endif
                            {{ \Illuminate\Support\Number::fileSize($file['bytes']) }} · {{ strtoupper($file['extension']) }}
                        </p>

                        @php($usedCount = count($file['admin_uses']) + count($file['code_uses']) + count($file['default_uses']))
                        @if ($usedCount > 0)
                            <details class="mt-auto pt-1 text-sm">
                                <summary class="cursor-pointer text-forest-800 underline-offset-2 hover:underline">Where it is used ({{ $usedCount }})</summary>
                                <ul class="mt-2 space-y-1 text-ink-soft">
                                    @foreach ($file['admin_uses'] as $use)
                                        <li>
                                            <a href="{{ route('admin.content.edit', $use['id']) }}" class="text-forest-800 underline-offset-2 hover:underline">{{ $use['section'] }} · {{ \Illuminate\Support\Str::limit($use['title'], 40) }}</a>
                                            <span class="text-xs">({{ $use['field'] }}@if (! $use['active']), hidden @endif)</span>
                                        </li>
                                    @endforeach
                                    @foreach ($file['code_uses'] as $use)
                                        <li><span class="text-xs">Built into the design: {{ $use }}</span></li>
                                    @endforeach
                                    @foreach ($file['default_uses'] as $use)
                                        <li><span class="text-xs">Starting design: {{ $use }}</span></li>
                                    @endforeach
                                </ul>
                            </details>
                        @else
                            <p class="mt-auto pt-1 text-sm text-ink-soft">Nothing on the site uses this file.</p>
                        @endif
                    </div>
                </li>
            @endforeach
        </ul>
    @endif

@endsection
