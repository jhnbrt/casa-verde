<?php

namespace App\Support;

use App\Models\HomeContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

/**
 * The language menu in the header: which languages are offered (set in the admin, Languages page)
 * and how a visitor's language is picked automatically from their country or browser.
 */
class Languages
{
    public const SECTION = 'language_settings';

    /**
     * @return array{show: bool, auto: bool, ip_lookup: bool, codes: array<int, string>}
     */
    public static function settings(): array
    {
        $row = null;

        try {
            // Looked up without the "active" filter: switching the entry off hides the menu.
            $row = HomeContent::where('section', self::SECTION)->orderBy('sort_order')->first();
        } catch (\Throwable) {
            // table not ready yet: starting values
        }

        $supported = array_keys(config('languages.supported'));

        $saved = $row ? preg_split('/[\s,]+/', trim((string) $row->description), -1, PREG_SPLIT_NO_EMPTY) : [];
        $codes = $saved ?: config('languages.enabled');
        // English is the language of the site, so it is always there; keep the order of the master list.
        $codes = array_values(array_intersect($supported, array_merge(['en'], $codes)));

        $flag = fn ($value, bool $default): bool => trim((string) $value) === ''
            ? $default
            : in_array(strtolower(trim((string) $value)), ['on', 'yes', '1', 'true'], true);

        return [
            'show' => $row ? (bool) $row->active : true,
            'auto' => $flag($row?->subtitle, true),
            'ip_lookup' => $flag($row?->button_text, true),
            'codes' => $codes,
        ];
    }

    /** Saves the admin's choices (the Languages page). */
    public static function save(array $codes, bool $show, bool $auto, bool $ipLookup): void
    {
        $supported = array_keys(config('languages.supported'));
        $codes = array_values(array_intersect($supported, array_merge(['en'], $codes)));

        $row = HomeContent::firstOrNew(['section' => self::SECTION]);
        $row->title = 'Languages';
        $row->description = implode(',', $codes);
        $row->subtitle = $auto ? 'on' : 'off';
        $row->button_text = $ipLookup ? 'on' : 'off';
        $row->active = $show;
        $row->sort_order = $row->sort_order ?: 1;
        $row->save();
    }

    /**
     * The language to show a first-time visitor: by country when we can tell it, otherwise by the
     * language of their browser, otherwise English. Only languages that are offered are returned.
     *
     * @param  array{show: bool, auto: bool, ip_lookup: bool, codes: array<int, string>}|null  $settings
     */
    public static function detect(Request $request, ?array $settings = null): string
    {
        $settings ??= self::settings();

        if (self::looksLikeBot($request)) {
            return 'en';
        }

        $country = self::country($request, $settings['ip_lookup']);
        $byCountry = $country ? (config('languages.countries')[$country] ?? null) : null;

        if ($byCountry && in_array($byCountry, $settings['codes'], true)) {
            return $byCountry;
        }

        return self::fromBrowser($request, $settings['codes']) ?? 'en';
    }

    /** The visitor's country (two letters) from the host's headers, or a cached IP lookup if allowed. */
    public static function country(Request $request, bool $ipLookup): ?string
    {
        foreach (['CF-IPCountry', 'CloudFront-Viewer-Country', 'X-Vercel-IP-Country', 'X-Country-Code', 'X-AppEngine-Country'] as $header) {
            $value = strtoupper(trim((string) $request->header($header)));

            if (preg_match('/^[A-Z]{2}$/', $value) && ! in_array($value, ['XX', 'T1'], true)) {
                return $value;
            }
        }

        if (! $ipLookup) {
            return null;
        }

        $ip = $request->ip();

        // Only public addresses can be looked up (not the developer's own computer).
        if (! $ip || ! filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            return null;
        }

        $key = 'languages.country.'.sha1($ip);
        $cached = Cache::get($key);

        if ($cached !== null) {
            return $cached === '' ? null : $cached;
        }

        $code = '';

        try {
            $response = Http::timeout(2)->connectTimeout(2)->acceptJson()
                ->get(sprintf(config('languages.ip_lookup_url'), urlencode($ip)));

            if ($response->successful() && $response->json('success') !== false) {
                $code = strtoupper((string) $response->json('country_code'));
            }
        } catch (\Throwable) {
            // lookup service down or slow: carry on without a country
        }

        $code = preg_match('/^[A-Z]{2}$/', $code) ? $code : '';

        // Only the country is kept (never the address in clear), a day when found, 10 minutes when not.
        Cache::put($key, $code, $code === '' ? now()->addMinutes(10) : now()->addDay());

        return $code === '' ? null : $code;
    }

    /**
     * The best offered language from the browser's language list (Accept-Language), or null.
     * If the visitor's first choice is English, English stays.
     *
     * @param  array<int, string>  $enabled
     */
    public static function fromBrowser(Request $request, array $enabled): ?string
    {
        $header = (string) $request->header('Accept-Language');

        if ($header === '') {
            return null;
        }

        $tags = [];

        foreach (explode(',', $header) as $part) {
            if (preg_match('/^\s*([a-z]{1,8}(?:-[a-z0-9]{1,8})*)(?:\s*;\s*q=([0-9.]+))?/i', $part, $m)) {
                $tags[] = [strtolower($m[1]), isset($m[2]) ? (float) $m[2] : 1.0];
            }
        }

        usort($tags, fn ($a, $b) => $b[1] <=> $a[1]);

        foreach ($tags as [$tag]) {
            $code = self::codeForTag($tag);

            if ($code === 'en') {
                return 'en';
            }

            if ($code && in_array($code, $enabled, true)) {
                return $code;
            }
        }

        return null;
    }

    /** Maps a browser language tag (ko-KR, zh-Hant, fil...) to one of our codes. */
    protected static function codeForTag(string $tag): ?string
    {
        $special = [
            'zh-tw' => 'zh-TW', 'zh-hk' => 'zh-TW', 'zh-mo' => 'zh-TW', 'zh-hant' => 'zh-TW',
            'zh-cn' => 'zh-CN', 'zh-sg' => 'zh-CN', 'zh-hans' => 'zh-CN', 'zh' => 'zh-CN',
            'fil' => 'tl',
        ];

        // Try the whole tag first, then shorter ones: zh-hant-hk, zh-hant, zh.
        $parts = explode('-', $tag);

        while ($parts) {
            $candidate = implode('-', $parts);

            if (isset($special[$candidate])) {
                return $special[$candidate];
            }

            array_pop($parts);
        }

        $primary = explode('-', $tag)[0];

        return array_key_exists($primary, config('languages.supported')) ? $primary : null;
    }

    protected static function looksLikeBot(Request $request): bool
    {
        return (bool) preg_match('/bot|crawl|spider|slurp|lighthouse|facebookexternalhit|preview/i', (string) $request->userAgent());
    }
}
