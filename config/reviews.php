<?php

/*
|--------------------------------------------------------------------------
| Reviews page defaults
|--------------------------------------------------------------------------
|
| Mock-up content and placeholder images for the Reviews page. Anything
| edited in the admin (home_contents rows whose section starts with
| "reviews_") overrides these values. The names, places and quotes below
| are placeholders until real guest reviews are supplied.
|
*/

return [

    'header' => [
        'eyebrow' => 'Guest voices',
        'title' => 'What our guests say',
        'text' => 'Real stays. Real people. Real memories.',
        'button_text' => 'Share your experience',
        'button_url' => '#rv-share', // the "Share your experience" form at the bottom of the page
        'autoplay' => 7, // seconds between automatic changes; 0 = off
    ],

    /*
    | Reviews written by guests on the website (the form at the bottom of the page).
    |
    |   require_approval - false: a new review appears on the page straight away
    |                      true:  it waits in Admin > Guest reviews until you press "Show"
    |                      (set REVIEWS_REQUIRE_APPROVAL=true in .env to switch it on)
    |   guest_limit      - how many of the newest guest reviews the carousel shows
    |   guest_photos     - resort photos used on the cards of guest reviews, one after the other
    */
    'require_approval' => env('REVIEWS_REQUIRE_APPROVAL', false),
    'guest_limit' => 20,
    'guest_photos' => [
        'images/main.jpg',
        'images/premium.jpg',
        'images/dining3.jpg',
        'images/wellness1.jpg',
        'images/standard.jpg',
    ],

    // The first review is the one shown in the middle when the page opens.
    'items' => [
        [
            'name' => 'Lukas H.',
            'place' => 'Zurich',
            'quote' => 'An extraordinary stay! The highest quality, love for detail and a team that truly listens. I could not be happier.',
            'rating' => 5,
            'image' => 'images/main.jpg',
            'avatar' => 'images/reviews/avatar-2.svg',
        ],
        [
            'name' => 'Anna K.',
            'place' => 'Hamburg',
            'quote' => 'Modern, stylish and absolutely reliable. You can feel the passion that goes into every detail here. A clear recommendation from me!',
            'rating' => 5,
            'image' => 'images/premium.jpg',
            'avatar' => 'images/reviews/avatar-3.svg',
        ],
        [
            'name' => 'Marco R.',
            'place' => 'Milan',
            'quote' => 'The cliffside views at sunset are unforgettable, and the staff made our long weekend feel effortless.',
            'rating' => 5,
            'image' => 'images/dining3.jpg',
            'avatar' => 'images/reviews/avatar-4.svg',
        ],
        [
            'name' => 'Elena V.',
            'place' => 'Cebu City',
            'quote' => 'Quiet, beautiful and so well looked after. We came for two nights and wished we had booked a week.',
            'rating' => 5,
            'image' => 'images/wellness1.jpg',
            'avatar' => 'images/reviews/avatar-5.svg',
        ],
        [
            'name' => 'Sophie M.',
            'place' => 'Munich',
            'quote' => 'Rarely have I experienced such attentive and professional service. From the first message to the last sunset, everything was simply perfect.',
            'rating' => 5,
            'image' => 'images/standard.jpg',
            'avatar' => 'images/reviews/avatar-1.svg',
        ],
    ],

    // Digits in "value" roll like slot-machine reels; every other character stays still.
    'stats' => [
        ['value' => '4.9 / 5', 'label' => 'Average rating'],
        ['value' => '500+', 'label' => 'Happy guests'],
        ['value' => '98 %', 'label' => 'Would recommend us'],
    ],

];
