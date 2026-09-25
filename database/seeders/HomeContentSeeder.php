<?php

namespace Database\Seeders;

use App\Models\HomeContent;
use Illuminate\Database\Seeder;

class HomeContentSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | CLEAR OLD CONTENT
        |--------------------------------------------------------------------------
        */

        HomeContent::truncate();


        /*
        |--------------------------------------------------------------------------
        | SITE INFORMATION
        |--------------------------------------------------------------------------
        */

        HomeContent::create([
            'section' => 'site',
            'title' => 'CASA VERDE CLIFF RESORT & SPA',
            'subtitle' => 'CAMOTES ISLAND, PHILIPPINES',
            'image' => 'images/logo.png',
            'sort_order' => 1,
        ]);


        /*
        |--------------------------------------------------------------------------
        | NAVIGATION
        |--------------------------------------------------------------------------
        */

        $navigation = [
            ['VILLAS', '#villas'],
            ['EXPERIENCES', '#experiences'],
            ['WELLNESS', '#wellness'],
            ['DINING', '#dining'],
            ['LONG STAY', '#longstay'],
            ['ABOUT', '#about'],
            ['CONTACT', '#contact'],
        ];

        foreach ($navigation as $index => $nav) {

            HomeContent::create([
                'section' => 'navigation',
                'title' => $nav[0],
                'button_url' => $nav[1],
                'sort_order' => $index + 1,
            ]);

        }


        /*
        |--------------------------------------------------------------------------
        | HERO
        |--------------------------------------------------------------------------
        */

        HomeContent::create([
            'section' => 'hero',

            'title' => 'More than a resort.',

            'subtitle' => 'A cliffside sanctuary on Camotes Island',

            'description' =>
                'Perched above the sea, Casa Verde is a place to slow down, reconnect, and discover a life surrounded by beauty, comfort and genuine Filipino hospitality.',

            'image' => 'images/main.jpg',

            'button_text' => 'BOOK YOUR STAY',

            'button_url' => '#booking',

            'sort_order' => 1,
        ]);


        /*
        |--------------------------------------------------------------------------
        | HERO FEATURES
        |--------------------------------------------------------------------------
        */

        HomeContent::create([
            'section' => 'features',
            'title' => '100m Cliff',
            'subtitle' => 'Front',
            'icon' => '◈',
            'sort_order' => 1,
        ]);

        HomeContent::create([
            'section' => 'features',
            'title' => 'Spectacular',
            'subtitle' => 'Sunsets',
            'icon' => '☼',
            'sort_order' => 2,
        ]);

        HomeContent::create([
            'section' => 'features',
            'title' => '10 Private',
            'subtitle' => 'Villas',
            'icon' => '♧',
            'sort_order' => 3,
        ]);

        HomeContent::create([
            'section' => 'features',
            'title' => 'Ocean, Pool',
            'subtitle' => '& Cave',
            'icon' => '≋',
            'sort_order' => 4,
        ]);

        HomeContent::create([
            'section' => 'features',
            'title' => 'Wellness &',
            'subtitle' => 'Spa',
            'icon' => '◌',
            'sort_order' => 5,
        ]);

        HomeContent::create([
            'section' => 'features',
            'title' => 'Restaurant',
            'subtitle' => '& Bar',
            'icon' => '⚒',
            'sort_order' => 6,
        ]);


        /*
        |--------------------------------------------------------------------------
        | VILLAS SECTION HEADER
        |--------------------------------------------------------------------------
        */

        HomeContent::create([
            'section' => 'villas_header',

            'subtitle' => 'OUR VILLAS',

            'title' => 'Choose your villa',

            'description' =>
                'Two distinctive villa styles. One unforgettable Casa Verde experience.',

            'sort_order' => 1,
        ]);


        /*
        |--------------------------------------------------------------------------
        | STANDARD VILLA
        |--------------------------------------------------------------------------
        */

        HomeContent::create([
            'section' => 'villas',

            'title' => 'Standard Villa',

            'subtitle' => 'Simple luxury. Genuine comfort.',

            'description' =>
                'Ideal for couples and longer stays.',

            'image' => 'images/standard.jpg',

            'price' => 6500,

            'features' => json_encode([
                'Ideal for couples and longer stays',
                'Garden and terrace moments',
                'Everything you need to feel completely at home',
            ]),

            'button_text' => 'View Standard Villa',

            'button_url' => '#',

            'sort_order' => 1,
        ]);


        /*
        |--------------------------------------------------------------------------
        | PREMIUM VILLA
        |--------------------------------------------------------------------------
        */

        HomeContent::create([
            'section' => 'villas',

            'title' => 'Premium Villa',

            'subtitle' => 'Your best seat in paradise.',

            'description' =>
                'More space and privacy.',

            'image' => 'images/premium.jpg',

            'price' => 9500,

            'features' => json_encode([
                'More space and privacy',
                'Ocean views and cliffside atmosphere',
                'Made for unforgettable stays',
            ]),

            'button_text' => 'View Premium Villa',

            'button_url' => '#',

            'sort_order' => 2,
        ]);


        /*
        |--------------------------------------------------------------------------
        | EXPERIENCES SECTION HEADER
        |--------------------------------------------------------------------------
        */

        HomeContent::create([
            'section' => 'experiences_header',

            'subtitle' => 'EXPERIENCES',

            'title' => 'Moments that stay with you.',

            'description' =>
                'From golden sunsets to hidden caves and island discoveries, every day at Casa Verde has its own rhythm.',

            'sort_order' => 1,
        ]);


        /*
        |--------------------------------------------------------------------------
        | EXPERIENCE 1
        |--------------------------------------------------------------------------
        */

        HomeContent::create([
            'section' => 'experiences',

            'title' => 'Spectacular Sunsets',

            'description' =>
                'Evenings unfold in shades of gold above the cliff.',

            'image' => 'images/exp1.jpg',

            'sort_order' => 1,
        ]);


        /*
        |--------------------------------------------------------------------------
        | EXPERIENCE 2
        |--------------------------------------------------------------------------
        */

        HomeContent::create([
            'section' => 'experiences',

            'title' => 'Ocean & Cave',

            'description' =>
                'Swim, explore and discover the island\'s natural beauty.',

            'image' => 'images/exp2.jpg',

            'sort_order' => 2,
        ]);


        /*
        |--------------------------------------------------------------------------
        | EXPERIENCE 3
        |--------------------------------------------------------------------------
        */

        HomeContent::create([
            'section' => 'experiences',

            'title' => 'Island Adventures',

            'description' =>
                'Discover Camotes through quiet escapes and local stories.',

            'image' => 'images/exp3.jpg',

            'sort_order' => 3,
        ]);


        /*
        |--------------------------------------------------------------------------
        | EXPERIENCES BOTTOM
        |--------------------------------------------------------------------------
        */

        HomeContent::create([
            'section' => 'experiences_bottom',

            'description' =>
                'Wellness, dining and active moments await throughout your stay.',

            'button_text' => 'Discover all experiences',

            'button_url' => '#',

            'sort_order' => 1,
        ]);


        /*
        |--------------------------------------------------------------------------
        | WELLNESS MAIN INTRO
        |--------------------------------------------------------------------------
        */

        HomeContent::create([
            'section' => 'wellness_intro',

            'subtitle' => 'WELLNESS & SPA',

            'title' => "Restore.\nRenew.\nRebalance.",

            'description' =>
                'Find calm in our serene spa sanctuary, where traditional therapies, gentle rituals and tropical stillness restore body and mind.',

            'image' => 'images/wellness1.jpg',

            'button_text' => 'Explore Our Spa',

            'button_url' => '#',

            'sort_order' => 1,
        ]);


        /*
        |--------------------------------------------------------------------------
        | WELLNESS CARD 1
        |--------------------------------------------------------------------------
        */

        HomeContent::create([
            'section' => 'wellness',

            'title' => 'Signature Massages',

            'description' =>
                'Expert touch to melt tension and restore deep balance.',

            'image' => 'images/wellness2.jpg',

            'icon' => '◈',

            'sort_order' => 1,
        ]);


        /*
        |--------------------------------------------------------------------------
        | WELLNESS CARD 2
        |--------------------------------------------------------------------------
        */

        HomeContent::create([
            'section' => 'wellness',

            'title' => 'Spa Rituals',

            'description' =>
                'Time-honoured rituals inspired by nature and crafted for deep renewal.',

            'image' => 'images/wellness3.jpg',

            'icon' => '♧',

            'sort_order' => 2,
        ]);


        /*
        |--------------------------------------------------------------------------
        | WELLNESS CARD 3
        |--------------------------------------------------------------------------
        */

        HomeContent::create([
            'section' => 'wellness',

            'title' => 'Mind & Body',

            'description' =>
                'Nourish your inner calm and reconnect in tropical stillness.',

            'image' => 'images/wellness4.jpg',

            'icon' => '☼',

            'sort_order' => 3,
        ]);


        /*
        |--------------------------------------------------------------------------
        | WELLNESS QUOTE
        |--------------------------------------------------------------------------
        */

        HomeContent::create([
            'section' => 'wellness_quote',

            'description' =>
                'Wellness is not a luxury. It is a way of life.',

            'sort_order' => 1,
        ]);


        /*
        |--------------------------------------------------------------------------
        | DINING MAIN INTRO
        |--------------------------------------------------------------------------
        */

        HomeContent::create([
            'section' => 'dining_intro',

            'subtitle' => 'DINING',

            'title' => "More than a meal.\nA moment to remember.",

            'description' =>
                'Tropical flavors, handcrafted cocktails and ocean-view dinners come together in a setting made for slow evenings.',

            'image' => 'images/dining3.jpg',

            'button_text' => 'Explore Our Restaurant',

            'button_url' => '#',

            'sort_order' => 1,
        ]);


        /*
        |--------------------------------------------------------------------------
        | DINING CARD 1
        |--------------------------------------------------------------------------
        */

        HomeContent::create([
            'section' => 'dining',

            'title' => 'Private Dining',

            'description' =>
                'Locally inspired cuisine, artfully prepared.',

            'image' => 'images/dining2.jpg',

            'sort_order' => 1,
        ]);


        /*
        |--------------------------------------------------------------------------
        | DINING CARD 2
        |--------------------------------------------------------------------------
        */

        HomeContent::create([
            'section' => 'dining',

            'title' => 'Sunset Dinner',

            'description' =>
                'Unforgettable views. Unhurried moments.',

            'image' => 'images/dining1.jpg',

            'sort_order' => 2,
        ]);


        /*
        |--------------------------------------------------------------------------
        | DINING CARD 3
        |--------------------------------------------------------------------------
        */

        HomeContent::create([
            'section' => 'dining',

            'title' => 'Cocktails',

            'description' =>
                'Handcrafted drinks, perfectly mixed.',

            'image' => 'images/dining4.jpg',

            'sort_order' => 3,
        ]);


        /*
        |--------------------------------------------------------------------------
        | DINING BOTTOM
        |--------------------------------------------------------------------------
        */

        HomeContent::create([
            'section' => 'dining_bottom',

            'description' =>
                'Breakfast is served with fresh coffee, tropical fruit and sea views.',

            'sort_order' => 1,
        ]);

       /*
   /*
|--------------------------------------------------------------------------
| LONG STAY MAIN
|--------------------------------------------------------------------------
*/

