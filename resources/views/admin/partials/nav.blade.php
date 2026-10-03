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
</nav>
