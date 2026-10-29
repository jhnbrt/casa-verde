@extends('admin.layout')

@section('title', 'Bookings')

@section('content')

    <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="page-title">Bookings</h1>
            <p class="mt-3 max-w-xl text-ink-soft">
                Requests guests send from the Book page. Check availability, then press <strong>Confirm</strong> or <strong>Cancel</strong>; the guest is emailed automatically.
            </p>
        </div>
        <div class="flex gap-2 self-start sm:self-auto">
            <a href="{{ route('admin.bookings.export', array_filter(['status' => $status, 'q' => $search])) }}" class="btn-secondary whitespace-nowrap">Download CSV</a>
            <a href="{{ route('admin.content.index', ['group' => 'booking']) }}" class="btn-secondary whitespace-nowrap">Page &amp; rates</a>
            <a href="{{ route('booking.index') }}" target="_blank" rel="noopener" class="btn-secondary whitespace-nowrap">View page</a>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.bookings.index') }}" class="mt-6 flex flex-wrap gap-2">
        @if ($status) <input type="hidden" name="status" value="{{ $status }}"> @endif
        <input type="search" name="q" value="{{ $search }}" placeholder="Search name, email, reference or villa"
               class="min-w-0 flex-1 basis-64 rounded-lg border border-sand-300 bg-white px-3 py-2 text-[0.9375rem]">
        <button type="submit" class="btn-secondary">Search</button>
        @if ($search) <a href="{{ route('admin.bookings.index', array_filter(['status' => $status])) }}" class="btn-secondary">Clear</a> @endif
    </form>

    <nav class="mt-4 flex flex-wrap gap-2 text-sm" aria-label="Filter by status">
        @php($tabs = ['' => 'All', 'pending' => 'Pending', 'confirmed' => 'Confirmed', 'cancelled' => 'Cancelled'])
        @foreach ($tabs as $key => $label)
            @php($active = ($status ?? '') === $key)
            <a href="{{ route('admin.bookings.index', array_filter(['status' => $key, 'q' => $search])) }}"
               @if ($active) aria-current="page" @endif
               class="rounded-full border px-3 py-1.5 {{ $active ? 'border-forest-900 bg-forest-900 text-white' : 'border-sand-300 bg-white text-forest-900 hover:border-forest-700' }}">
                {{ $label }}@if ($key) <span class="opacity-70">· {{ $counts[$key] }}</span>@endif
            </a>
        @endforeach
    </nav>

    <div class="mt-4">
        @if ($bookings->isEmpty())
            <div class="rounded-xl border border-dashed border-sand-300 bg-white px-6 py-14 text-center">
                <p class="font-medium text-forest-900">No booking requests{{ $status ? " marked {$status}" : '' }}{{ $search ? " matching “{$search}”" : '' }}</p>
                <p class="mt-1 text-ink-soft">New requests from the website show up here and are emailed to the resort inbox.</p>
            </div>
        @else
            <ul role="list" class="divide-y divide-sand-200 overflow-hidden rounded-xl border border-sand-300 bg-white">
                @foreach ($bookings as $booking)
                    <li class="px-4 py-4">
                        <div class="flex flex-wrap items-start justify-between gap-x-6 gap-y-3">
                            <div class="min-w-0 flex-1 basis-72">
                                <div class="flex flex-wrap items-baseline gap-x-3 gap-y-1">
                                    <span class="font-medium text-forest-900">{{ $booking->name }}</span>
                                    <span class="font-mono text-xs tracking-wider text-ink-soft">{{ $booking->reference }}</span>
                                    <span class="rounded-full px-2 py-0.5 text-xs font-medium
                                        {{ $booking->status === 'pending' ? 'bg-gold-300 text-forest-900' : ($booking->status === 'confirmed' ? 'bg-forest-100 text-forest-800' : 'bg-sand-200 text-ink-soft') }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </div>
                                <p class="mt-1 text-[0.9375rem] text-ink">
                                    {{ $booking->villa_title }} ·
                                    {{ $booking->check_in->format('M j') }} – {{ $booking->check_out->format('M j, Y') }}
                                    ({{ $booking->nights }} {{ \Illuminate\Support\Str::plural('night', $booking->nights) }}) ·
                                    {{ $booking->adults }}A{{ $booking->children ? ' + '.$booking->children.'C' : '' }} ·
                                    <strong>₱{{ number_format($booking->total, 2) }}</strong>
                                </p>
                                <p class="mt-1 text-sm text-ink-soft">
                                    <a class="underline" href="mailto:{{ $booking->email }}">{{ $booking->email }}</a> · {{ $booking->phone }} · {{ $booking->country }}
                                </p>
                                @if ($booking->special_requests)
                                    <p class="mt-2 whitespace-pre-line break-words rounded-lg bg-sand-100 px-3 py-2 text-sm text-ink">{{ $booking->special_requests }}</p>
                                @endif
                                <p class="mt-1 text-xs text-ink-soft">Received {{ $booking->created_at->format('M j, Y · g:i A') }}</p>
                            </div>

                            <div class="flex items-center gap-2">
                                @foreach (['confirmed' => 'Confirm', 'cancelled' => 'Cancel', 'pending' => 'Reopen'] as $to => $label)
                                    @continue($booking->status === $to)
                                    <form action="{{ route('admin.bookings.update', $booking) }}" method="POST">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="status" value="{{ $to }}">
                                        <button type="submit" class="btn-secondary whitespace-nowrap">{{ $label }}</button>
                                    </form>
                                @endforeach
                                <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST" onsubmit="return confirm('Delete this booking request?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-sm text-danger-600 underline hover:text-danger-700">Delete</button>
                                </form>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>

            <div class="mt-4">{{ $bookings->links() }}</div>
        @endif
    </div>

@endsection
