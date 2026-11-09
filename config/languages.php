<?php

/*
|--------------------------------------------------------------------------
| Website languages
|--------------------------------------------------------------------------
| The site is written in English. Visitors can switch to another language with the
| menu in the header; the page is then translated automatically (Google Website
| Translator), including everything edited in the admin.
|
| Which of these languages are offered, whether the language is picked automatically
| from the visitor's location, and whether the menu shows at all is set in the admin
| (Languages page). The lists below are only the starting values and the choices.
*/

return [

    'default' => 'en',

    // code (the Google Translate code) => names. "short" is what the menu button shows.
    'supported' => [
        'en'    => ['name' => 'English',               'native' => 'English',          'short' => 'EN'],
        'tl'    => ['name' => 'Filipino',              'native' => 'Filipino',         'short' => 'FIL'],
        'ceb'   => ['name' => 'Cebuano',               'native' => 'Cebuano',          'short' => 'CEB'],
        'ko'    => ['name' => 'Korean',                'native' => '한국어',             'short' => 'KO'],
        'zh-CN' => ['name' => 'Chinese (Simplified)',  'native' => '简体中文',            'short' => '中文'],
        'zh-TW' => ['name' => 'Chinese (Traditional)', 'native' => '繁體中文',            'short' => '繁中'],
        'ja'    => ['name' => 'Japanese',              'native' => '日本語',             'short' => 'JA'],
        'ru'    => ['name' => 'Russian',               'native' => 'Русский',          'short' => 'RU'],
        'de'    => ['name' => 'German',                'native' => 'Deutsch',          'short' => 'DE'],
        'fr'    => ['name' => 'French',                'native' => 'Français',         'short' => 'FR'],
        'es'    => ['name' => 'Spanish',               'native' => 'Español',          'short' => 'ES'],
        'it'    => ['name' => 'Italian',               'native' => 'Italiano',         'short' => 'IT'],
        'pt'    => ['name' => 'Portuguese',            'native' => 'Português',        'short' => 'PT'],
        'nl'    => ['name' => 'Dutch',                 'native' => 'Nederlands',       'short' => 'NL'],
        'id'    => ['name' => 'Indonesian',            'native' => 'Bahasa Indonesia', 'short' => 'ID'],
        'th'    => ['name' => 'Thai',                  'native' => 'ไทย',              'short' => 'TH'],
        'vi'    => ['name' => 'Vietnamese',            'native' => 'Tiếng Việt',       'short' => 'VI'],
    ],

    // Offered until the admin chooses otherwise.
    'enabled' => ['en', 'tl', 'ko', 'zh-CN', 'zh-TW', 'ja', 'ru', 'de', 'fr', 'es'],

    // Visitor's country (ISO code) => language shown automatically. Countries not listed here
    // (the Philippines, the USA, the UK...) keep English, unless their browser asks for another language.
    'countries' => [
        'KR' => 'ko',
        'CN' => 'zh-CN',
        'TW' => 'zh-TW', 'HK' => 'zh-TW', 'MO' => 'zh-TW',
        'JP' => 'ja',
        'RU' => 'ru', 'BY' => 'ru',
        'DE' => 'de', 'AT' => 'de', 'CH' => 'de', 'LI' => 'de',
        'FR' => 'fr', 'MC' => 'fr',
        'ES' => 'es', 'MX' => 'es', 'AR' => 'es', 'CO' => 'es', 'CL' => 'es', 'PE' => 'es',
        'IT' => 'it',
        'PT' => 'pt', 'BR' => 'pt',
        'NL' => 'nl',
        'ID' => 'id',
        'TH' => 'th',
        'VN' => 'vi',
    ],

    // Free country lookup used when the host does not tell us the visitor's country
    // (Cloudflare and similar send it for free). %s is replaced by the visitor's IP address.
    'ip_lookup_url' => 'https://ipwho.is/%s?fields=success,country_code',
];
