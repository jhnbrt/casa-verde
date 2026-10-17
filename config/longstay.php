<?php

/*
|--------------------------------------------------------------------------
| Long Stay page defaults
|--------------------------------------------------------------------------
|
| Starting content for the Long Stay page. Anything edited in the admin
| (home_contents rows whose section starts with "long_stay_") overrides these.
| The admin loads these on its own the first time it is opened; to load them by
| hand run `php artisan db:seed --class=LongStayContentSeeder`.
|
| "icon" accepts an icon name or an uploaded picture. Built-in names:
| calendar, villa, wifi, lotus, hands, workation, meditation, island, family,
| couple, suitcase, laptop, book, breakfast, housekeeping.
|
*/

return [

    'hero' => [
        'eyebrow' => 'Long Stay',
        'title' => "Stay Longer.\nLive Deeper.",
        'text' => 'Whether you are working remotely, escaping the winter or beginning a slower chapter, Casa Verde gives you the space, comfort and rhythm to feel at home in paradise.',
        'tagline' => 'Your paradise. Your pace. Your life.',
        'image' => 'images/long-stay.jpg',
        'button_text' => 'Plan Your Long Stay',
        'button_url' => '/#contact',
    ],

    'strip' => [
        ['title' => 'Flexible Stays', 'text' => 'From 4 weeks up to 12 months', 'icon' => 'calendar'],
        ['title' => 'Feel at Home', 'text' => 'Comfortable villas with everything you need', 'icon' => 'villa'],
        ['title' => 'Work & Connect', 'text' => 'High-speed internet, quiet spaces, total focus', 'icon' => 'wifi'],
        ['title' => 'Wellness & Balance', 'text' => 'Wellness programs, healthy food, and island living', 'icon' => 'lotus'],
        ['title' => 'Warm Hospitality', 'text' => 'Personal service and a community that cares', 'icon' => 'hands'],
    ],

    'uses_header' => [
        'eyebrow' => 'Designed for the way you want to live.',
    ],

    'uses' => [
        ['title' => 'Workation', 'text' => 'Work from paradise', 'icon' => 'workation'],
        ['title' => 'Wellness Retreat', 'text' => 'Rest, reset and recharge', 'icon' => 'meditation'],
        ['title' => 'Winter Escape', 'text' => 'Sunshine when you need it most', 'icon' => 'island'],
        ['title' => 'Family Long Stay', 'text' => 'Quality time in a safe and relaxing place', 'icon' => 'couple'],
        ['title' => 'Extended Island Living', 'text' => 'Enjoy your golden years in comfort', 'icon' => 'family'],
        ['title' => 'Sabbatical', 'text' => 'Take a break. Explore life.', 'icon' => 'suitcase'],
    ],

    'intro' => [
        'eyebrow' => 'Long stay living',
        'title' => 'Everything you need to feel at home.',
        'text' => 'Designed for guests who want more than a holiday: space, comfort, connection and the rhythm of island life.',
        'icon' => null,
    ],

    // Boxes: the title and style are the box; its list items (with icons) follow below.
    'panels' => [
        [
            'title' => 'Who it is for',
            'numbered' => false,
            'items' => [
                ['text' => 'Workation', 'icon' => 'laptop'],
                ['text' => 'Winter Escape', 'icon' => 'island'],
                ['text' => 'Sabbatical', 'icon' => 'book'],
                ['text' => 'Family Time', 'icon' => 'family'],
            ],
        ],
        [
            'title' => 'What is included',
            'numbered' => false,
            'items' => [
                ['text' => 'Private Villa', 'icon' => 'villa'],
                ['text' => 'Breakfast', 'icon' => 'breakfast'],
                ['text' => 'High-Speed Wi-Fi', 'icon' => 'wifi'],
                ['text' => 'Pool & Wellness', 'icon' => 'lotus'],
                ['text' => 'Housekeeping', 'icon' => 'housekeeping'],
            ],
        ],
        [
            'title' => 'How it works',
            'numbered' => true,
            'items' => [
                ['text' => 'Share your preferred dates', 'icon' => null],
                ['text' => 'Choose your villa', 'icon' => null],
                ['text' => 'Settle into island life', 'icon' => null],
            ],
        ],
    ],

    // Photos beside the boxes: 1 = tall photo in the ornate frame, 2 = wide photo underneath.
    'gallery' => [
        ['image' => 'images/main.jpg', 'alt' => 'Working with a view of the sea'],
        ['image' => 'images/wellness1.jpg', 'alt' => 'Relaxing by the pool'],
    ],

    'bar' => [
        'text' => 'Stay for a month. Stay for a season.',
        'text_second' => 'Stay as long as you need.',
        'button_text' => 'Talk to our long stay concierge',
        'button_url' => '/#contact',
    ],

];
