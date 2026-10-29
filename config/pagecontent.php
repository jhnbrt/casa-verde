<?php
/*
|--------------------------------------------------------------------------
| Page content defaults
|--------------------------------------------------------------------------
|
| Starting content for the Dining, Experiences, Wellness and Villas pages and a few
| labels on the Home page. Each key is an admin section; each item is one admin entry
| (same fields as the admin form). Anything saved in the admin overrides these.
| Run `php artisan db:seed --class=PageContentDefaultsSeeder` to load them into the
| admin so they can be edited there.
|
*/

return [

    'dining_page_hero' => [
        [
            'subtitle' => 'DINING',
            'title' => 'TASTE THE ISLAND.
Every meal becomes a memory.',
            'description' => 'Savor a curated dining experience inspired by fresh local ingredients, international flavors and breathtaking views. From sunrise breakfast to sunset cocktails, every moment is crafted to delight.',
            'image' => 'images/dining/dining-hero.jpg',
        ],
    ],

    'dining_page_features' => [
        [
            'title' => 'ASIAN & WESTERN CUISINE',
            'description' => 'Thoughtfully crafted dishes made with passion and precision.',
            'image' => 'images/dining/asian-western.jpg',
            'icon' => 'cloche',
        ],
        [
            'title' => 'SIGNATURE COCKTAILS',
            'description' => 'Creative blends and tropical favorites to complement every moment.',
            'image' => 'images/dining/signature-cocktails.jpg',
            'icon' => 'cocktail',
        ],
        [
            'title' => 'FRESH SEAFOOD',
            'description' => 'Daily catch and freshest seafood, prepared to perfection.',
            'image' => 'images/dining/fresh-seafood.jpg',
            'icon' => 'fish',
        ],
        [
            'title' => 'SUNSET DINNERS',
            'description' => 'Unforgettable sunsets paired with exquisite dining experiences.',
            'image' => 'images/dining/sunset-dinners.jpg',
            'icon' => 'sunset',
        ],
        [
            'title' => 'BREAKFAST',
            'description' => 'Wholesome and delicious start to your day, every day.',
            'image' => 'images/dining/breakfast.jpg',
            'icon' => 'coffee',
        ],
    ],

    'dining_page_info' => [
        [
            'title' => 'EXCEPTIONAL CUISINE',
            'description' => 'A culinary journey that celebrates local flavors and global inspiration.',
            'icon' => 'cutlery',
        ],
        [
            'title' => 'PERFECT PAIRINGS',
            'description' => 'From fine wines to handcrafted cocktails, find your perfect match.',
            'icon' => 'wine',
        ],
    ],

    'dining_page_menus' => [
        [
            'button_text' => 'EXPLORE FOOD MENU',
            'button_url' => '#',
        ],
        [
            'button_text' => 'EXPLORE DRINKS MENU',
            'button_url' => '#',
        ],
    ],

    'experiences_page_hero' => [
        [
            'subtitle' => 'EXPERIENCES',
            'title' => 'Moments that make
your stay unforgettable.',
            'description' => 'Every day at Casa Verde invites you to slow down, explore and connect with the island',
            'image' => 'images/experiences/hero.jpg',
        ],
    ],

    'experiences_page_signature_header' => [
        [
            'title' => 'SIGNATURE EXPERIENCES',
        ],
    ],

    'experiences_page_signature' => [
        [
            'title' => 'Cliffside Jacuzzi',
            'image' => 'images/experiences/cliffside-jacuzzi.jpg',
        ],
        [
            'title' => 'Hidden Cave',
            'image' => 'images/experiences/hidden-cave.jpg',
        ],
        [
            'title' => 'Infinity Pool',
            'image' => 'images/experiences/infinity-pool.jpg',
        ],
    ],

    'experiences_page_more_header' => [
        [
            'title' => 'MORE EXPERIENCES',
        ],
    ],

    'experiences_page_more' => [
        [
            'title' => 'Padel & Pickleball',
            'description' => 'Play with a sea view.',
            'image' => 'images/experiences/padel.jpg',
        ],
        [
            'title' => 'Billiards Lounge',
            'description' => 'Relax and unwind.',
            'image' => 'images/experiences/billiards.jpg',
        ],
        [
            'title' => 'Island Hopping',
            'description' => 'Discover nearby islands.',
            'image' => 'images/experiences/island-hopping.jpg',
        ],
        [
            'title' => 'Snorkeling',
            'description' => 'Explore vibrant reefs.',
            'image' => 'images/experiences/snorkeling.jpg',
        ],
        [
            'title' => 'Sunset Rituals',
            'description' => 'End the day mindfully.',
            'image' => 'images/experiences/sunset-rituals.jpg',
        ],
        [
            'title' => 'Open-Air Fitness',
            'description' => 'Move with nature.',
            'image' => 'images/experiences/open-air-fitness.jpg',
        ],
        [
            'title' => 'Yoga Sessions',
            'description' => 'Balance body and mind.',
            'image' => 'images/experiences/yoga.jpg',
        ],
    ],

    'experiences_page_concierge' => [
        [
            'title' => 'Concierge Planning',
            'description' => 'Personalized experiences
crafted just for you.',
            'icon' => 'cloche',
        ],
        [
            'title' => 'WhatsApp Concierge',
            'description' => 'Chat with us anytime for instant
assistance.',
            'icon' => 'whatsapp',
        ],
        [
            'title' => 'Activities by Reservation',
            'description' => 'Thoughtfully planned for a seamless
experience.',
            'icon' => 'calendar',
        ],
    ],

    'experiences_page_cta' => [
        [
            'button_text' => 'PLAN YOUR EXPERIENCES',
            'button_url' => '/#contact',
        ],
    ],

    'wellness_page_hero' => [
        [
            'subtitle' => 'WELLNESS',
            'title' => 'Relax. Rejuvenate.
Reconnect with yourself.',
            'description' => 'At Casa Verde Cliff Resort & Spa, wellness is more than a treatment - it\'s a way of life. Let our spa experiences restore your body, calm your mind and uplift your spirit.',
            'image' => 'images/wellness/hero.jpg',
        ],
    ],

    'wellness_page_treatments' => [
        [
            'title' => 'Banana Scanning Signature Massage',
            'description' => 'Our signature blend of massage designed to release tension and promote deep relaxation.',
            'image' => 'images/wellness/banana.jpg',
        ],
        [
            'title' => 'Hot Stone Massage',
            'description' => 'Warm volcanic stones melt away stress and improve circulation and balance.',
            'image' => 'images/wellness/hot-stone.jpg',
        ],
        [
            'title' => 'Therapeutic Massage',
            'description' => 'Essential oils combined with soothing touch to calm your mind and restore harmony.',
            'image' => 'images/wellness/therapeutic.jpg',
        ],
        [
            'title' => 'Foot Massage',
            'description' => 'Relieve tired feet and improve circulation with a relaxing reflexology massage.',
            'image' => 'images/wellness/foot.jpg',
        ],
        [
            'title' => 'Wellness Package',
            'description' => 'Choose from carefully curated package for the ultimate wellness journey.',
            'image' => 'images/wellness/package.jpg',
        ],
    ],

    'wellness_page_perks' => [
        [
            'title' => 'Peaceful Sanctuary',
            'description' => 'A serene spa environment
designed for total relaxation.',
            'icon' => 'lotus',
        ],
        [
            'title' => 'Natural Ingredients',
            'description' => 'We use high-quality, natural
products for your well-being.',
            'icon' => 'leaf',
        ],
        [
            'title' => 'Advance Booking',
            'description' => 'We recommend booking your
spa treatment in advance.',
            'icon' => 'calendar',
        ],
        [
            'title' => 'Your Well-being Matters',
            'description' => 'Our therapists are dedicated
to your comfort and care.',
            'icon' => 'shield',
        ],
    ],

    'wellness_page_menu' => [
        [
            'button_text' => 'EXPLORE SPA MENU',
            'button_url' => '#',
        ],
    ],

    'villas_page_style' => [
        [
            'icon' => '✧',
            'subtitle' => '♧',
            'title' => 'From PHP',
            'description' => '/ night',
            'button_text' => 'VIEW VILLA',
        ],
    ],

    'villas_page_bottom' => [
        [
            'icon' => '⚑',
            'title' => 'Not sure which villa is right for you?',
            'description' => 'We\'re happy to help you choose the perfect stay.',
        ],
        [
            'icon' => '♧',
            'description' => 'Comfortable spaces designed for relaxed island living.',
        ],
        [
            'icon' => '✧',
            'description' => 'Enjoy a peaceful private stay surrounded by nature.',
        ],
    ],

    'villas_page_book' => [
        [
            'icon' => '✧',
            'button_text' => 'BOOK YOUR STAY',
            'button_url' => '/#contact',
        ],
    ],

    'villas_detail_labels' => [
        [
            'title' => 'Our Villas',
        ],
        [
            'title' => 'Check availability',
        ],
        [
            'title' => 'Villa Amenities',
        ],
        [
            'title' => 'Villa Layout',
        ],
        [
            'title' => 'Interior Area',
        ],
        [
            'title' => 'Terrace Area',
        ],
        [
            'title' => 'Total Area',
        ],
        [
            'title' => 'From',
        ],
        [
            'title' => 'per night',
        ],
        [
            'title' => 'approx. USD',
        ],
    ],

    'home_labels' => [
        [
            'title' => 'EXPLORE THE RESORT',
            'icon' => '✧',
        ],
        [
            'title' => 'GET IN TOUCH',
        ],
        [
            'title' => 'Designed for the way you want to live.',
        ],
        [
            'title' => 'Hero button symbol',
            'icon' => '✧',
        ],
        [
            'title' => 'Video play symbol',
            'icon' => '▶',
        ],
        [
            'title' => 'Experiences divider symbol',
            'icon' => 'images/icons/ico-lotus.png',
        ],
        [
            'title' => 'Wellness button symbol',
            'icon' => '✧',
        ],
        [
            'title' => 'Dining divider symbol',
            'icon' => '♧',
        ],
        [
            'title' => 'Long stay booking symbol',
            'icon' => '◈',
        ],
        [
            'title' => 'Wellness quote ornament',
            'icon' => 'images/icons/placeholders/lotus.svg',
        ],
        [
            'title' => 'Map marker',
            'icon' => 'images/icons/placeholders/pin.svg',
        ],
    ],

    'site_icons' => [
        [
            'title' => 'Arrow',
            'icon' => 'images/icons/placeholders/arrow.svg',
        ],
        [
            'title' => 'Leaf',
            'icon' => 'images/icons/placeholders/leaf.svg',
        ],
        [
            'title' => 'Map pin',
            'icon' => 'images/icons/placeholders/pin.svg',
        ],
        [
            'title' => 'Lotus',
            'icon' => 'images/icons/placeholders/lotus.svg',
        ],
        [
            'title' => 'Villa card lotus',
            'icon' => 'images/icons/ico-lotus.png',
        ],
    ],

    'villas_highlights' => [
    ],


    // ---- Booking page (/book) -------------------------------------------------
    // Wording, one entry per label, in this order (see the admin help text).
    'booking_labels' => [
        ['title' => 'Choose a villa'],
        ['title' => 'Pick dates & guests'],
        ['title' => 'We confirm by email'],
        ['title' => 'Reserve this villa'],
        ['title' => 'Hide booking form'],
        ['title' => '/ night'],
        ['title' => 'Non-refundable rate · taxes and fees as stated in the terms'],
        ['title' => 'Promo dates:'],
        ['title' => 'Dates'],
        ['title' => 'Guests'],
        ['title' => 'Your details'],
        ['title' => 'Confirm booking request'],
        ['title' => 'Pay nothing now. We confirm availability by email first.'],
        ['title' => 'I agree to the Terms & Conditions'],
        ['title' => 'Terms & Conditions'],
        ['title' => 'Your stay'],
        ['title' => 'Estimated total'],
    ],

    'booking_confirmation' => [
        [
            'subtitle' => 'Request received',
            'title' => 'Thank you, :name',
            'description' => 'Your booking request has been sent to our team. It is not a confirmed reservation yet. We will check availability and email you at :email.',
        ],
    ],

    'booking_settings' => [
        [
            'title' => '',
            'subtitle' => '30',
            'price' => 10,
            'description' => 'Online booking is paused right now. Please contact us and our team will gladly arrange your stay.',
        ],
    ],
];
