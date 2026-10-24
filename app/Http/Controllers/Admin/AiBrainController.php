<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeContent;
use App\Support\AiBrain;
use App\Support\AiClient;
use App\Support\AiConnection;
use App\Support\AiProvider;
use App\Support\ChatLog;
use App\Support\ChatPrompt;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AiBrainController extends Controller
{
    private const TABS = ['teach', 'knowledge', 'questions', 'test', 'connection'];

    /**
     * The AI Brain page: teach the AI, manage what it knows, see what guests ask, test it.
     */
    public function index(Request $request): View
    {
        $tab = in_array($request->query('tab'), self::TABS, true) ? $request->query('tab') : 'teach';
        $filter = in_array($request->query('filter'), ['needs', 'all', 'done'], true) ? $request->query('filter') : 'needs';

        $order = array_flip(AiBrain::TOPICS);
        $entries = collect(AiBrain::all())
            ->sortBy(fn (array $entry) => ($order[AiBrain::normalizeTopic($entry['topic'] ?? null)] ?? 99))
            ->groupBy(fn (array $entry) => AiBrain::normalizeTopic($entry['topic'] ?? null));

        $questions = ChatLog::recent(300);
        $needs = count(array_filter($questions, fn (array $row) => ChatLog::needsKnowledge($row)));

        $shown = array_values(array_filter($questions, fn (array $row) => match ($filter) {
            'needs' => ChatLog::needsKnowledge($row),
            'done' => ! empty($row['unsure']) && ! empty($row['reviewed']),
            default => true,
        }));

        return view('admin.ai-brain.index', [
            'tab' => $tab,
            'filter' => $filter,
            'topics' => AiBrain::TOPICS,
            'entries' => $entries,
            'counts' => AiBrain::counts(),
            'questions' => array_slice($shown, 0, 100),
            'needs' => $needs,
            'week' => ChatLog::thisWeek(),
            'aiReady' => AiProvider::ready(),
            'provider' => ucfirst(AiProvider::name()),
            'model' => AiProvider::model(),
            'keepDays' => ChatLog::KEEP_DAYS,
            'connection' => $this->connection(),
        ]);
    }

    /**
     * Everything the Connection tab shows. The key itself is never sent to the page.
     *
     * @return array<string, mixed>
     */
    protected function connection(): array
    {
        $name = AiProvider::name();
        $status = AiConnection::status();

        $providers = [
            'gemini' => ['label' => 'Google Gemini (has a free tier)', 'url' => 'https://aistudio.google.com/apikey'],
            'openai' => ['label' => 'OpenAI', 'url' => 'https://platform.openai.com/api-keys'],
            'anthropic' => ['label' => 'Anthropic Claude', 'url' => 'https://console.anthropic.com/settings/keys'],
        ];

        foreach ($providers as $key => &$provider) {
            $provider['default_model'] = (string) config("services.{$key}.model");
            $provider['has_saved_key'] = AiConnection::hasSavedKey($key);
        }
        unset($provider);

        return [
            'name' => $name,
            'providers' => $providers,
            'source' => AiProvider::source(),
            'masked' => AiConnection::mask(AiProvider::key()),
            'saved_model' => AiConnection::model($name),
            'saved_at' => AiConnection::savedAt($name),
            'status' => $status,
            'attention' => AiConnection::needsAttention(),
        ];
    }

    /**
     * Save the provider and a new API key (and model) chosen in the admin. An empty key field keeps
     * the key already saved. The key is stored encrypted and is never shown again.
     */
    public function saveConnection(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'provider' => ['required', Rule::in(AiProvider::PROVIDERS)],
            'api_key' => ['nullable', 'string', 'max:400'],
            'model' => ['nullable', 'string', 'max:100', 'regex:/^[A-Za-z0-9._:\/-]+$/'],
        ], [
            'model.regex' => 'The model name can only have letters, numbers and . _ : / - (no spaces).',
        ]);

        $provider = $data['provider'];
        $key = trim((string) ($data['api_key'] ?? ''), " \t\n\r\0\x0B\"'");

        if ($key === '' && AiProvider::keyFor($provider) === '') {
            return back()->withInput($request->except('api_key'))->withErrors(['api_key' => 'Paste the API key from your '.ucfirst($provider).' account.']);
        }

        AiConnection::save($provider, $key, $data['model'] ?? null);

        return redirect()->route('admin.ai-brain.index', ['tab' => 'connection', 'check' => 1])
            ->with('status', $key !== '' ? 'The new key was saved. Checking that it works...' : 'Saved. Checking that it works...');
    }

    /**
     * Send one tiny request to the AI to see whether the key and model work right now.
     */
    public function checkConnection(): JsonResponse
    {
        if (! AiProvider::ready()) {
            return response()->json(['message' => 'There is no API key yet. Paste one above and save it.'], 422);
        }

        $result = AiClient::ask('You are a connection test. Reply with the single word OK.', [['role' => 'user', 'content' => 'Test']], 40);

        if ($result['text'] === null) {
            return response()->json(['message' => $result['error'] ?? 'The AI could not be reached.'], 502);
        }

        return response()->json(['message' => 'Connected. '.ucfirst(AiProvider::name()).' ('.AiProvider::model().') is answering.']);
    }

    /**
     * Forget the key saved in the admin, so the key in the .env file (if any) is used again.
     */
    public function removeConnection(): RedirectResponse
    {
        AiConnection::remove(AiProvider::name());

        return redirect()->route('admin.ai-brain.index', ['tab' => 'connection'])
            ->with('status', 'The saved key was removed. The key in the .env file is used again, if there is one.');
    }

    public function storeEntry(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'topic' => ['required', Rule::in(AiBrain::TOPICS)],
            'text' => ['required', 'string', 'min:5', 'max:'.AiBrain::MAX_TEXT],
        ]);

        $saved = AiBrain::add($data['topic'], $data['text']);

        return redirect()->route('admin.ai-brain.index', ['tab' => 'knowledge'])->with('status', $saved
            ? 'Saved. The assistant will use this from the next question.'
            : 'The knowledge list is full ('.AiBrain::MAX_ENTRIES.' entries). Delete or combine some entries first.');
    }

    public function updateEntry(Request $request, string $id): RedirectResponse
    {
        $data = $request->validate([
            'topic' => ['required', Rule::in(AiBrain::TOPICS)],
            'text' => ['required', 'string', 'min:5', 'max:'.AiBrain::MAX_TEXT],
        ]);

        $active = collect(AiBrain::all())->firstWhere('id', $id)['active'] ?? true;

        AiBrain::update($id, $data['topic'], $data['text'], (bool) $active);

        return redirect()->route('admin.ai-brain.index', ['tab' => 'knowledge'])->with('status', 'Changes saved.');
    }

    public function toggleEntry(string $id): RedirectResponse
    {
        AiBrain::toggle($id);

        return redirect()->route('admin.ai-brain.index', ['tab' => 'knowledge'])->with('status', 'Updated.');
    }

    public function destroyEntry(string $id): RedirectResponse
    {
        AiBrain::delete($id);

        return redirect()->route('admin.ai-brain.index', ['tab' => 'knowledge'])->with('status', 'The entry was deleted.');
    }

    public function questionDone(string $id): RedirectResponse
    {
        ChatLog::markReviewed($id);

        return redirect()->route('admin.ai-brain.index', ['tab' => 'questions'])->with('status', 'Marked as done.');
    }

    /**
     * The owner describes something new in plain words; the AI turns it into clear knowledge cards
     * to review. Nothing is saved until the owner presses Save.
     */
    public function teach(Request $request): JsonResponse
    {
        $data = $request->validate(['message' => ['required', 'string', 'min:5', 'max:4000']]);

        if (! AiProvider::ready()) {
            return response()->json([
                'message' => 'The AI is not connected yet. Paste an API key in the Connection tab, or write the knowledge yourself in the Knowledge tab.',
            ], 503);
        }

        $topics = implode('", "', AiBrain::TOPICS);
        $existing = AiBrain::promptText(5000);
        $known = $existing !== '' ? "\n\nWHAT THE ASSISTANT ALREADY KNOWS (do not repeat it unless the owner is changing it):\n{$existing}" : '';

        $system = <<<TXT
You are the knowledge editor for the AI assistant of Casa Verde Cliff Resort & Spa (Camotes Island, Philippines). The resort owner writes new facts in their own words (English, Bisaya/Cebuano or Tagalog). Turn them into clear knowledge cards that the guest assistant can use to answer guests.

RULES
- Use ONLY facts the owner wrote. Never add, guess or improve facts, prices, times or policies.
- Write each card in clear, friendly English, 1 to 3 sentences, from the resort's point of view (for example: "Check-in is from 2 PM."). Keep numbers, currencies and times exactly as given.
- One subject per card. Split a long message into several cards. Maximum 8 cards.
- The topic must be exactly one of: "{$topics}".
- If something important looks missing or unclear (for example a price without a currency, or a time without AM/PM), still create the card with what was given and mention the gap in "note".
- Reply with JSON only, no markdown and no extra text, in exactly this shape: {"entries":[{"topic":"...","text":"..."}],"note":"one short sentence for the owner, or an empty string"}{$known}
TXT;

        $result = AiClient::ask($system, [['role' => 'user', 'content' => $data['message']]], 1600);

        if ($result['text'] === null) {
            return response()->json(['message' => $result['error'] ?? 'The AI could not answer.'], 502);
        }

        $parsed = $this->parseJson($result['text']);
        $entries = [];

        foreach (array_slice((array) ($parsed['entries'] ?? []), 0, 8) as $entry) {
            $text = AiBrain::clean(is_array($entry) ? ($entry['text'] ?? '') : '');

            if ($text !== '') {
                $entries[] = ['topic' => AiBrain::normalizeTopic($entry['topic'] ?? null), 'text' => $text];
            }
        }

        if ($entries === []) {
            return response()->json(['message' => 'The AI could not turn that into knowledge cards. Try writing it again with a little more detail.'], 422);
        }

        return response()->json([
            'entries' => $entries,
            'note' => mb_substr(trim((string) ($parsed['note'] ?? '')), 0, 300),
        ]);
    }

    /**
     * Save the cards the owner reviewed.
     */
    public function saveDrafts(Request $request): JsonResponse
    {
        $data = $request->validate([
            'entries' => ['required', 'array', 'min:1', 'max:8'],
            'entries.*.topic' => ['required', 'string', 'max:60'],
            'entries.*.text' => ['required', 'string', 'min:5', 'max:'.AiBrain::MAX_TEXT],
        ]);

        $saved = 0;

        foreach ($data['entries'] as $entry) {
            if (AiBrain::add($entry['topic'], $entry['text'])) {
                $saved++;
            }
        }

        if ($saved === 0) {
            return response()->json(['message' => 'Nothing was saved. The knowledge list may be full ('.AiBrain::MAX_ENTRIES.' entries).'], 422);
        }

        return response()->json(['saved' => $saved, 'counts' => AiBrain::counts()]);
    }

    /**
     * Try the assistant exactly as a guest would meet it. Not saved, not counted.
     */
    public function test(Request $request): JsonResponse
    {
        $data = $request->validate([
            'messages' => ['required', 'array', 'min:1', 'max:40'],
            'messages.*.role' => ['required', 'in:user,assistant'],
            'messages.*.content' => ['required', 'string', 'max:1500'],
        ]);

        if (! AiProvider::ready()) {
            return response()->json(['message' => 'The AI is not connected yet, so there is nothing to test. Paste an API key in the Connection tab.'], 503);
        }

        $messages = ChatPrompt::clean($data['messages']);

        if ($messages === []) {
            return response()->json(['message' => 'Please type a question first.'], 422);
        }

        $settings = HomeContent::where('section', 'ai_chat')->orderBy('sort_order')->first();
        $faq = HomeContent::where('section', 'chat_faq')->where('active', true)->orderBy('sort_order')->get();

        $result = AiClient::ask(ChatPrompt::build($settings, $faq), $messages);

        if ($result['text'] === null) {
            return response()->json(['message' => $result['error'] ?? 'The AI could not answer.'], 502);
        }

        [$reply, $unsure] = ChatPrompt::strip($result['text']);

        return response()->json(['reply' => $reply, 'unsure' => $unsure]);
    }

    /**
     * The first JSON object found in the AI's answer (it sometimes wraps it in a code block).
     *
     * @return array<string, mixed>
     */
    protected function parseJson(string $text): array
    {
        $start = strpos($text, '{');
        $end = strrpos($text, '}');

        if ($start === false || $end === false || $end <= $start) {
            return [];
        }

        $data = json_decode(substr($text, $start, $end - $start + 1), true);

        return is_array($data) ? $data : [];
    }
}
