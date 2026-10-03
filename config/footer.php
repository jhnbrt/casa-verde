<?php

/*
|--------------------------------------------------------------------------
| Footer defaults
|--------------------------------------------------------------------------
|
| Used when nothing has been entered in the admin (home_contents rows whose
| section starts with "footer"). The migration loads these into the admin so
| they can be edited there.
|
*/

return [

    'cta' => [
        'title' => 'Ready to experience Casa Verde?',
        'button_text' => 'Book your stay',
        'button_url' => '/#contact',
    ],

    'brand' => [
        'title' => 'Casa Verde Cliff Resort & Spa',
        'subtitle' => 'Camotes Island, Philippines',
    ],

    'explore' => [
        ['title' => 'Villas', 'url' => '/villas'],
        ['title' => 'Experiences', 'url' => '/experiences'],
        ['title' => 'Wellness', 'url' => '/wellness'],
        ['title' => 'Dining', 'url' => '/dining'],
        ['title' => 'Long Stay', 'url' => '/long-stay'],
    ],

    'useful' => [
        ['title' => 'Gallery', 'url' => '/gallery'],
        ['title' => 'Special Offers', 'url' => '/special-offers'],
        ['title' => 'Events', 'url' => '/events'],
        ['title' => 'Contact', 'url' => '/#contact'],
    ],

    'contact' => [
        ['icon' => 'pin', 'title' => "Sitio Mankahilo, Consuelo,\nSan Francisco, Philippines", 'url' => ''],
        ['icon' => 'whatsapp', 'title' => '+63-962-255-8380', 'url' => 'https://wa.me/639622558380'],
        ['icon' => 'mail', 'title' => 'bookings@casaverdecliffresort.com', 'url' => 'mailto:bookings@casaverdecliffresort.com'],
    ],

    'social' => [
        ['icon' => 'instagram', 'title' => 'Instagram', 'url' => '#'],
        ['icon' => 'facebook', 'title' => 'Facebook', 'url' => '#'],
        ['icon' => 'tiktok', 'title' => 'TikTok', 'url' => '#'],
    ],

    'newsletter' => [
        'title' => 'Be the first to know',
        'text' => 'Sign up for updates on special offers, events and resort news.',
    ],

    'legal' => [
        ['title' => 'Privacy Policy', 'url' => '#'],
        ['title' => 'Terms & Conditions', 'url' => '#'],
    ],

    'bottom' => 'Casa Verde Cliff Resort & Spa. All rights reserved.',

];
