@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')

    <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="page-title">Dashboard</h1>
            <p class="mt-3 max-w-xl text-ink-soft">
                {{ $totalEntries }} {{ Str::plural('entry', $totalEntries) }} across {{ $groups->count() }} areas of the site. @if ($totalHidden > 0){{ $totalHidden }} currently hidden from visitors.@endif
            </p>
        </div>
        <a href="{{ route('admin.content.create') }}" class="btn-primary self-start whitespace-nowrap sm:self-auto">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            New entry
        </a>
    </div>

    <section class="mt-10" aria-labelledby="areas-heading">
        <h2 id="areas-heading" class="section-title">Areas of the site</h2>

        @if ($groups->isEmpty())
            <div class="mt-4 rounded-xl border border-dashed border-sand-300 bg-white px-6 py-12 text-center">
                <p class="font-medium text-forest-900">No content yet</p>
                <p class="mt-1 text-ink-soft">Add your first entry to start filling the site.</p>
                <a href="{{ route('admin.content.create') }}" class="btn-primary mt-5">New entry</a>
            </div>
        @else
            <div class="mt-4 grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
                @foreach ($groups as $group)
                    <a href="{{ route('admin.content.index', ['group' => $group['key']]) }}"
                       class="group block overflow-hidden rounded-xl border border-sand-300 bg-white transition-colors hover:border-forest-700">
                        <div class="aspect-[16/10] overflow-hidden bg-forest-900">
                            @if ($group['cover'])
                                <img src="{{ asset($group['cover']) }}" alt="" loading="lazy" class="h-full w-full object-cover">
                            @else
                                <div class="flex h-full items-center justify-center">
                                    <img src="{{ asset('images/logo.png') }}" alt="" class="h-20 w-20">
                                </div>
                            @endif
                        </div>
                        <div class="px-4 py-3.5">
                            <div class="flex items-baseline justify-between gap-3">
                                <h3 class="font-display text-2xl font-semibold text-forest-900 group-hover:underline">{{ $group['label'] }}</h3>
                                <p class="text-sm text-ink-soft">{{ $group['total'] }} {{ Str::plural('entry', $group['total']) }}</p>
                            </div>
                            @if ($group['hidden_total'] > 0)
                                <p class="mt-0.5 text-sm text-gold-700">{{ $group['hidden_total'] }} hidden</p>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </section>

    @if ($recent->isNotEmpty())
        <section class="mt-12" aria-labelledby="recent-heading">
            <h2 id="recent-heading" class="section-title">Recently updated</h2>

            <ul role="list" class="mt-4 divide-y divide-sand-200 overflow-hidden rounded-xl border border-sand-300 bg-white">
                @foreach ($recent as $item)
                    <li>
                        <a href="{{ route('admin.content.edit', $item) }}" class="flex items-center gap-4 px-4 py-3 transition-colors hover:bg-sand-50">
                            @if ($item->image)
                                <img src="{{ asset($item->image) }}" alt="" class="h-12 w-16 shrink-0 rounded-md border border-sand-200 bg-sand-100 object-cover">
                            @else
                                <span class="flex h-12 w-16 shrink-0 items-center justify-center rounded-md bg-sand-100 text-ink-soft" title="Text only">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6.5h16M4 12h16M4 17.5h10"/></svg>
                                </span>
                            @endif
                            <span class="min-w-0 flex-1">
                                <span class="block truncate font-medium text-forest-900">{{ $item->display_title }}</span>
                                <span class="block truncate text-sm text-ink-soft">{{ $item->section_name }}</span>
                            </span>
                            <span class="hidden whitespace-nowrap text-sm text-ink-soft sm:block">{{ $item->updated_at->diffForHumans() }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </section>
    @endif

@endsection
