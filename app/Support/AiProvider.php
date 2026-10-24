<?php

namespace App\Support;

/**
 * Which AI service answers the website chat, and with which key and model. What is saved in the admin
 * (AI Brain > Connection) wins; otherwise the .env file is used: AI_PROVIDER=gemini, openai or
 * anthropic, and when that is not set the first provider whose key is present. With no key at all the
 * chat answers from the quick answers only.
 */
class AiProvider
{
    public const PROVIDERS = ['gemini', 'openai', 'anthropic'];

    public static function name(): string
    {
        if ($saved = AiConnection::provider()) {
            return $saved;
        }

        $chosen = strtolower(trim((string) config('services.ai_provider')));

        if (in_array($chosen, self::PROVIDERS, true)) {
            return $chosen;
        }

        foreach (self::PROVIDERS as $provider) {
            if (self::keyFor($provider) !== '') {
                return $provider;
            }
        }

        return 'gemini';
    }

    public static function keyFor(string $provider): string
    {
        $saved = AiConnection::key($provider);

        return $saved !== '' ? $saved : trim((string) config("services.{$provider}.key"));
    }

    public static function key(): string
    {
        return self::keyFor(self::name());
    }

    public static function model(): string
    {
        $name = self::name();
        $saved = AiConnection::model($name);

        return $saved !== '' ? $saved : trim((string) config("services.{$name}.model"));
    }

    public static function ready(): bool
    {
        return self::key() !== '';
    }

    /** Where the key in use comes from: the admin, the .env file, or nowhere. */
    public static function source(): string
    {
        $name = self::name();

        if (AiConnection::hasSavedKey($name)) {
            return 'admin';
        }

        return trim((string) config("services.{$name}.key")) !== '' ? 'env' : 'none';
    }

    /** A short code for the provider and key in use, never the key itself. */
    public static function fingerprint(): string
    {
        return substr(sha1(self::name().'|'.self::key()), 0, 12);
    }
}
