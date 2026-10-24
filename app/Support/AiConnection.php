<?php

namespace App\Support;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

/**
 * The AI provider, API key and model chosen in the admin (AI Brain > Connection), kept in one private
 * file (no database): storage/app/ai-brain/connection.json. Keys are stored encrypted with the site's
 * APP_KEY and are never shown in full. What is saved here wins over the .env file, so an expired key
 * can be replaced without touching the server.
 *
 * It also remembers whether the last AI request was rejected because of the key, so the admin can be
 * told to renew it.
 */
class AiConnection
{
    /** The file's content, read once per request (keyed by path; cleared whenever it is written). */
    protected static array $memo = [];

    protected static function file(): string
    {
        return AiBrain::dir().DIRECTORY_SEPARATOR.'connection.json';
    }

    /**
     * @return array<string, mixed>
     */
    protected static function read(): array
    {
        $path = self::file();

        if (array_key_exists($path, self::$memo)) {
            return self::$memo[$path];
        }

        $data = is_file($path) ? json_decode((string) @file_get_contents($path), true) : [];

        return self::$memo[$path] = is_array($data) ? $data : [];
    }

    /**
     * Read, change and save the file while holding a lock.
     *
     * @param  callable(array<string, mixed>): mixed  $change
     */
    protected static function mutate(callable $change): mixed
    {
        if (! is_dir(AiBrain::dir())) {
            @mkdir(AiBrain::dir(), 0775, true);
        }

        $handle = fopen(self::file(), 'c+');

        if (! $handle) {
            throw new \RuntimeException('The AI connection file could not be opened for writing: '.self::file());
        }

        try {
            flock($handle, LOCK_EX);
            unset(self::$memo[self::file()]);

            $data = json_decode((string) stream_get_contents($handle), true);
            $data = is_array($data) ? $data : [];

            $result = $change($data);

            ftruncate($handle, 0);
            rewind($handle);
            fwrite($handle, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            fflush($handle);
            @chmod(self::file(), 0600);
            unset(self::$memo[self::file()]);

            return $result;
        } finally {
            flock($handle, LOCK_UN);
            fclose($handle);
        }
    }

    /** The provider chosen in the admin, or null when none was. */
    public static function provider(): ?string
    {
        $provider = self::read()['provider'] ?? null;

        return in_array($provider, AiProvider::PROVIDERS, true) ? $provider : null;
    }

    /** The key saved in the admin for a provider ('' when there is none or it cannot be read). */
    public static function key(string $provider): string
    {
        $encrypted = self::read()['providers'][$provider]['key'] ?? null;

        if (! is_string($encrypted) || $encrypted === '') {
            return '';
        }

        try {
            return trim(Crypt::decryptString($encrypted));
        } catch (DecryptException) {
            return '';
        }
    }

    public static function model(string $provider): string
    {
        return trim((string) (self::read()['providers'][$provider]['model'] ?? ''));
    }

    /**
     * Save the provider, and its key and model. An empty key keeps the one already saved.
     */
    public static function save(string $provider, ?string $key, ?string $model): void
    {
        $key = trim((string) $key, " \t\n\r\0\x0B\"'");
        $model = trim((string) $model);

        self::mutate(function (array &$data) use ($provider, $key, $model) {
            $data['provider'] = $provider;
            $data['providers'][$provider] = $data['providers'][$provider] ?? [];

            if ($key !== '') {
                $data['providers'][$provider]['key'] = Crypt::encryptString($key);
                $data['providers'][$provider]['saved_at'] = now()->toIso8601String();
            }

            if ($model !== '') {
                $data['providers'][$provider]['model'] = $model;
            } else {
                unset($data['providers'][$provider]['model']);
            }

            // A new key gets a fresh start: the old warning no longer applies.
            unset($data['status']);
        });
    }

    /** Forget what was saved for a provider, so the .env file is used again. */
    public static function remove(string $provider): void
    {
        self::mutate(function (array &$data) use ($provider) {
            unset($data['providers'][$provider]);

            if (($data['provider'] ?? null) === $provider) {
                unset($data['provider']);
            }

            unset($data['status']);
        });
    }

    public static function hasSavedKey(string $provider): bool
    {
        return self::key($provider) !== '';
    }

    public static function savedAt(string $provider): ?string
    {
        $at = self::read()['providers'][$provider]['saved_at'] ?? null;

        return is_string($at) ? $at : null;
    }

    /** A key written so it can be recognised but not used: only the last four characters show. */
    public static function mask(string $key): string
    {
        $key = trim($key);

        if ($key === '') {
            return '';
        }

        return mb_strlen($key) >= 12 ? '•••• •••• '.mb_substr($key, -4) : '•••• ••••';
    }

    public static function recordSuccess(): void
    {
        try {
            self::mutate(function (array &$data) {
                $data['status'] = ['ok' => true, 'at' => now()->toIso8601String(), 'fingerprint' => AiProvider::fingerprint()];
            });
        } catch (\Throwable) {
            // Remembering the status is optional.
        }
    }

    /**
     * @param  string  $kind  key (rejected or expired), quota (no credit or limit used up), limit (busy) or other
     */
    public static function recordFailure(string $kind, string $message): void
    {
        try {
            self::mutate(function (array &$data) use ($kind, $message) {
                $data['status'] = [
                    'ok' => false,
                    'kind' => $kind,
                    'message' => mb_substr($message, 0, 300),
                    'at' => now()->toIso8601String(),
                    'fingerprint' => AiProvider::fingerprint(),
                ];
            });
        } catch (\Throwable) {
            // Remembering the status is optional.
        }
    }

    /**
     * What happened on the last AI request made with the key in use now. A problem recorded for an
     * older key is ignored, so replacing the key clears the warning.
     *
     * @return array{ok: bool|null, kind: ?string, message: ?string, at: ?string}
     */
    public static function status(): array
    {
        $status = self::read()['status'] ?? null;

        if (! is_array($status) || ($status['fingerprint'] ?? null) !== AiProvider::fingerprint()) {
            return ['ok' => null, 'kind' => null, 'message' => null, 'at' => null];
        }

        return [
            'ok' => $status['ok'] ?? null,
            'kind' => $status['kind'] ?? null,
            'message' => $status['message'] ?? null,
            'at' => $status['at'] ?? null,
        ];
    }

    /** True when the AI service refused the key or the account has no credit left. */
    public static function needsAttention(): bool
    {
        $status = self::status();

        return $status['ok'] === false && in_array($status['kind'], ['key', 'quota'], true);
    }
}
