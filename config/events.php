<?php

/*
|--------------------------------------------------------------------------
| Events page defaults
|--------------------------------------------------------------------------
|
| Mock-up content and placeholder images for the Events page. Anything
| edited in the admin (home_contents rows whose section starts with
| "events_") overrides these values. Run
| `php artisan db:seed --class=EventContentSeeder` to load them into the
| admin so they can be edited there.
|
*/

return [

    'hero' => [
        'eyebrow' => 'Events at Casa Verde',
        'title' => 'Gather. Celebrate. Remember.',
        'text' => 'Meaningful occasions, framed by sea, sky and the warmth of island hospitality.',
        'image' => 'images/about-hero.jpg',
        'button_text' => 'Plan your event',
        'button_url' => '/#contact',
    ],

    'intro' => [
        'eyebrow' => 'Your moment, your way',
        'title' => 'Extraordinary settings for unforgettable gatherings.',
        'text' => 'Exclusive offers designed to help you relax deeper, explore more, and create unforgettable memories at Casa Verde Cliff Resort & Spa.',
    ],

    'types' => [
        [
            'title' => 'Intimate Weddings',
            'text' => 'Romantic and intimate ceremonies with breathtaking ocean views.',
            'image' => 'images/exp1.jpg',
            'icon' => 'rings',
            'button_text' => 'Discover more',
            'button_url' => '/#contact',
        ],
        [
            'title' => 'Private Celebrations',
            'text' => 'Birthdays, anniversaries and life’s special moments, made memorable.',
            'image' => 'images/dining2.jpg',
            'icon' => 'cake',
            'button_text' => 'Discover more',
            'button_url' => '/#contact',
        ],
        [
            'title' => 'Retreats & Gatherings',
            'text' => 'Inspiring spaces for wellness retreats. Team offsites and meaningful getaways.',
            'image' => 'images/wellness4.jpg',
            'icon' => 'lotus',
            'button_text' => 'Discover more',
            'button_url' => '/#contact',
        ],
    ],

    'features' => [
        'title' => 'Everything, beautifully considered.',
        'items' => [
            ['title' => 'Cliffside Venues', 'text' => 'Spectacular settings with unrivalled views.', 'icon' => 'cliff'],
            ['title' => 'Thoughtful Menus', 'text' => 'Seasonal, locally inspired culinary experiences.', 'icon' => 'cloche'],
            ['title' => 'Personal Planning', 'text' => 'Dedicated support to bring your vision to life.', 'icon' => 'clipboard'],
            ['title' => 'Island Hospitality', 'text' => 'Warm, intuitive service rooted in care.', 'icon' => 'island'],
        ],
    ],

    'personal' => [
        'eyebrow' => 'Made personal',
        'title' => 'From first idea to final toast.',
        'text' => 'Our events team is here to listen, guide and craft every detail seamlessly and with heart, so you can be fully present in the moments that matter.',
        'image' => 'images/dining3.jpg',
        'button_text' => 'Start planning',
        'button_url' => '/#contact',
    ],

    'cta' => [
        'title' => 'Let’s create something unforgettable.',
        'image' => 'images/experiences/hero.jpg',
        'buttons' => [
            ['text' => 'Send an enquiry', 'url' => '/#contact'],
            ['text' => 'Contact our team', 'url' => '/#contact'],
        ],
    ],

];
