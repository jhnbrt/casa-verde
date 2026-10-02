<?php

/*
|--------------------------------------------------------------------------
| Villa detail pages
|--------------------------------------------------------------------------
| Rich content for /villas/{slug}. The slug is the villa title from the
| `villas` section of home_contents, slugified (e.g. "Standard Villa" =>
| "standard-villa"). Title, tagline, price, and card image still come from
| the database; everything below only fills the detail page.
|
| To swap in better photos, drop them in public/images/villas and change
| the paths here.
*/

$amenitiesShared = [
    'Private Terrace with Seating',
    'High-Speed Wi-Fi and Free Video Streaming',
    'Minibar & Refreshment Station',
    'Daily Housekeeping',
];

return [

    'standard-villa' => [
        // Icons for the feature rows on the home-page villa card (same order as the DB features)
        'card_icons' => ['bed', 'leaf', 'villa'],
        // Used on the home/villas cards only when the admin has no features saved for this villa
        'card_features' => [
            'Ideal for couples and longer stays',
            'Garden and terrace moments',
            'Everything you need to feel completely at home',
        ],
        'usd' => 115,
        'tagline' => 'Simple luxury. Genuine comfort.',
        'hero' => 'images/villas/standard-hero.jpg',
        'intro' => 'Thoughtfully designed for couples, solo travelers and longer stays, our Standard Villas combine tropical elegance with everything you need to feel completely at home.',

        'highlights' => [
            ['icon' => 'villa',   'label' => 'Private Villa'],
            ['icon' => 'bed',     'label' => 'King Bed'],
            ['icon' => 'bath',    'label' => 'Spacious Bathroom'],
            ['icon' => 'terrace', 'label' => 'Private Terrace'],
            ['icon' => 'wifi',    'label' => 'High-Speed Wi-Fi'],
        ],

        'story_title' => ['Designed for comfort.', 'Surrounded by nature.'],
        'story_text'  => [
            'Warm interiors, natural materials and thoughtful details create a calm space to unwind. Step onto your private terrace and take in the ocean breeze and lush tropical garden views.',
        ],
        'gallery' => [
            'images/villas/standard-1.jpg',
            'images/villas/standard-2.jpg',
            'images/villas/standard-3.jpg',
        ],

        'amenities' => [
            ['Air Conditioning', 'Coffee & Tea Station', 'King-Size Bed with Premium Linens', 'In-Villa Safe'],
            ['Spacious Bathroom', 'Hot & Cold Shower', 'Premium Bath Amenities', 'Hair Dryer'],
            $amenitiesShared,
        ],

        'layout' => [
            'plan'     => 'images/villas/standard-plan.jpg',
            'interior' => 28,
            'terrace'  => 10,
            'total'    => 38,
        ],
    ],

    'premium-villa' => [
        'card_icons' => ['sunset', 'pavilion', 'star'],
        'card_features' => [
            'More space and privacy',
            'Ocean views and cliffside atmosphere',
            'Made for unforgettable stays',
        ],
        'usd' => 170,
        'hero' => 'images/villas/premium-hero.jpg',
        'intro' => 'Discover the most exclusive accommodation at Casa Verde. With expansive living spaces, enhanced privacy and breathtaking ocean views, the Premium Villa is designed for guests seeking something truly extraordinary.',
        'tagline' => 'More space. More privacy. Ocean-view moments.',

        'highlights' => [
            ['icon' => 'villa',   'label' => 'Private Villa'],
            ['icon' => 'beds',    'label' => 'Queen Bed & Twin Beds'],
            ['icon' => 'jacuzzi', 'label' => 'Spacious Bathroom & Jacuzzi'],
            ['icon' => 'terrace', 'label' => 'Private Terrace'],
            ['icon' => 'wifi',    'label' => 'High-Speed Wi-Fi'],
        ],

        'story_title' => ['Designed for space.', 'Made for ocean-view living.'],
        'story_text'  => [
            'Natural materials, soft tones and thoughtful details create a warm and welcoming atmosphere.',
            'Every villa is built to offer you rest, privacy and a true sense of escape.',
        ],
        'gallery' => [
            'images/villas/premium-1.jpg',
            'images/villas/premium-2.jpg',
            'images/villas/premium-3.jpg',
        ],

        'amenities' => [
            ['Air Conditioning', 'Coffee & Tea Station', 'Two Bedrooms with Premium Linens', 'In-Villa Safe'],
            ['Spacious Bathroom & Jacuzzi', 'Hot & Cold Shower', 'Premium Bath Amenities', 'Hair Dryer'],
            $amenitiesShared,
        ],

        'layout' => [
            'plan'     => 'images/villas/premium-plan.jpg',
            'interior' => 56,
            'terrace'  => 18,
            'total'    => 74,
        ],
    ],

];
