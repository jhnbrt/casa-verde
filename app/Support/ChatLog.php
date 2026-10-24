<?php

namespace App\Support;

use Illuminate\Support\Str;

/**
 * What guests asked the website chat, kept as plain files (no database) in
 * storage/app/ai-brain/chats, one file per day, and deleted after KEEP_DAYS days. Guests are only
 * identified by a short code that changes every day, never by name, email or IP address.
 */
class ChatLog
{
    public const KEEP_DAYS = 30;

    protected static function dir(): string
    {
        return AiBrain::dir().DIRECTORY_SEPARATOR.'chats';
    }

    /**
     * Save one question and the answer it got. Never lets a problem with the files break the chat.
     */
    public static function record(string $question, string $answer, string $source, bool $unsure, string $visitor): void
    {
        try {
            if (! is_dir(self::dir())) {
                @mkdir(self::dir(), 0775, true);
            }

            $row = [
                'id' => Str::lower(Str::random(12)),
                'at' => now()->toIso8601String(),
                'visitor' => $visitor,
                'question' => mb_substr(trim($question), 0, 500),
                'answer' => mb_substr(trim($answer), 0, 1500),
                'source' => $source,
                'unsure' => $unsure,
            ];

            file_put_contents(
                self::dir().DIRECTORY_SEPARATOR.now()->format('Y-m-d').'.jsonl',
                json_encode($row, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)."\n",
                FILE_APPEND | LOCK_EX
            );

            self::prune();
            @unlink(self::dir().DIRECTORY_SEPARATOR.'needs.cache');
        } catch (\Throwable) {
            // Logging is optional.
        }
    }

    /**
     * The newest questions first, each with a "reviewed" flag.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function recent(int $limit = 200): array
    {
        $files = glob(self::dir().DIRECTORY_SEPARATOR.'*.jsonl') ?: [];
        rsort($files);

        $reviewed = array_flip(self::reviewedIds());
        $rows = [];

        foreach ($files as $file) {
            $lines = array_reverse(file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: []);

            foreach ($lines as $line) {
                $row = json_decode($line, true);

                if (! is_array($row) || ! isset($row['id'], $row['question'])) {
                    continue;
                }

                $row['reviewed'] = isset($reviewed[$row['id']]);
                $rows[] = $row;

                if (count($rows) >= $limit) {
                    break 2;
                }
            }
        }

        return $rows;
    }

    /** A guest question the assistant could not answer and that has not been dealt with yet. */
    public static function needsKnowledge(array $row): bool
    {
        return ! empty($row['unsure']) && empty($row['reviewed']);
    }

    /** How many questions are waiting for the owner (kept for a minute so every admin page stays fast). */
    public static function needsKnowledgeCount(): int
    {
        $cache = self::dir().DIRECTORY_SEPARATOR.'needs.cache';

        try {
            if (is_file($cache) && time() - filemtime($cache) < 60) {
                return (int) file_get_contents($cache);
            }

            $count = count(array_filter(self::recent(500), fn (array $row) => self::needsKnowledge($row)));

            if (is_dir(self::dir())) {
                @file_put_contents($cache, (string) $count);
            }

            return $count;
        } catch (\Throwable) {
            return 0;
        }
    }

    /** Questions asked in the last 7 days. */
    public static function thisWeek(): int
    {
        $since = now()->subDays(7);

        return count(array_filter(self::recent(1000), fn (array $row) => ! empty($row['at']) && \Carbon\Carbon::parse($row['at'])->greaterThanOrEqualTo($since)));
    }

    public static function markReviewed(string $id): void
    {
        if (! preg_match('/^[a-z0-9]{6,20}$/', $id)) {
            return;
        }

        if (! is_dir(self::dir())) {
            @mkdir(self::dir(), 0775, true);
        }

        $ids = array_values(array_unique(array_merge(self::reviewedIds(), [$id])));

        file_put_contents(self::dir().DIRECTORY_SEPARATOR.'reviewed.json', json_encode(array_slice($ids, -2000)), LOCK_EX);
        @unlink(self::dir().DIRECTORY_SEPARATOR.'needs.cache');
    }

    /** @return array<int, string> */
    protected static function reviewedIds(): array
    {
        $file = self::dir().DIRECTORY_SEPARATOR.'reviewed.json';
        $ids = is_file($file) ? json_decode((string) @file_get_contents($file), true) : [];

        return is_array($ids) ? array_values(array_filter($ids, 'is_string')) : [];
    }

    /** Delete the daily files older than KEEP_DAYS (checked once a day). */
    protected static function prune(): void
    {
        $marker = self::dir().DIRECTORY_SEPARATOR.'.pruned';

        if (is_file($marker) && trim((string) @file_get_contents($marker)) === now()->format('Y-m-d')) {
            return;
        }

        $limit = now()->subDays(self::KEEP_DAYS)->format('Y-m-d');

        foreach (glob(self::dir().DIRECTORY_SEPARATOR.'*.jsonl') ?: [] as $file) {
            if (basename($file, '.jsonl') < $limit) {
                @unlink($file);
            }
        }

        @file_put_contents($marker, now()->format('Y-m-d'));
    }
}
