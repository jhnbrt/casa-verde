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
        'image' => null,
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
            'image' => null,
            'icon' => 'lotus',
            'button_text' => 'Discover more',
            'button_url' => '/#contact',
        ],
        [
            'title' => 'Sunset Restore',
            'eyebrow' => '5:00 PM · 60 min · Gentle',
            'text' => 'Slow, restorative poses and breathwork to close the day in stillness.',
            'image' => null,
            'icon' => 'lotus',
            'button_text' => 'Discover more',
            'button_url' => '/#contact',
        ],
        [
            'title' => 'Private Sessions',
            'eyebrow' => 'By appointment · 60 min · Any level',
            'text' => 'One-to-one or small-group classes shaped around your pace and goals.',
            'image' => null,
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
        'image' => null,
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
        'image' => null,
        'buttons' => [
            ['text' => 'Book your stay', 'url' => '/book'],
            ['text' => 'Contact our team', 'url' => '/#contact'],
        ],
    ],

    // Shown in the "Good to know" section. Edited in the admin under Yoga Reset > Questions and answers.
    'faq' => [
        'eyebrow' => 'Good to know',
        'title' => 'Questions, answered.',
        'items' => [
            ['title' => 'Do I need yoga experience?', 'text' => 'No. Our sessions welcome every level, from first-timers to daily practitioners.'],
            ['title' => 'What should I bring?', 'text' => 'Mats and props are provided. Bring water and wear comfortable clothes you can move in.'],
            ['title' => 'How do I reserve a class?', 'text' => 'Use the booking button on this page or message our team, and we will confirm your session.'],
            ['title' => 'Where do the classes take place?', 'text' => 'On our open-air cliffside deck, with the sea in front of you.'],
        ],
    ],

    // The teachers section only appears once at least one teacher is added in the admin.
    'teachers' => [
        'eyebrow' => 'Your guides',
        'title' => 'Meet your teachers.',
    ],

];
