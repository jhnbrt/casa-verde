@extends('admin.layout')

@section('title', 'Guest reviews')

@section('content')

    <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="page-title">Guest reviews</h1>
            <p class="mt-3 max-w-xl text-ink-soft">
                Ratings and comments guests send from the Reviews page.
                @if ($needsApproval)
                    New reviews wait here until you press <strong>Show</strong>.
                @else
                    New reviews appear on the page straight away; press <strong>Hide</strong> to take one down.
                @endif
            </p>
        </div>
        <div class="flex gap-2 self-start sm:self-auto">
            <form action="{{ route('admin.reviews.sync-google') }}" method="POST">
                @csrf
                <button type="submit" class="btn-secondary whitespace-nowrap">Sync Google reviews</button>
            </form>
            <a href="{{ route('reviews.index') }}#rv-share" target="_blank" rel="noopener" class="btn-secondary whitespace-nowrap">View page</a>
        </div>
    </div>

    <p class="mt-6 text-sm text-ink-soft">
        {{ $total }} {{ \Illuminate\Support\Str::plural('review', $total) }}
        @if ($waiting > 0) · <span class="font-medium text-forest-900">{{ $waiting }} hidden</span> @endif
    </p>

    <div class="mt-4">
        @if ($reviews->isEmpty())
            <div class="rounded-xl border border-dashed border-sand-300 bg-white px-6 py-14 text-center">
                <p class="font-medium text-forest-900">No guest reviews yet</p>
                <p class="mt-1 text-ink-soft">When a guest submits the form on the Reviews page, it shows up here.</p>
            </div>
        @else
            <ul role="list" class="divide-y divide-sand-200 overflow-hidden rounded-xl border border-sand-300 bg-white">
                @foreach ($reviews as $review)
                    <li class="flex flex-wrap items-start gap-x-4 gap-y-3 px-4 py-4 sm:flex-nowrap">
                        <div class="min-w-0 flex-1 basis-64">
                            <div class="flex flex-wrap items-baseline gap-x-3 gap-y-1">
                                <span class="font-medium text-forest-900">{{ $review->name }}</span>
                                @if ($review->isFromGoogle())
                                    <span class="rounded-full bg-sand-200 px-2 py-0.5 text-xs text-ink-soft">Google</span>
                                @endif
                                @if ($review->place)
                                    <span class="text-sm text-ink-soft">{{ $review->place }}</span>
                                @endif
                                <span class="text-sm text-gold-600" role="img" aria-label="{{ $review->rating }} out of 5 stars">{{ str_repeat('★', $review->rating) }}<span class="text-sand-300">{{ str_repeat('★', 5 - $review->rating) }}</span></span>
                            </div>
                            <p class="mt-1 whitespace-pre-line break-words text-[0.9375rem] text-ink">{{ $review->comment }}</p>
                            <p class="mt-1 text-xs text-ink-soft">{{ ($review->reviewed_at ?? $review->created_at)->format('M j, Y · g:i A') }}</p>
                        </div>

                        <div class="flex w-full items-center justify-between gap-4 sm:w-auto sm:justify-end">
                            <span class="inline-flex w-20 items-center gap-2 text-sm {{ $review->is_published ? 'text-forest-800' : 'text-ink-soft' }}">
                                <span class="h-2 w-2 rounded-full {{ $review->is_published ? 'bg-forest-700' : 'bg-sand-300' }}"></span>
                                {{ $review->is_published ? 'Visible' : 'Hidden' }}
                            </span>

                            <div class="flex items-center gap-1">
                                <form action="{{ route('admin.reviews.update', $review) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn-secondary px-3 py-1.5">{{ $review->is_published ? 'Hide' : 'Show' }}</button>
                                </form>
                                <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST"
                                      onsubmit="return confirm('Delete this review? This cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-quiet-danger">Delete</button>
                                </form>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>

            @if ($reviews->hasPages())
                <div class="mt-5 flex items-center justify-between">
                    @if ($reviews->previousPageUrl())
                        <a href="{{ $reviews->previousPageUrl() }}" class="btn-secondary">← Newer</a>
                    @else
                        <span></span>
                    @endif
                    @if ($reviews->nextPageUrl())
                        <a href="{{ $reviews->nextPageUrl() }}" class="btn-secondary">Older →</a>
                    @endif
                </div>
            @endif
        @endif
    </div>

@endsection
