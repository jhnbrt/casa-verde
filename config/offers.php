<?php

/*
|--------------------------------------------------------------------------
| Special Offers page defaults
|--------------------------------------------------------------------------
|
| Used when nothing has been entered in the admin (home_contents rows whose
| section starts with "offers"). The migration loads these into the admin so
| they can be edited there.
|
*/

return [

    'header' => [
        'eyebrow' => 'Special Offers',
        'title' => "Stay a little longer.\nExperience a little more.",
        'text' => 'Exclusive offers designed to help you relax deeper, explore more, and create unforgettable memories at Casa Verde Cliff Resort & Spa.',
    ],

    'items' => [
        [
            'title' => 'Stay 3, Pay 2',
            'icon' => '🏝️',
            'text' => 'The more nights you stay, the more you save. Unwind longer in paradise.',
            'highlight' => "1 NIGHT FREE\nwhen you stay 2 nights or more",
            'image' => 'images/experiences/sunset-rituals.jpg',
            'button_text' => 'View offer',
            'button_url' => '/#contact',
        ],
        [
            'title' => 'Wellness Escape',
            'icon' => '🪷',
            'text' => 'Restore your body and mind with a rejuvenating retreat designed for total well-being.',
            'highlight' => "15% OFF SPA\ntreatments and wellness experiences",
            'image' => 'images/wellness/package.jpg',
            'button_text' => 'View offer',
            'button_url' => '/#contact',
        ],
        [
            'title' => 'Romantic Getaway',
            'icon' => '♡',
            'text' => 'Celebrate love with special touches and unforgettable moments for two.',
            'highlight' => "ROMANTIC DINNER\nand amenities included",
            'image' => 'images/dining3.jpg',
            'button_text' => 'View offer',
            'button_url' => '/#contact',
        ],
    ],

    'note' => 'Book direct for our best available benefits.',

];