HomeContent::create([
    'section' => 'long_stay',

    'subtitle' => 'LONG STAY',

    'title' => "Stay longer.\nLive deeper.",

    'description' =>
        'Settle into island life with flexible stays, quiet villas, reliable connection and the rhythm of Camotes Island at your door.',

    'image' => 'images/long-stay.jpg',

    'button_text' => 'Explore Long Stays',

    'button_url' => '#longstay',

    'sort_order' => 1,
]);


/*
|--------------------------------------------------------------------------
| LONG STAY BOOKING BUTTON
|--------------------------------------------------------------------------
*/

HomeContent::create([
    'section' => 'long_stay_booking',

    'button_text' => 'BOOK YOUR STAY',

    'button_url' => '#booking',

    'sort_order' => 1,
]);


/*
|--------------------------------------------------------------------------
| LONG STAY FEATURE 1
|--------------------------------------------------------------------------
*/

HomeContent::create([
    'section' => 'long_stay_features',

    'title' => 'Flexible Stays',

    'icon' => '▦',

    'sort_order' => 1,
]);


/*
|--------------------------------------------------------------------------
| LONG STAY FEATURE 2
|--------------------------------------------------------------------------
*/

HomeContent::create([
    'section' => 'long_stay_features',

    'title' => 'Feel at Home',

    'icon' => '⌂',

    'sort_order' => 2,
]);


