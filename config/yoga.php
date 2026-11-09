<?php

/*
|--------------------------------------------------------------------------
| Yoga Reset page defaults
|--------------------------------------------------------------------------
|
| Placeholder content and images for the Yoga Reset page. Anything edited in
| the admin (home_contents rows whose section starts with "yoga_") overrides
| these values. The migration loads them into the admin so they can be edited
| there.
|
*/

return [

    'hero' => [
        'eyebrow' => 'Yoga Reset at Casa Verde',
        'title' => 'Breathe. Stretch. Reset.',
        'text' => 'Guided yoga on the cliff edge, with the sea below and the morning sky above.',
        'image' => 'images/experiences/hero.jpg',
        'button_text' => 'Book your reset',
        'button_url' => '/#contact',
    ],

    'stats' => [
        ['title' => '2', 'eyebrow' => 'Daily sessions', 'icon' => 'sunset'],
        ['title' => '60 min', 'eyebrow' => 'Per class', 'icon' => 'clock'],
        ['title' => 'All levels', 'eyebrow' => 'Welcome', 'icon' => 'lotus'],
        ['title' => 'Sea view', 'eyebrow' => 'Cliffside deck', 'icon' => 'cliff'],
    ],

    'intro' => [
        'eyebrow' => 'Slow down',
        'title' => 'A gentle reset for body and mind.',
        'text' => 'Join our sessions to release tension, restore energy and find calm, whether you are brand new to yoga or already have a daily practice.',
    ],

    'types' => [
        [
            'title' => 'Sunrise Flow',
            'eyebrow' => '6:00 AM · 60 min · All levels',
            'text' => 'Wake up softly with an energising flow as the sun rises over the water.',
            'image' => 'images/wellness4.jpg',
            'icon' => 'lotus',
            'button_text' => 'Discover more',
            'button_url' => '/#contact',
        ],
        [
            'title' => 'Sunset Restore',
            'eyebrow' => '5:00 PM · 60 min · Gentle',
            'text' => 'Slow, restorative poses and breathwork to close the day in stillness.',
            'image' => 'images/exp1.jpg',
            'icon' => 'lotus',
            'button_text' => 'Discover more',
            'button_url' => '/#contact',
        ],
        [
            'title' => 'Private Sessions',
            'eyebrow' => 'By appointment · 60 min · Any level',
            'text' => 'One-to-one or small-group classes shaped around your pace and goals.',
            'image' => 'images/dining3.jpg',
            'icon' => 'lotus',
            'button_text' => 'Discover more',
            'button_url' => '/#contact',
        ],
    ],

    'schedule' => [
        'eyebrow' => 'Your day',
        'title' => 'A daily rhythm to slow down to.',
        'items' => [
            ['title' => '6:00 AM', 'eyebrow' => 'Sunrise Flow', 'text' => 'Gentle movement and breathwork as the sky turns gold.'],
            ['title' => '8:00 AM', 'eyebrow' => 'Island Breakfast', 'text' => 'Fresh fruit and a slow breakfast with a view of the sea.'],
            ['title' => '11:00 AM', 'eyebrow' => 'Meditation & Stretch', 'text' => 'A short guided session to keep the calm going through the day.'],
            ['title' => '5:00 PM', 'eyebrow' => 'Sunset Restore', 'text' => 'Slow, supported poses to let go of the day as the light fades.'],
        ],
    ],

    'features' => [
        'title' => 'Everything you need to reset.',
        'items' => [
            ['title' => 'Cliffside Deck', 'text' => 'An open-air practice space with ocean views.', 'icon' => 'cliff'],
            ['title' => 'Expert Teachers', 'text' => 'Mindful guidance for every level.', 'icon' => 'lotus'],
            ['title' => 'Mats & Props', 'text' => 'Everything provided, just bring yourself.', 'icon' => 'clipboard'],
            ['title' => 'Island Calm', 'text' => 'Quiet surroundings made for deep rest.', 'icon' => 'island'],
        ],
    ],

    'personal' => [
        'eyebrow' => 'Your pace',
        'title' => 'Practice that fits your stay.',
        'text' => 'Drop in for a single class or build a full reset into your visit. Our team will help you choose the sessions that suit you best.',
        'image' => 'images/dining3.jpg',
        'button_text' => 'Start your reset',
        'button_url' => '/#contact',
    ],

    'quote' => [
        'text' => 'Waking up to yoga above the sea was the best part of our stay. I left rested in a way I had not felt in years.',
        'title' => 'Guest name',
        'eyebrow' => 'Guest from Germany',
    ],

    'cta' => [
        'title' => 'Ready for your Yoga Reset?',
        'image' => 'images/experiences/hero.jpg',
        'buttons' => [
            ['text' => 'Book your stay', 'url' => '/book'],
            ['text' => 'Contact our team', 'url' => '/#contact'],
        ],
    ],

];
