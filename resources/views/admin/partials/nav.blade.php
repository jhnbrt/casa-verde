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
