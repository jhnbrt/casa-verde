<?php

namespace App\Support;

use Illuminate\Support\Str;

/**
 * The AI's extra knowledge about Casa Verde, kept in one private file (no database):
 * storage/app/ai-brain/knowledge.json. The customer chat reads it on every question, so a change
 * made in the admin applies at once. Back this folder up together with the site.
 */
class AiBrain
{
    public const TOPICS = [
        'Rooms & villas',
        'Rates & payment',
        'Check-in & policies',
        'Dining',
        'Wellness & spa',
        'Activities & experiences',
        'Getting here & location',
        'Long stays',
        'Contact & booking',
        'About the resort',
        'Other',
    ];

    public const MAX_ENTRIES = 300;

    public const MAX_TEXT = 1500;

    /** The private folder for the AI Brain files. */
    public static function dir(): string
    {
        return rtrim((string) (config('aichat.storage_path') ?: storage_path('app/ai-brain')), '/\\');
    }

    protected static function file(): string
    {
        return self::dir().DIRECTORY_SEPARATOR.'knowledge.json';
    }

    /**
     * @return array<int, array{id: string, topic: string, text: string, active: bool, updated_at: string}>
     */
    public static function all(): array
    {
        if (! is_file(self::file())) {
            return [];
        }

        $data = json_decode((string) @file_get_contents(self::file()), true);
        $entries = is_array($data['entries'] ?? null) ? $data['entries'] : [];

        return array_values(array_filter($entries, fn ($entry) => is_array($entry) && isset($entry['id'], $entry['text'])));
    }

    /**
     * @return array{total: int, active: int}
     */
    public static function counts(): array
    {
        $entries = self::all();

        return [
            'total' => count($entries),
            'active' => count(array_filter($entries, fn ($entry) => ! empty($entry['active']))),
        ];
    }

    public static function normalizeTopic(?string $topic): string
    {
        return in_array($topic, self::TOPICS, true) ? $topic : 'Other';
    }

    public static function clean(?string $text): string
    {
        $text = strip_tags((string) $text);
        $text = preg_replace("/[ \t]+/", ' ', str_replace(["\r\n", "\r"], "\n", $text));
        $text = preg_replace("/\n{3,}/", "\n\n", (string) $text);

        return mb_substr(trim((string) $text), 0, self::MAX_TEXT);
    }

    /**
     * @return array{id: string, topic: string, text: string, active: bool, updated_at: string}|null  null when the list is full
     */
    public static function add(string $topic, string $text, bool $active = true): ?array
    {
        $text = self::clean($text);

        if ($text === '') {
            return null;
        }

        return self::mutate(function (array &$entries) use ($topic, $text, $active) {
            if (count($entries) >= self::MAX_ENTRIES) {
                return null;
            }

            $entry = [
                'id' => 'k'.Str::lower(Str::random(10)),
                'topic' => self::normalizeTopic($topic),
                'text' => $text,
                'active' => $active,
                'updated_at' => now()->toIso8601String(),
            ];

            $entries[] = $entry;

            return $entry;
        });
    }

    public static function update(string $id, string $topic, string $text, bool $active): bool
    {
        $text = self::clean($text);

        if ($text === '') {
            return false;
        }

        return (bool) self::mutate(function (array &$entries) use ($id, $topic, $text, $active) {
            foreach ($entries as $index => $entry) {
                if (($entry['id'] ?? null) === $id) {
                    $entries[$index] = [
                        'id' => $id,
                        'topic' => self::normalizeTopic($topic),
                        'text' => $text,
                        'active' => $active,
                        'updated_at' => now()->toIso8601String(),
                    ];

                    return true;
                }
            }

            return false;
        });
    }

    public static function toggle(string $id): bool
    {
        return (bool) self::mutate(function (array &$entries) use ($id) {
            foreach ($entries as $index => $entry) {
                if (($entry['id'] ?? null) === $id) {
                    $entries[$index]['active'] = empty($entry['active']);
                    $entries[$index]['updated_at'] = now()->toIso8601String();

                    return true;
                }
            }

            return false;
        });
    }

    public static function delete(string $id): bool
    {
        return (bool) self::mutate(function (array &$entries) use ($id) {
            $before = count($entries);
            $entries = array_values(array_filter($entries, fn ($entry) => ($entry['id'] ?? null) !== $id));

            return count($entries) < $before;
        });
    }

    /**
     * The active knowledge written out for the AI, grouped by topic. Stops adding once it reaches
     * the size limit so a very long list can never swamp the prompt.
     */
    public static function promptText(int $maxChars = 14000): string
    {
        $groups = [];

        foreach (self::all() as $entry) {
            if (! empty($entry['active'])) {
                $groups[self::normalizeTopic($entry['topic'] ?? null)][] = trim((string) $entry['text']);
            }
        }

        if ($groups === []) {
            return '';
        }

        $lines = [];
        $length = 0;
        $full = false;

        foreach (self::TOPICS as $topic) {
            if (empty($groups[$topic])) {
                continue;
            }

            $block = [];

            foreach ($groups[$topic] as $text) {
                $line = '- '.str_replace("\n", ' ', $text);

                if ($length + mb_strlen($line) > $maxChars) {
                    $full = true;
                    break;
                }

                $length += mb_strlen($line);
                $block[] = $line;
            }

            // A topic heading is only written when at least one of its entries fits.
            if ($block !== []) {
                $lines[] = strtoupper($topic);
                array_push($lines, ...$block);
                $lines[] = '';
            }

            if ($full) {
                break;
            }
        }

        return trim(implode("\n", $lines));
    }

    /**
     * Read, change and save the file while holding a lock, so two admins saving at once cannot
     * overwrite each other.
     *
     * @param  callable(array<int, array<string, mixed>>): mixed  $change
     */
    protected static function mutate(callable $change): mixed
    {
        if (! is_dir(self::dir())) {
            @mkdir(self::dir(), 0775, true);
        }

        $handle = fopen(self::file(), 'c+');

        if (! $handle) {
            throw new \RuntimeException('The AI Brain file could not be opened for writing: '.self::file());
        }

        try {
            flock($handle, LOCK_EX);

            $data = json_decode((string) stream_get_contents($handle), true);
            $entries = is_array($data['entries'] ?? null) ? array_values($data['entries']) : [];

            $result = $change($entries);

            ftruncate($handle, 0);
            rewind($handle);
            fwrite($handle, json_encode(['entries' => array_values($entries)], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            fflush($handle);

            return $result;
        } finally {
            flock($handle, LOCK_UN);
            fclose($handle);
        }
    }
}
