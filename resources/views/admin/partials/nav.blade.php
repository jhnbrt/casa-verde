<nav aria-label="Admin" class="space-y-1">
    <a href="{{ route('admin.dashboard') }}"
       @if (request()->routeIs('admin.dashboard')) aria-current="page" @endif
       class="flex items-center gap-3 rounded-lg border-l-2 px-3 py-2 text-[0.9375rem] transition-colors {{ request()->routeIs('admin.dashboard') ? 'border-gold-400 bg-white/10 text-white' : 'border-transparent text-sand-200 hover:bg-white/5 hover:text-white' }}">
        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.5h6v6h-6zM13.5 4.5h6v6h-6zM3.75 13.5h6v6h-6zM13.5 13.5h6v6h-6z"/></svg>
        Dashboard
    </a>

    @php($onContent = request()->routeIs('admin.content.*'))

    <details class="group/content" @if ($onContent) open @endif>
        <summary
            class="flex cursor-pointer list-none items-center gap-3 rounded-lg border-l-2 px-3 py-2 text-[0.9375rem] transition-colors [&::-webkit-details-marker]:hidden {{ $onContent ? 'border-gold-400 bg-white/10 text-white' : 'border-transparent text-sand-200 hover:bg-white/5 hover:text-white' }}">
            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
            <span class="flex-1">Site content</span>
            <svg class="h-4 w-4 shrink-0 transition-transform duration-200 group-open/content:rotate-180" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
        </summary>

        <ul role="list" class="ml-5 mt-1 space-y-0.5 border-l border-white/15 pl-3">
            <li>
                <a href="{{ route('admin.content.index') }}"
                   @if ($onContent && ! request('group')) aria-current="page" @endif
                   class="block rounded-md px-3 py-1.5 text-sm transition-colors {{ $onContent && ! request('group') ? 'bg-white/10 text-gold-300' : 'text-sand-200/80 hover:bg-white/5 hover:text-white' }}">
                    All content
                </a>
            </li>
            @foreach (config('admin.content_groups') as $key => $group)
                <li>
                    <a href="{{ route('admin.content.index', ['group' => $key]) }}"
                       @if (request('group') === $key) aria-current="page" @endif
                       class="block rounded-md px-3 py-1.5 text-sm transition-colors {{ request('group') === $key ? 'bg-white/10 text-gold-300' : 'text-sand-200/80 hover:bg-white/5 hover:text-white' }}">
                        {{ $group['label'] }}
                    </a>
                </li>
            @endforeach
        </ul>
    </details>

    @php($onMedia = request()->routeIs('admin.media.*'))

    <a href="{{ route('admin.media.index') }}"
       @if ($onMedia) aria-current="page" @endif
       class="flex items-center gap-3 rounded-lg border-l-2 px-3 py-2 text-[0.9375rem] transition-colors {{ $onMedia ? 'border-gold-400 bg-white/10 text-white' : 'border-transparent text-sand-200 hover:bg-white/5 hover:text-white' }}">
        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.16-5.16a2.25 2.25 0 013.18 0l5.16 5.16m-1.5-1.5l1.41-1.41a2.25 2.25 0 013.18 0l2.41 2.41m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.01v.01h-.01V8.25zm.38 0a.38.38 0 11-.76 0 .38.38 0 01.76 0z"/></svg>
        <span class="flex-1">Media library</span>
    </a>

    @php($onGallery = request()->routeIs('admin.gallery.*'))

    <a href="{{ route('admin.gallery.index') }}"
       @if ($onGallery) aria-current="page" @endif
       class="flex items-center gap-3 rounded-lg border-l-2 px-3 py-2 text-[0.9375rem] transition-colors {{ $onGallery ? 'border-gold-400 bg-white/10 text-white' : 'border-transparent text-sand-200 hover:bg-white/5 hover:text-white' }}">
        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 6.878V6a2.25 2.25 0 012.25-2.25h7.5A2.25 2.25 0 0118 6v.878m-12 0c.235-.083.487-.128.75-.128h10.5c.263 0 .515.045.75.128m-12 0A2.25 2.25 0 004.5 9v.878m13.5-3A2.25 2.25 0 0119.5 9v.878m0 0a2.246 2.246 0 00-.75-.128H5.25c-.263 0-.515.045-.75.128m15 0A2.25 2.25 0 0121 12v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18v-6c0-.98.626-1.813 1.5-2.122"/></svg>
        <span class="flex-1">Gallery photos</span>
    </a>

    @php($onBookings = request()->routeIs('admin.bookings.*'))
    @php($pendingBookings = \App\Models\Booking::pendingCount())

    <a href="{{ route('admin.bookings.index') }}"
       @if ($onBookings) aria-current="page" @endif
       class="flex items-center gap-3 rounded-lg border-l-2 px-3 py-2 text-[0.9375rem] transition-colors {{ $onBookings ? 'border-gold-400 bg-white/10 text-white' : 'border-transparent text-sand-200 hover:bg-white/5 hover:text-white' }}">
        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
        <span class="flex-1">Bookings</span>
        @if ($pendingBookings > 0)
            <span class="rounded-full bg-gold-400 px-2 py-0.5 text-xs font-semibold text-forest-900" title="Requests waiting for a reply">{{ $pendingBookings }}</span>
        @endif
    </a>

    @php($onReviews = request()->routeIs('admin.reviews.*'))
    @php($waitingReviews = \App\Models\Review::waitingForApproval())

    <a href="{{ route('admin.reviews.index') }}"
       @if ($onReviews) aria-current="page" @endif
       class="flex items-center gap-3 rounded-lg border-l-2 px-3 py-2 text-[0.9375rem] transition-colors {{ $onReviews ? 'border-gold-400 bg-white/10 text-white' : 'border-transparent text-sand-200 hover:bg-white/5 hover:text-white' }}">
        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.5a.56.56 0 011.04 0l2.13 5.11a.56.56 0 00.47.35l5.52.44c.5.04.7.67.32.99l-4.2 3.6a.56.56 0 00-.18.56l1.28 5.39a.56.56 0 01-.84.61l-4.73-2.89a.56.56 0 00-.58 0l-4.73 2.89a.56.56 0 01-.84-.61l1.28-5.39a.56.56 0 00-.18-.56l-4.2-3.6a.56.56 0 01.32-.99l5.52-.44a.56.56 0 00.47-.35L11.48 3.5z"/></svg>
        <span class="flex-1">Guest reviews</span>
        @if ($waitingReviews > 0)
            <span class="rounded-full bg-gold-400 px-2 py-0.5 text-xs font-semibold text-forest-900">{{ $waitingReviews }}</span>
        @endif
    </a>

    @php($onBrain = request()->routeIs('admin.ai-brain.*'))
    @php($brainAttention = \App\Support\ChatLog::needsKnowledgeCount())
    @php($brainKeyProblem = \App\Support\AiConnection::needsAttention())

    <a href="{{ route('admin.ai-brain.index') }}"
       @if ($onBrain) aria-current="page" @endif
       class="flex items-center gap-3 rounded-lg border-l-2 px-3 py-2 text-[0.9375rem] transition-colors {{ $onBrain ? 'border-gold-400 bg-white/10 text-white' : 'border-transparent text-sand-200 hover:bg-white/5 hover:text-white' }}">
        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z"/></svg>
        <span class="flex-1">AI Brain</span>
        @if ($brainKeyProblem)
            <span class="h-2.5 w-2.5 rounded-full bg-red-500" title="The AI key needs to be renewed"><span class="sr-only">The AI key needs to be renewed</span></span>
        @endif
        @if ($brainAttention > 0)
            <span class="rounded-full bg-gold-400 px-2 py-0.5 text-xs font-semibold text-forest-900" title="Guest questions the AI could not answer">{{ $brainAttention }}</span>
        @endif
    </a>
</nav>