/*
|--------------------------------------------------------------------------
| LONG STAY FEATURE 3
|--------------------------------------------------------------------------
*/

HomeContent::create([
    'section' => 'long_stay_features',

    'title' => 'Work & Connect',

    'icon' => '⌁',

    'sort_order' => 3,
]);


/*
|--------------------------------------------------------------------------
| LONG STAY FEATURE 4
|--------------------------------------------------------------------------
*/

HomeContent::create([
    'section' => 'long_stay_features',

    'title' => 'Wellness & Balance',

    'icon' => '❧',

    'sort_order' => 4,
]);


/*
|--------------------------------------------------------------------------
| LONG STAY OPTIONS HEADER
|--------------------------------------------------------------------------
*/

HomeContent::create([
    'section' => 'long_stay_options_header',

    'title' => 'Designed for the way you want to live.',

    'sort_order' => 1,
]);


/*
|--------------------------------------------------------------------------
| LONG STAY OPTION 1
|--------------------------------------------------------------------------
*/

HomeContent::create([
    'section' => 'long_stay_options',

    'title' => 'Workation',

    'icon' => '▱',

    'sort_order' => 1,
]);


/*
|--------------------------------------------------------------------------
| LONG STAY OPTION 2
|--------------------------------------------------------------------------
*/

