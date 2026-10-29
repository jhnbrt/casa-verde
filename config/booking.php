<?php

/*
|--------------------------------------------------------------------------
| Online booking (/book)
|--------------------------------------------------------------------------
| Starting content for the booking page. Once the migration has run, the
| rates, promo dates and Terms & Conditions are edited in the admin under
| Site content > Booking. These values are only the starting point.
|
| BOOKING_NOTIFY_EMAIL (in .env) is the inbox that receives every new
| booking request. When it is empty, MAIL_FROM_ADDRESS is used.
*/

return [

    'notify' => env('BOOKING_NOTIFY_EMAIL'),

    // Most guests per room type, adults and children counted separately.
    'max_guests' => 10,

    // Longest stay a guest can request online.
    'max_nights' => 30,

    'terms' => "General Information\n"
        . "NON-REFUNDABLE RATES\n"
        . "• This terms and conditions applies to all bookings made at Casa Verde Cliff Resort and Spa.\n"
        . "• By making a reservation, guest agree to comply with these terms.\n"
        . "• All Rooms are non-smoking room. Smoking in a non-smoking room will be charged Php 7,000.00",

    // Keyed by the villa slug (the villa title, slugified).
    'rates' => [
        'standard-villa' => ['regular' => 9000, 'promo' => 5500, 'from' => '2026-11-01', 'to' => '2026-11-30'],
        'premium-villa' => ['regular' => 14500, 'promo' => 9500, 'from' => '2026-10-21', 'to' => '2026-10-31'],
    ],
];
