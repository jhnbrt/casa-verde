<?php

namespace App\Support;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * One place that talks to the chosen AI service (Gemini, OpenAI or Anthropic), used by the customer
 * chat and by the admin's AI Brain page.
 */
class AiClient
{
    /**
     * @param  array<int, array{role: string, content: string}>  $messages
     * @return array{text: ?string, error: ?string}
     */
    public static function ask(string $system, array $messages, ?int $maxTokens = null): array
    {
        $provider = AiProvider::name();

        try {
            $response = match ($provider) {
                'openai' => self::openAi($system, $messages, $maxTokens),
                'anthropic' => self::anthropic($system, $messages, $maxTokens),
                default => self::gemini($system, $messages, $maxTokens),
            };
        } catch (\Throwable $e) {
            Log::warning('AI chat request error', ['provider' => $provider, 'error' => $e->getMessage()]);

            return ['text' => null, 'error' => 'The AI service could not be reached. Check the internet connection of the server.'];
        }

        if ($response->failed()) {
            Log::warning('AI chat request failed', [
                'provider' => $provider,
                'status' => $response->status(),
                'body' => Str::limit($response->body(), 500),
            ]);

            $kind = self::kind($response);
            $message = self::explain($response, $kind);

            // Remember key and credit problems so the admin is told to renew the key.
            if (in_array($kind, ['key', 'quota'], true)) {
                AiConnection::recordFailure($kind, $message);
            }

            return ['text' => null, 'error' => $message];
        }

        $text = match ($provider) {
            'openai' => (string) $response->json('choices.0.message.content', ''),
            'anthropic' => collect($response->json('content', []))->where('type', 'text')->pluck('text')->implode("\n"),
            default => collect($response->json('candidates.0.content.parts', []))->pluck('text')->filter()->implode("\n"),
        };

        $text = trim($text);

        // A working key clears an earlier warning (only written when something changed).
        if ($text !== '' && AiConnection::status()['ok'] !== true) {
            AiConnection::recordSuccess();
        }

        return $text !== ''
            ? ['text' => $text, 'error' => null]
            : ['text' => null, 'error' => 'The AI sent back an empty answer. Please try again.'];
    }

    /**
     * Just the answer, or null when the AI could not give one.
     *
     * @param  array<int, array{role: string, content: string}>  $messages
     */
    public static function complete(string $system, array $messages, ?int $maxTokens = null): ?string
    {
        return self::ask($system, $messages, $maxTokens)['text'];
    }

    /**
     * key (rejected, expired or wrong), quota (no credit left), limit (busy or free limit), model, other.
     */
    protected static function kind(Response $response): string
    {
        $body = strtolower($response->body());

        return match (true) {
            in_array($response->status(), [401, 403], true) => 'key',
            $response->status() === 400 && (str_contains($body, 'api key') || str_contains($body, 'api_key')) => 'key',
            str_contains($body, 'insufficient_quota') || str_contains($body, 'billing') && str_contains($body, 'quota') => 'quota',
            $response->status() === 429 => 'limit',
            $response->status() === 404 => 'model',
            default => 'other',
        };
    }

    protected static function explain(Response $response, string $kind): string
    {
        return match ($kind) {
            'key' => 'The AI service did not accept the API key. It may have expired or been deleted. Paste a new key in AI Brain > Connection.',
            'quota' => 'The AI account has no credit left or its free allowance is used up. Check the billing page of the AI provider, or paste a key from another account in AI Brain > Connection.',
            'limit' => 'The AI is busy or its free limit was reached. Please try again in a minute.',
            'model' => 'The AI model was not found. Check the model name in AI Brain > Connection.',
            default => 'The AI service returned an error (code '.$response->status().'). Details are in storage/logs/laravel.log.',
        };
    }

    /**
     * @param  array<int, array{role: string, content: string}>  $messages
     */
    protected static function gemini(string $system, array $messages, ?int $maxTokens): Response
    {
        return Http::withHeaders(['x-goog-api-key' => AiProvider::key()])
            ->acceptJson()
            ->timeout(40)
            ->post('https://generativelanguage.googleapis.com/v1beta/models/'.AiProvider::model().':generateContent', [
                'systemInstruction' => ['parts' => [['text' => $system]]],
                'contents' => array_map(fn (array $message): array => [
                    'role' => $message['role'] === 'assistant' ? 'model' : 'user',
                    'parts' => [['text' => $message['content']]],
                ], $messages),
                'generationConfig' => ['maxOutputTokens' => $maxTokens ?? 700],
            ]);
    }

    /**
     * @param  array<int, array{role: string, content: string}>  $messages
     */
    protected static function openAi(string $system, array $messages, ?int $maxTokens): Response
    {
        $payload = [
            'model' => AiProvider::model(),
            // Room for the model's own thinking as well as the answer.
            'max_completion_tokens' => max(1200, (int) $maxTokens),
            'messages' => array_merge([['role' => 'system', 'content' => $system]], $messages),
        ];

        if (filled(config('services.openai.reasoning_effort'))) {
            $payload['reasoning_effort'] = trim((string) config('services.openai.reasoning_effort'));
        }

        return Http::withToken(AiProvider::key())
            ->acceptJson()
            ->timeout(40)
            ->post('https://api.openai.com/v1/chat/completions', $payload);
    }

    /**
     * @param  array<int, array{role: string, content: string}>  $messages
     */
    protected static function anthropic(string $system, array $messages, ?int $maxTokens): Response
    {
        return Http::withHeaders([
            'x-api-key' => AiProvider::key(),
            'anthropic-version' => '2023-06-01',
        ])->acceptJson()->timeout(40)->post('https://api.anthropic.com/v1/messages', [
            'model' => AiProvider::model(),
            'max_tokens' => $maxTokens ?? 500,
            'system' => $system,
            'messages' => $messages,
        ]);
    }
}
