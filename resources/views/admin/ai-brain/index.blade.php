@extends('admin.layout')

@section('title', 'AI Brain')

@section('content')

    @php
        $tabs = [
            'teach' => ['label' => 'Teach the AI', 'count' => null],
            'knowledge' => ['label' => 'Knowledge', 'count' => $counts['total']],
            'questions' => ['label' => 'Customer questions', 'count' => $needs ?: null],
            'test' => ['label' => 'Test chat', 'count' => null],
            'connection' => ['label' => 'Connection', 'count' => null],
        ];

        // Settings the page script needs (kept in a variable because the JSON shortcut cannot take a list with commas).
        $aibData = [
            'topics' => $topics,
            'urls' => [
                'teach' => route('admin.ai-brain.teach'),
                'drafts' => route('admin.ai-brain.drafts'),
                'test' => route('admin.ai-brain.test'),
                'knowledge' => route('admin.ai-brain.index', ['tab' => 'knowledge']),
                'check' => route('admin.ai-brain.connection.check'),
            ],
            'csrf' => csrf_token(),
        ];
    @endphp

    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="page-title">AI Brain</h1>
            <p class="mt-3 max-w-2xl text-ink-soft">
                The assistant on your website answers guests using what you teach it here. Tell it something new, review what it understood,
                and see the questions it could not answer. Changes work right away, with no database.
            </p>
        </div>
    </div>

    @if (session('status'))
        <div role="status" class="mt-6 rounded-lg border border-forest-200 bg-forest-50 px-4 py-3 text-forest-900">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div role="alert" class="mt-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-800">
            @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
        </div>
    @endif

    @if ($connection['attention'])
        <div role="alert" class="mt-6 flex flex-wrap items-center justify-between gap-3 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-800">
            <p><strong>The AI needs attention.</strong> {{ $connection['status']['message'] }}</p>
            <button type="button" data-tab-link="connection" class="btn-primary px-4 py-1.5">Update the key</button>
        </div>
    @endif

    {{-- Status --}}
    <div class="mt-6 grid gap-4 lg:grid-cols-[1.4fr_1fr_1fr_1fr]">
        <div class="rounded-xl border p-4 {{ $aiReady ? 'border-forest-200 bg-forest-50' : 'border-gold-300 bg-gold-300/20' }}">
            <p class="flex items-center gap-2 text-sm font-medium {{ $aiReady ? 'text-forest-800' : 'text-gold-700' }}">
                <span class="h-2.5 w-2.5 rounded-full {{ $aiReady ? 'bg-forest-700' : 'bg-gold-400' }}"></span>
                {{ $aiReady ? 'AI connected' : 'AI not connected yet' }}
            </p>
            @if ($aiReady)
                <p class="mt-1 text-sm text-ink-soft">{{ $provider }} · {{ $model }} · <button type="button" data-tab-link="connection" class="underline hover:text-forest-900">Change key</button></p>
            @else
                <p class="mt-1 text-sm text-ink-soft">Paste an API key in the <button type="button" data-tab-link="connection" class="underline hover:text-forest-900">Connection</button> tab. A free Gemini key works. Until then guests get your quick answers, and you can still write knowledge by hand.</p>
            @endif
        </div>
        <div class="rounded-xl border border-sand-300 bg-white p-4">
            <p class="text-sm text-ink-soft">Knowledge entries</p>
            <p class="mt-1 text-2xl font-semibold text-forest-900">{{ $counts['active'] }}<span class="text-base font-normal text-ink-soft"> in use of {{ $counts['total'] }}</span></p>
        </div>
        <div class="rounded-xl border border-sand-300 bg-white p-4">
            <p class="text-sm text-ink-soft">Guest questions this week</p>
            <p class="mt-1 text-2xl font-semibold text-forest-900">{{ $week }}</p>
        </div>
        <div class="rounded-xl border p-4 {{ $needs ? 'border-gold-300 bg-gold-300/20' : 'border-sand-300 bg-white' }}">
            <p class="text-sm text-ink-soft">Waiting for your answer</p>
            <p class="mt-1 text-2xl font-semibold text-forest-900">{{ $needs }}</p>
        </div>
    </div>

    {{-- Tabs --}}
    <div role="tablist" aria-label="AI Brain sections" class="mt-8 flex flex-wrap gap-1 border-b border-sand-300">
        @foreach ($tabs as $key => $item)
            <button type="button" role="tab" id="tab-{{ $key }}" data-tab="{{ $key }}" aria-controls="panel-{{ $key }}"
                    aria-selected="{{ $tab === $key ? 'true' : 'false' }}" tabindex="{{ $tab === $key ? '0' : '-1' }}"
                    class="-mb-px flex items-center gap-2 rounded-t-lg border-b-2 border-transparent px-4 py-2.5 text-[0.9375rem] font-medium text-ink-soft transition-colors hover:text-forest-900 aria-selected:border-gold-400 aria-selected:text-forest-900 focus-visible:ring-4 focus-visible:ring-gold-400/40">
                {{ $item['label'] }}
                @if ($key === 'connection' && $connection['attention'])
                    <span class="h-2.5 w-2.5 rounded-full bg-red-500"><span class="sr-only">needs attention</span></span>
                @endif
                @if ($item['count'])
                    <span class="rounded-full {{ $key === 'questions' ? 'bg-gold-400 text-forest-900' : 'bg-sand-200 text-ink-soft' }} px-2 py-0.5 text-xs font-semibold">{{ $item['count'] }}</span>
                @endif
            </button>
        @endforeach
    </div>

    {{-- 1. Teach --}}
    <section id="panel-teach" role="tabpanel" aria-labelledby="tab-teach" class="mt-6" @if ($tab !== 'teach') hidden @endif>
        <div class="panel">
            <h2 class="section-title">Tell the AI something new</h2>
            <p class="field-hint">Write it the way you would tell a new staff member, in English, Bisaya or Tagalog. The AI organises it into clear cards. You check them before anything is saved.</p>

            <form id="teach-form" class="mt-5">
                <label for="teach-message" class="field-label">What should the assistant know?</label>
                <textarea id="teach-message" rows="6" maxlength="4000" class="field-input"
                          placeholder="For example: Check-in is 2 PM and check-out is 11 AM. Early check-in is possible if the villa is ready. Airport transfers from Camotes pier are available, please ask us to arrange it."></textarea>
                <div class="mt-3 flex flex-wrap items-center gap-3">
                    <button type="submit" id="teach-submit" class="btn-primary">Organise with AI</button>
                    <span id="teach-status" class="text-sm text-ink-soft" role="status" aria-live="polite"></span>
                </div>
                <p class="field-hint mt-3">Ideas: check-in and check-out times, airport transfer, pet policy, payment methods, cancellation, spa menu, dining hours, things to do on Camotes, how to get here.</p>
            </form>

            <div id="teach-results" class="mt-6 hidden">
                <h3 class="field-label">Review what the AI understood</h3>
                <p id="teach-note" class="field-hint hidden"></p>
                <div id="teach-cards" class="mt-3 space-y-3"></div>
                <div class="mt-4 flex flex-wrap items-center gap-3">
                    <button type="button" id="teach-save-all" class="btn-primary">Save all to the AI's knowledge</button>
                    <button type="button" id="teach-clear" class="btn-secondary">Start over</button>
                    <span id="teach-saved" class="text-sm text-forest-800" role="status" aria-live="polite"></span>
                </div>
            </div>
        </div>
    </section>

    {{-- 2. Knowledge --}}
    <section id="panel-knowledge" role="tabpanel" aria-labelledby="tab-knowledge" class="mt-6" @if ($tab !== 'knowledge') hidden @endif>
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div class="min-w-0 flex-1 basis-64">
                <label for="knowledge-search" class="field-label">Search what the AI knows</label>
                <input id="knowledge-search" type="search" class="field-input" placeholder="Type a word, for example check-in">
            </div>
        </div>

        <details class="panel mt-4" @if ($counts['total'] === 0) open @endif>
            <summary class="cursor-pointer text-[0.9375rem] font-medium text-forest-900">Add knowledge by hand</summary>
            <form action="{{ route('admin.ai-brain.entries.store') }}" method="POST" class="mt-4 space-y-4">
                @csrf
                <div>
                    <label for="new-topic" class="field-label">Topic</label>
                    <select id="new-topic" name="topic" class="field-input">
                        @foreach ($topics as $topic)<option value="{{ $topic }}">{{ $topic }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label for="new-text" class="field-label">What the assistant should know</label>
                    <textarea id="new-text" name="text" rows="3" maxlength="1500" required class="field-input" placeholder="Write it as a clear fact, for example: Check-in is from 2 PM and check-out is until 11 AM."></textarea>
                </div>
                <button type="submit" class="btn-primary">Add</button>
            </form>
        </details>

        <div id="knowledge-list" class="mt-6 space-y-8">
            @forelse ($entries as $topic => $items)
                <section data-topic-group>
                    <h2 class="section-title">{{ $topic }} <span class="text-sm font-normal text-ink-soft">({{ $items->count() }})</span></h2>
                    <ul role="list" class="mt-3 divide-y divide-sand-200 overflow-hidden rounded-xl border border-sand-300 bg-white">
                        @foreach ($items as $entry)
                            <li data-entry class="px-4 py-4 {{ empty($entry['active']) ? 'bg-sand-50' : '' }}">
                                <div class="flex flex-wrap items-start gap-x-4 gap-y-3">
                                    <div class="min-w-0 flex-1 basis-72">
                                        <p data-entry-text class="whitespace-pre-line break-words text-[0.9375rem] {{ empty($entry['active']) ? 'text-ink-soft' : 'text-ink' }}">{{ $entry['text'] }}</p>
                                        <p class="mt-1 text-xs text-ink-soft">
                                            {{ empty($entry['active']) ? 'Not in use · ' : '' }}Updated {{ \Carbon\Carbon::parse($entry['updated_at'] ?? now())->timezone(config('app.timezone'))->format('M j, Y') }}
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <form action="{{ route('admin.ai-brain.entries.toggle', $entry['id']) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn-secondary px-3 py-1.5" aria-label="{{ empty($entry['active']) ? 'Use this knowledge again' : 'Stop using this knowledge' }}">{{ empty($entry['active']) ? 'Turn on' : 'Turn off' }}</button>
                                        </form>
                                        <form action="{{ route('admin.ai-brain.entries.destroy', $entry['id']) }}" method="POST" onsubmit="return confirm('Delete this knowledge? The assistant will no longer know it.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-quiet-danger">Delete</button>
                                        </form>
                                    </div>
                                </div>

                                <details class="mt-3">
                                    <summary class="cursor-pointer text-sm text-forest-800 hover:underline">Edit</summary>
                                    <form action="{{ route('admin.ai-brain.entries.update', $entry['id']) }}" method="POST" class="mt-3 space-y-3">
                                        @csrf
                                        @method('PUT')
                                        <select name="topic" class="field-input" aria-label="Topic">
                                            @foreach ($topics as $option)<option value="{{ $option }}" @selected($option === ($entry['topic'] ?? 'Other'))>{{ $option }}</option>@endforeach
                                        </select>
                                        <textarea name="text" rows="3" maxlength="1500" required class="field-input" aria-label="Knowledge text">{{ $entry['text'] }}</textarea>
                                        <button type="submit" class="btn-primary">Save changes</button>
                                    </form>
                                </details>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @empty
                <div class="rounded-xl border border-dashed border-sand-300 bg-white px-6 py-14 text-center">
                    <p class="font-medium text-forest-900">The AI has no extra knowledge yet</p>
                    <p class="mt-1 text-ink-soft">Use <strong>Teach the AI</strong> to tell it about Casa Verde, or add a fact by hand above.</p>
                </div>
            @endforelse
        </div>
        <p id="knowledge-none" class="mt-6 hidden rounded-xl border border-dashed border-sand-300 bg-white px-6 py-8 text-center text-ink-soft">Nothing matches your search.</p>
    </section>

    {{-- 3. Customer questions --}}
    <section id="panel-questions" role="tabpanel" aria-labelledby="tab-questions" class="mt-6" @if ($tab !== 'questions') hidden @endif>
        <div class="flex flex-wrap items-center gap-2" role="group" aria-label="Filter questions">
            @foreach (['needs' => 'Needs your answer', 'all' => 'All questions', 'done' => 'Done'] as $key => $label)
                <a href="{{ route('admin.ai-brain.index', ['tab' => 'questions', 'filter' => $key]) }}"
                   @if ($filter === $key) aria-current="true" @endif
                   class="rounded-full border px-4 py-1.5 text-sm transition-colors {{ $filter === $key ? 'border-forest-800 bg-forest-800 text-white' : 'border-sand-300 bg-white text-ink-soft hover:border-forest-700 hover:text-forest-900' }}">{{ $label }}</a>
            @endforeach
        </div>
        <p class="mt-3 max-w-2xl text-sm text-ink-soft">
            Guest questions are saved as private files on your server for {{ $keepDays }} days, without names, emails or IP addresses.
            When the AI could not answer, press <strong>Teach the answer</strong>.
        </p>

        <div class="mt-4">
            @forelse ($questions as $row)
                @php
                    $unsure = ! empty($row['unsure']);
                    $badge = match (true) {
                        $unsure && ! empty($row['reviewed']) => ['Done', 'bg-sand-200 text-ink-soft'],
                        $unsure => ['Could not answer', 'bg-gold-400 text-forest-900'],
                        ($row['source'] ?? '') === 'quick' => ['Quick answer', 'bg-forest-100 text-forest-800'],
                        default => ['Answered by AI', 'bg-forest-100 text-forest-800'],
                    };
                @endphp
                @if ($loop->first)<ul role="list" class="divide-y divide-sand-200 overflow-hidden rounded-xl border border-sand-300 bg-white">@endif
                <li class="px-4 py-4">
                    <div class="flex flex-wrap items-start gap-x-4 gap-y-3">
                        <div class="min-w-0 flex-1 basis-72">
                            <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
                                <span class="rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $badge[1] }}">{{ $badge[0] }}</span>
                                <span class="text-xs text-ink-soft">{{ \Carbon\Carbon::parse($row['at'])->timezone(config('app.timezone'))->format('M j · g:i A') }} · Guest {{ $row['visitor'] ?? '' }}</span>
                            </div>
                            <p class="mt-2 break-words font-medium text-forest-900">{{ $row['question'] }}</p>
                            <p class="mt-1 line-clamp-3 whitespace-pre-line break-words text-sm text-ink-soft">{{ $row['answer'] ?? '' }}</p>
                        </div>
                        @if ($unsure && empty($row['reviewed']))
                            <div class="flex items-center gap-1">
                                <button type="button" class="btn-primary px-4 py-1.5" data-teach-question="{{ $row['question'] }}">Teach the answer</button>
                                <form action="{{ route('admin.ai-brain.questions.done', $row['id']) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-secondary px-3 py-1.5">Mark done</button>
                                </form>
                            </div>
                        @endif
                    </div>
                </li>
                @if ($loop->last)</ul>@endif
            @empty
                <div class="rounded-xl border border-dashed border-sand-300 bg-white px-6 py-14 text-center">
                    <p class="font-medium text-forest-900">{{ $filter === 'needs' ? 'No questions are waiting for you' : 'No questions yet' }}</p>
                    <p class="mt-1 text-ink-soft">When guests use the chat on your website, their questions appear here.</p>
                </div>
            @endforelse
        </div>
    </section>

    {{-- 4. Test --}}
    <section id="panel-test" role="tabpanel" aria-labelledby="tab-test" class="mt-6" @if ($tab !== 'test') hidden @endif>
        <div class="panel">
            <h2 class="section-title">Try the assistant</h2>
            <p class="field-hint">Ask it what a guest might ask. It answers exactly as it would on your website, using everything you taught it. Tests are not saved or counted.</p>

            <div id="test-log" class="mt-5 max-h-96 min-h-40 space-y-3 overflow-y-auto rounded-xl border border-sand-300 bg-sand-50 p-4" role="log" aria-live="polite">
                <p class="text-sm text-ink-soft" id="test-empty">Type a question below to start.</p>
            </div>

            <form id="test-form" class="mt-3 flex gap-2">
                <label for="test-input" class="sr-only">Test question</label>
                <input id="test-input" type="text" maxlength="500" autocomplete="off" class="field-input" placeholder="For example: What time is check-in?">
                <button type="submit" id="test-send" class="btn-primary whitespace-nowrap">Ask</button>
                <button type="button" id="test-reset" class="btn-secondary whitespace-nowrap">Clear</button>
            </form>
        </div>
    </section>

    {{-- 5. Connection --}}
    <section id="panel-connection" role="tabpanel" aria-labelledby="tab-connection" class="mt-6" @if ($tab !== 'connection') hidden @endif>
        <div class="panel">
            <h2 class="section-title">AI connection</h2>
            <p class="field-hint">If the AI stops answering because the API key expired or was deleted, paste a new key here. It works straight away, with no need to touch the server.</p>

            <dl class="mt-5 grid gap-4 sm:grid-cols-3">
                <div class="rounded-xl border border-sand-300 bg-sand-50 p-4">
                    <dt class="text-sm text-ink-soft">Provider and model</dt>
                    <dd class="mt-1 font-medium text-forest-900">{{ $provider }} · {{ $model ?: 'default' }}</dd>
                </div>
                <div class="rounded-xl border border-sand-300 bg-sand-50 p-4">
                    <dt class="text-sm text-ink-soft">Key in use</dt>
                    <dd class="mt-1 font-medium text-forest-900">
                        @if ($connection['masked'] !== '')
                            <span class="font-mono">{{ $connection['masked'] }}</span>
                            <span class="block text-xs font-normal text-ink-soft">
                                {{ $connection['source'] === 'admin' ? 'Saved here' : 'From the .env file' }}@if ($connection['saved_at']) · {{ \Carbon\Carbon::parse($connection['saved_at'])->timezone(config('app.timezone'))->format('M j, Y') }}@endif
                            </span>
                        @else
                            None yet
                        @endif
                    </dd>
                </div>
                <div class="rounded-xl border p-4 {{ $connection['attention'] ? 'border-red-200 bg-red-50' : 'border-sand-300 bg-sand-50' }}">
                    <dt class="text-sm text-ink-soft">Last AI request</dt>
                    <dd class="mt-1 font-medium {{ $connection['attention'] ? 'text-red-800' : 'text-forest-900' }}">
                        @if ($connection['status']['ok'] === true)
                            Worked
                        @elseif ($connection['status']['ok'] === false)
                            Failed
                        @else
                            Not checked yet
                        @endif
                        @if ($connection['status']['at'])
                            <span class="block text-xs font-normal text-ink-soft">{{ \Carbon\Carbon::parse($connection['status']['at'])->timezone(config('app.timezone'))->format('M j · g:i A') }}</span>
                        @endif
                    </dd>
                </div>
            </dl>

            <form action="{{ route('admin.ai-brain.connection.save') }}" method="POST" class="mt-6 space-y-5" autocomplete="off">
                @csrf

                <div>
                    <label for="conn-provider" class="field-label">AI provider</label>
                    <select id="conn-provider" name="provider" class="field-input">
                        @foreach ($connection['providers'] as $key => $item)
                            <option value="{{ $key }}" data-url="{{ $item['url'] }}" data-model="{{ $item['default_model'] }}" data-saved="{{ $item['has_saved_key'] ? '1' : '0' }}" @selected(old('provider', $connection['name']) === $key)>{{ $item['label'] }}</option>
                        @endforeach
                    </select>
                    <p class="field-hint">Get a key at <a id="conn-link" href="{{ $connection['providers'][$connection['name']]['url'] }}" target="_blank" rel="noopener noreferrer" class="underline">{{ parse_url($connection['providers'][$connection['name']]['url'], PHP_URL_HOST) }}</a>.</p>
                </div>

                <div>
                    <label for="conn-key" class="field-label">New API key</label>
                    <div class="flex gap-2">
                        <input id="conn-key" name="api_key" type="password" class="field-input font-mono" autocomplete="new-password" spellcheck="false" autocapitalize="off"
                               placeholder="{{ $connection['masked'] !== '' ? 'Leave empty to keep the current key' : 'Paste the key here' }}">
                        <button type="button" id="conn-show" class="btn-secondary whitespace-nowrap" aria-pressed="false">Show</button>
                    </div>
                    <p class="field-hint">The key is stored encrypted on your server and is never shown again. Only the last 4 characters appear above. Leave this empty to keep the current key.</p>
                    @error('api_key')<p class="mt-2 text-sm text-red-700" role="alert">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="conn-model" class="field-label">Model <span class="font-normal text-ink-soft">(optional)</span></label>
                    <input id="conn-model" name="model" type="text" class="field-input font-mono" value="{{ old('model', $connection['saved_model']) }}" placeholder="{{ $connection['providers'][$connection['name']]['default_model'] }}" spellcheck="false">
                    <p class="field-hint">Leave empty to use the default shown. Change it only if the provider tells you a model was retired.</p>
                    @error('model')<p class="mt-2 text-sm text-red-700" role="alert">{{ $message }}</p>@enderror
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <button type="submit" class="btn-primary">Save and check</button>
                    <button type="button" id="conn-check" class="btn-secondary">Check connection</button>
                    <span id="conn-result" class="text-sm" role="status" aria-live="polite"></span>
                </div>
            </form>

            @if ($connection['source'] === 'admin')
                <form action="{{ route('admin.ai-brain.connection.remove') }}" method="POST" class="mt-6 border-t border-sand-300 pt-5" onsubmit="return confirm('Remove the key saved here? The key in the .env file will be used instead, if there is one.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-quiet-danger">Remove the saved key</button>
                    <p class="field-hint">Switches back to the key in the .env file.</p>
                </form>
            @endif
        </div>
    </section>

    <script type="application/json" id="aib-data">@json($aibData)</script>

    @verbatim
    <script>
    (() => {
        const data = JSON.parse(document.getElementById('aib-data').textContent);
        const $ = (selector, root = document) => root.querySelector(selector);

        /* ---------- Tabs ---------- */
        const tabs = [...document.querySelectorAll('[data-tab]')];

        const showTab = (name, focus = false) => {
            tabs.forEach((tab) => {
                const on = tab.dataset.tab === name;
                tab.setAttribute('aria-selected', String(on));
                tab.tabIndex = on ? 0 : -1;
                if (on && focus) tab.focus();
            });
            document.querySelectorAll('[role="tabpanel"]').forEach((panel) => { panel.hidden = panel.id !== 'panel-' + name; });
            const url = new URL(window.location.href);
            url.searchParams.set('tab', name);
            history.replaceState(null, '', url);
        };

        tabs.forEach((tab, index) => {
            tab.addEventListener('click', () => showTab(tab.dataset.tab));
            tab.addEventListener('keydown', (event) => {
                const step = event.key === 'ArrowRight' ? 1 : event.key === 'ArrowLeft' ? -1 : 0;
                if (!step) return;
                event.preventDefault();
                showTab(tabs[(index + step + tabs.length) % tabs.length].dataset.tab, true);
            });
        });

        document.querySelectorAll('[data-tab-link]').forEach((link) => link.addEventListener('click', () => {
            showTab(link.dataset.tabLink);
            document.getElementById('conn-key')?.focus();
        }));

        /* ---------- Talking to the server ---------- */
        const post = async (url, body) => {
            const response = await fetch(url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', Accept: 'application/json', 'X-CSRF-TOKEN': data.csrf, 'X-Requested-With': 'XMLHttpRequest' },
                body: JSON.stringify(body),
            });
            const json = await response.json().catch(() => ({}));
            if (!response.ok) {
                const first = json.errors ? Object.values(json.errors)[0][0] : null;
                throw new Error(first || json.message || 'Something went wrong. Please try again.');
            }
            return json;
        };

        /* ---------- Teach ---------- */
        const form = $('#teach-form');
        const message = $('#teach-message');
        const submit = $('#teach-submit');
        const status = $('#teach-status');
        const results = $('#teach-results');
        const cards = $('#teach-cards');
        const note = $('#teach-note');
        const saved = $('#teach-saved');

        const makeCard = (entry) => {
            const card = document.createElement('div');
            card.className = 'rounded-xl border border-sand-300 bg-white p-4';
            card.dataset.card = '1';

            const select = document.createElement('select');
            select.className = 'field-input';
            select.setAttribute('aria-label', 'Topic');
            data.topics.forEach((topic) => {
                const option = document.createElement('option');
                option.value = topic;
                option.textContent = topic;
                option.selected = topic === entry.topic;
                select.append(option);
            });

            const text = document.createElement('textarea');
            text.className = 'field-input mt-3';
            text.rows = 3;
            text.maxLength = 1500;
            text.value = entry.text;
            text.setAttribute('aria-label', 'Knowledge text');

            const row = document.createElement('div');
            row.className = 'mt-3 flex flex-wrap items-center gap-2';

            const save = document.createElement('button');
            save.type = 'button';
            save.className = 'btn-primary px-4 py-1.5';
            save.textContent = 'Save this card';
            save.addEventListener('click', async () => {
                save.disabled = true;
                try {
                    await post(data.urls.drafts, { entries: [{ topic: select.value, text: text.value }] });
                    card.replaceChildren(Object.assign(document.createElement('p'), { className: 'text-sm text-forest-800', textContent: 'Saved. The assistant uses it from the next question.' }));
                    card.dataset.card = '';
                    afterChange();
                } catch (error) {
                    save.disabled = false;
                    alert(error.message);
                }
            });

            const discard = document.createElement('button');
            discard.type = 'button';
            discard.className = 'btn-quiet-danger';
            discard.textContent = 'Discard';
            discard.addEventListener('click', () => { card.remove(); afterChange(); });

            row.append(save, discard);
            card.append(select, text, row);
            card.collect = () => ({ topic: select.value, text: text.value });
            return card;
        };

        const afterChange = () => {
            const left = cards.querySelectorAll('[data-card="1"]').length;
            $('#teach-save-all').hidden = left === 0;
            if (left === 0 && !cards.children.length) results.classList.add('hidden');
        };

        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            const text = message.value.trim();
            if (text.length < 5) { status.textContent = 'Please write a little more first.'; return; }

            submit.disabled = true;
            status.textContent = 'The AI is organising your message...';
            saved.textContent = '';

            try {
                const json = await post(data.urls.teach, { message: text });
                cards.replaceChildren(...json.entries.map(makeCard));
                note.textContent = json.note || '';
                note.classList.toggle('hidden', !json.note);
                results.classList.remove('hidden');
                $('#teach-save-all').hidden = false;
                status.textContent = 'Done. Please check the cards below, then save.';
                results.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            } catch (error) {
                status.textContent = error.message;
            } finally {
                submit.disabled = false;
            }
        });

        $('#teach-save-all').addEventListener('click', async (event) => {
            const button = event.currentTarget;
            const live = [...cards.querySelectorAll('[data-card="1"]')];
            if (!live.length) return;
            button.disabled = true;

            try {
                const json = await post(data.urls.drafts, { entries: live.map((card) => card.collect()) });
                saved.innerHTML = '';
                saved.append(json.saved + ' saved. ');
                const link = document.createElement('a');
                link.href = data.urls.knowledge;
                link.className = 'underline';
                link.textContent = 'See it in Knowledge';
                saved.append(link);
                cards.replaceChildren();
                message.value = '';
                button.hidden = true;
                note.classList.add('hidden');
            } catch (error) {
                saved.textContent = error.message;
            } finally {
                button.disabled = false;
            }
        });

        $('#teach-clear').addEventListener('click', () => {
            cards.replaceChildren();
            results.classList.add('hidden');
            status.textContent = '';
            saved.textContent = '';
            message.focus();
        });

        // "Teach the answer" on a guest question
        document.querySelectorAll('[data-teach-question]').forEach((button) => button.addEventListener('click', () => {
            showTab('teach');
            message.value = 'A guest asked: "' + button.dataset.teachQuestion + '"\nThe answer: ';
            message.focus();
            message.setSelectionRange(message.value.length, message.value.length);
            message.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }));

        /* ---------- Knowledge search ---------- */
        const search = $('#knowledge-search');

        search?.addEventListener('input', () => {
            const needle = search.value.trim().toLowerCase();
            let visible = 0;

            document.querySelectorAll('[data-entry]').forEach((entry) => {
                const match = !needle || $('[data-entry-text]', entry).textContent.toLowerCase().includes(needle);
                entry.hidden = !match;
                if (match) visible++;
            });

            document.querySelectorAll('[data-topic-group]').forEach((group) => {
                group.hidden = !group.querySelector('[data-entry]:not([hidden])');
            });

            $('#knowledge-none').classList.toggle('hidden', visible > 0 || !needle);
        });

        /* ---------- Connection ---------- */
        const provider = $('#conn-provider');
        const keyInput = $('#conn-key');
        const result = $('#conn-result');
        const checkButton = $('#conn-check');

        provider?.addEventListener('change', () => {
            const option = provider.selectedOptions[0];
            const link = $('#conn-link');
            link.href = option.dataset.url;
            link.textContent = new URL(option.dataset.url).hostname;
            $('#conn-model').placeholder = option.dataset.model;
        });

        $('#conn-show')?.addEventListener('click', (event) => {
            const show = keyInput.type === 'password';
            keyInput.type = show ? 'text' : 'password';
            event.currentTarget.textContent = show ? 'Hide' : 'Show';
            event.currentTarget.setAttribute('aria-pressed', String(show));
        });

        const runCheck = async () => {
            checkButton.disabled = true;
            result.className = 'text-sm text-ink-soft';
            result.textContent = 'Checking...';

            try {
                const json = await post(data.urls.check, {});
                result.className = 'text-sm font-medium text-forest-800';
                result.textContent = json.message;
            } catch (error) {
                result.className = 'text-sm font-medium text-red-700';
                result.textContent = error.message;
            } finally {
                checkButton.disabled = false;
            }
        };

        checkButton?.addEventListener('click', runCheck);

        // Right after saving a key, check it automatically.
        if (new URLSearchParams(window.location.search).has('check')) {
            showTab('connection');
            runCheck();
        }

        /* ---------- Test chat ---------- */
        const testLog = $('#test-log');
        const testForm = $('#test-form');
        const testInput = $('#test-input');
        const testSend = $('#test-send');
        let testHistory = [];

        const bubble = (role, text, extra) => {
            $('#test-empty')?.remove();
            const item = document.createElement('div');
            item.className = role === 'user'
                ? 'ml-auto max-w-[85%] rounded-2xl rounded-br-sm bg-forest-800 px-4 py-2.5 text-[0.9375rem] text-white'
                : 'mr-auto max-w-[85%] rounded-2xl rounded-bl-sm border border-sand-300 bg-white px-4 py-2.5 text-[0.9375rem] text-ink';
            item.style.whiteSpace = 'pre-wrap';
            item.textContent = text;
            if (extra) {
                const flag = document.createElement('p');
                flag.className = 'mt-2 text-xs font-medium text-gold-700';
                flag.textContent = extra;
                item.append(flag);
            }
            testLog.append(item);
            testLog.scrollTop = testLog.scrollHeight;
            return item;
        };

        testForm.addEventListener('submit', async (event) => {
            event.preventDefault();
            const text = testInput.value.trim();
            if (!text) return;

            testInput.value = '';
            testSend.disabled = true;
            testHistory.push({ role: 'user', content: text });
            bubble('user', text);
            const waiting = bubble('bot', '...');

            try {
                const json = await post(data.urls.test, { messages: testHistory.slice(-14) });
                waiting.remove();
                testHistory.push({ role: 'assistant', content: json.reply });
                bubble('bot', json.reply, json.unsure ? 'The AI was not sure about this. Teach it the answer in the Teach the AI tab.' : '');
            } catch (error) {
                waiting.remove();
                testHistory.pop();
                bubble('bot', error.message);
            } finally {
                testSend.disabled = false;
                testInput.focus();
            }
        });

        $('#test-reset').addEventListener('click', () => {
            testHistory = [];
            testLog.replaceChildren(Object.assign(document.createElement('p'), { id: 'test-empty', className: 'text-sm text-ink-soft', textContent: 'Type a question below to start.' }));
            testInput.focus();
        });
    })();
    </script>
    @endverbatim

@endsection