HomeContent::create([
    'section' => 'long_stay_options',

    'title' => 'Wellness Retreat',

    'icon' => '♨',

    'sort_order' => 2,
]);


/*
|--------------------------------------------------------------------------
| LONG STAY OPTION 3
|--------------------------------------------------------------------------
*/

HomeContent::create([
    'section' => 'long_stay_options',

    'title' => 'Winter Escape',

    'icon' => '☼',

    'sort_order' => 3,
]);


/*
|--------------------------------------------------------------------------
| LONG STAY OPTION 4
|--------------------------------------------------------------------------
*/

HomeContent::create([
    'section' => 'long_stay_options',

    'title' => 'Family Long Stay',

    'icon' => '♙',

    'sort_order' => 4,
]);


/*
|--------------------------------------------------------------------------
| LONG STAY OPTION 5
|--------------------------------------------------------------------------
*/

HomeContent::create([
    'section' => 'long_stay_options',
    'title' => 'Sabbatical',
    'icon' => '▤',
    'sort_order' => 5,
]);
/*
|--------------------------------------------------------------------------
| ABOUT CASA VERDE - HERO
|--------------------------------------------------------------------------
*/

HomeContent::create([
    'section' => 'about_hero',

    'subtitle' => 'ABOUT CASA VERDE',

    'title' => "Our story.\nOur passion.",

    'description' =>
        'Created from a love for beauty, nature and heartfelt hospitality, Casa Verde is a private sanctuary shaped by sunsets, stillness and genuine Filipino warmth.',

    'image' => 'images/about-hero.jpg',

    'sort_order' => 1,
]);


/*
|--------------------------------------------------------------------------
| ABOUT CASA VERDE - RESORT
|--------------------------------------------------------------------------
*/

HomeContent::create([
    'section' => 'about_resort',

    'subtitle' => 'OUR RESORT',

    'title' => 'A private sanctuary in paradise',

    'description' =>
        'Boutique villas, ocean cliffs and tropical gardens come together in a place designed for slow days and meaningful stays.',

    'image' => 'images/about-resort.jpg',

    'sort_order' => 1,
]);


/*
|--------------------------------------------------------------------------
| ABOUT CASA VERDE - VALUES
|--------------------------------------------------------------------------
*/

