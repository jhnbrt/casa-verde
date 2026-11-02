<?php

namespace App\Support;

use App\Models\HomeContent;

/**
 * The Google Map shown on the Contact page and under "Find us" on the Home page.
 * Both read the same admin entry (Site content > Contact page Google Map), so one edit updates both.
 * Only Google Maps addresses are accepted, so an admin entry can never embed another website.
 */
class MapEmbed
{
    private const PLACE = 'Casa Verde Cliff Resort and Spa, Mankahilo, Consuelo, Camotes, Cebu';

    /**
     * @return array{embed:string, link:string, address:string}
     */
    public static function from(?HomeContent $map): array
    {
        // Satellite view (t=k) of the resort: Google adds its own pin, address card and "Open in Maps" button.
        $default = 'https://www.google.com/maps?q='.rawurlencode(self::PLACE).'&t=k&z=17&output=embed';

        $candidate = trim((string) ($map?->description ?? ''));
        $host = parse_url($candidate, PHP_URL_HOST);
        $valid = is_string($host)
            && in_array(strtolower($host), ['www.google.com', 'google.com', 'maps.google.com'], true)
            && str_starts_with((string) parse_url($candidate, PHP_URL_PATH), '/maps')
            && str_starts_with($candidate, 'https://');

        $link = trim((string) ($map?->button_url ?? ''));
        $linkHost = parse_url($link, PHP_URL_HOST);
        $linkValid = str_starts_with($link, 'https://')
            && is_string($linkHost)
            && in_array(strtolower($linkHost), ['google.com', 'www.google.com', 'maps.google.com', 'maps.app.goo.gl'], true);

        return [
            'embed' => $valid ? $candidate : $default,
            'link' => $linkValid ? $link : 'https://www.google.com/maps/search/?api=1&query='.rawurlencode(self::PLACE),
            'address' => trim((string) ($map?->subtitle ?? '')) ?: 'Mankahilo, Consuelo, Camotes Islands, Cebu 6050, Philippines',
        ];
    }
}
