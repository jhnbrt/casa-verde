<?php

namespace App\Http\Controllers;

use App\Models\HomeContent;
use App\Support\AiClient;
use App\Support\AiProvider;
use App\Support\ChatLog;
use App\Support\ChatPrompt;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class AiChatController extends Controller
{
    /**
     * Answer one message. Order: the AI when a key is set and the visitor is within the daily
     * allowance; otherwise (or when the AI is busy or fails) the quick answers written in the admin.
     * Every question and answer is saved for the owner to review (admin: AI Brain).
     */
    public function reply(Request $request): JsonResponse
    {
        $settings = HomeContent::where('section', 'ai_chat')->orderBy('sort_order')->first();
        $faq = HomeContent::where('section', 'chat_faq')->where('active', true)->orderBy('sort_order')->get();

        if (($settings && ! $settings->active) || (! AiProvider::ready() && $faq->isEmpty())) {
            return response()->json(['message' => 'The assistant is not available right now.'], 503);
        }

        $data = $request->validate([
            'messages' => ['required', 'array', 'min:1', 'max:40'],
            'messages.*.role' => ['required', 'in:user,assistant'],
            'messages.*.content' => ['required', 'string', 'max:1500'],
        ]);

        $messages = ChatPrompt::clean($data['messages']);

        if ($messages === []) {
            return response()->json(['message' => 'Please type a question first.'], 422);
        }

        $question = $messages[count($messages) - 1]['content'];
        $visitor = substr(sha1((string) $request->ip().config('app.key').now()->format('Ymd')), 0, 8);

        // A daily allowance per visitor keeps any AI bill predictable; quick answers are always free.
        $limit = max(1, (int) ($settings?->price ?: config('aichat.chat.daily_limit')));
        $counter = 'aichat:'.sha1((string) $request->ip()).':'.now()->format('Ymd');
        $used = (int) Cache::get($counter, 0);
        $overLimit = $used >= $limit;

        if (AiProvider::ready() && ! $overLimit) {
            $reply = AiClient::complete(ChatPrompt::build($settings, $faq), $messages);

            if ($reply !== null) {
                Cache::put($counter, $used + 1, now()->endOfDay());
                [$reply, $unsure] = ChatPrompt::strip($reply);

                if ($reply !== '') {
                    ChatLog::record($question, $reply, 'ai', $unsure, $visitor);

                    return response()->json(['reply' => $reply]);
                }
            }
        }

        // No AI, or it could not answer: use a quick answer if one fits.
        $answer = $this->quickAnswer($faq, $question);

        if ($answer !== null) {
            ChatLog::record($question, $answer, 'quick', false, $visitor);

            return response()->json(['reply' => $answer]);
        }

        if (! AiProvider::ready()) {
            ChatLog::record($question, config('aichat.not_sure'), 'fallback', true, $visitor);

            return response()->json(['reply' => config('aichat.not_sure')]);
        }

        if ($overLimit) {
            $message = "You've reached today's chat limit. Please send us your question through the contact section and our team will reply personally.";
            ChatLog::record($question, $message, 'error', true, $visitor);

            return response()->json(['message' => $message], 429);
        }

        $message = "Sorry, I'm having trouble answering right now. Please try again in a moment or use the contact section and our team will reply.";
        ChatLog::record($question, $message, 'error', true, $visitor);

        return response()->json(['message' => $message], 502);
    }

    /**
     * The quick answer whose keywords match the guest's message best, or null when none does.
     * A keyword counts only as a whole word or phrase; longer phrases weigh more.
     *
     * @param  Collection<int, HomeContent>  $faq
     */
    protected function quickAnswer(Collection $faq, string $question): ?string
    {
        $best = null;
        $bestScore = 0;

        foreach ($faq as $entry) {
            $score = 0;

            foreach (array_filter(array_map('trim', (array) $entry->features), 'strlen') as $keyword) {
                $pattern = '/(?<![\p{L}\p{N}])'.preg_quote(mb_strtolower($keyword), '/').'(?![\p{L}\p{N}])/u';

                if (preg_match($pattern, mb_strtolower($question))) {
                    $score += 3 + (str_word_count($keyword) - 1);
                }
            }

            if ($score > $bestScore) {
                $best = $entry;
                $bestScore = $score;
            }
        }

        return $best && filled($best->description) ? trim((string) $best->description) : null;
    }
}