HomeContent::create([
    'section' => 'about_values',

    'title' => 'Boutique Luxury',

    'icon' => '⌂',

    'sort_order' => 1,
]);


HomeContent::create([
    'section' => 'about_values',

    'title' => 'Nature First',

    'icon' => '♧',

    'sort_order' => 2,
]);


HomeContent::create([
    'section' => 'about_values',

    'title' => 'Heartfelt Hospitality',

    'icon' => '♡',

    'sort_order' => 3,
]);


HomeContent::create([
    'section' => 'about_values',

    'title' => 'Legendary Sunsets',

    'icon' => '☼',

    'sort_order' => 4,
]);


/*
|--------------------------------------------------------------------------
| ABOUT CASA VERDE - PRINCIPLES
|--------------------------------------------------------------------------
*/

HomeContent::create([
    'section' => 'about_principles',

    'title' => 'Authenticity',

    'icon' => '♙',

    'sort_order' => 1,
]);


HomeContent::create([
    'section' => 'about_principles',

    'title' => 'Excellence',

    'icon' => '◇',

    'sort_order' => 2,
]);


HomeContent::create([
    'section' => 'about_principles',

    'title' => 'Respect',

    'icon' => '♧',

    'sort_order' => 3,
]);


HomeContent::create([
    'section' => 'about_principles',

    'title' => 'Connection',

    'icon' => '♧',

    'sort_order' => 4,
]);

/*
|--------------------------------------------------------------------------
| CONTACT HERO
|--------------------------------------------------------------------------
*/

HomeContent::create([
    'section' => 'contact_hero',

    'subtitle' => 'CONTACT US',

    'title' => "Begin your\nCasa Verde stay.",

    'description' =>
        'Whether you are planning a short escape, a long stay or a special sunset dinner, our team is here to help.',

    'image' => 'images/contact-hero.jpg',

    'sort_order' => 1,
]);


/*
|--------------------------------------------------------------------------
| CONTACT INFORMATION
|--------------------------------------------------------------------------
*/

HomeContent::create([
    'section' => 'contact_info',

    'title' => 'Location',

    'description' =>
        'Sitio Mankahilo, Consuelo, San Francisco, Camotes Island, Cebu 6050, Philippines',

    'icon' => 'location',

    'sort_order' => 1,
]);


HomeContent::create([
    'section' => 'contact_info',

    'title' => 'Phone / WhatsApp',

    'description' => '+63 962-255-8380',

    'icon' => 'phone',

    'sort_order' => 2,
]);


HomeContent::create([
    'section' => 'contact_info',

    'title' => 'Email',

    'description' => 'bookings@casaverdecliffresort.com',

    'icon' => 'email',

    'sort_order' => 3,
]);


HomeContent::create([
    'section' => 'contact_info',

    'title' => 'Hours',

    'description' =>
        "Daily: 7:00 AM – 9:00 PM\nwe’re here to help you anytime.",

    'icon' => 'clock',

    'sort_order' => 4,
]);


/*
|--------------------------------------------------------------------------
| CONTACT FORM
|--------------------------------------------------------------------------
*/

HomeContent::create([
    'section' => 'contact_form',

    'title' => 'SEND US A MESSAGE',

    'description' =>
        'We aim to respond to all inquiries within 24 hours.',

    'button_text' => 'SEND MESSAGE',

    'button_url' => '#',

    'sort_order' => 1,
]);


/*
|--------------------------------------------------------------------------
| CONTACT MAP
|--------------------------------------------------------------------------
*/

HomeContent::create([
    'section' => 'contact_map',

    'title' => 'FIND US',

    'image' => 'images/contact-map.jpg',

    'sort_order' => 1,
]);


/*
|--------------------------------------------------------------------------
| CONTACT FOOTER
|--------------------------------------------------------------------------
*/

HomeContent::create([
    'section' => 'contact_footer',

    'title' => 'CLIFFSIDE LUXURY · ISLAND SOUL',

    'image' => 'images/logo.png',

    'sort_order' => 1,
]);

    } 
}



