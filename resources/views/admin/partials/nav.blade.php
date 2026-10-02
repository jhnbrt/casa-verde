<nav aria-label="Admin" class="space-y-1">
    <a href="{{ route('admin.dashboard') }}"
       @if (request()->routeIs('admin.dashboard')) aria-current="page" @endif
       class="flex items-center gap-3 rounded-lg border-l-2 px-3 py-2 text-[0.9375rem] transition-colors {{ request()->routeIs('admin.dashboard') ? 'border-gold-400 bg-white/10 text-white' : 'border-transparent text-sand-200 hover:bg-white/5 hover:text-white' }}">
        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.5h6v6h-6zM13.5 4.5h6v6h-6zM3.75 13.5h6v6h-6zM13.5 13.5h6v6h-6z"/></svg>
        Dashboard
    </a>

    <a href="{{ route('admin.content.index') }}"
       @if (request()->routeIs('admin.content.*') && ! request('group')) aria-current="page" @endif
       class="flex items-center gap-3 rounded-lg border-l-2 px-3 py-2 text-[0.9375rem] transition-colors {{ request()->routeIs('admin.content.*') && ! request('group') ? 'border-gold-400 bg-white/10 text-white' : 'border-transparent text-sand-200 hover:bg-white/5 hover:text-white' }}">
        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
        Site content
    </a>

    <ul role="list" class="ml-5 space-y-0.5 border-l border-white/15 pl-3">
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
</nav>
