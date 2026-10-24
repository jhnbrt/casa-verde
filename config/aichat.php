<?php

/*
|--------------------------------------------------------------------------
| AI chat assistant and counter-offer popup: starting content
|--------------------------------------------------------------------------
|
| These are the starting values. Everything here is edited in the admin
| (Chat & offers); anything saved there overrides this file. The
| ANTHROPIC_API_KEY itself lives in the .env file, never in the admin.
|
*/

return [

    'chat' => [
        'name' => 'Casa Verde Assistant',
        'greeting' => "Hi! I'm the Casa Verde assistant. Ask me about our villas, rates, long stays or things to do on Camotes Island.",
        'suggestions' => [
            'What villa types do you have?',
            'How much is a villa per night?',
            'Tell me about long stays',
            'How do I book?',
        ],
        'button_text' => 'Chat with us',
        'booking_url' => '/#contact',
        'daily_limit' => 30,

        // The "AI brain": how the assistant behaves and what it knows. Edit it in the admin.
        'brain' => <<<'TXT'
ABOUT CASA VERDE
Casa Verde Cliff Resort & Spa is a boutique cliffside resort on Camotes Island, Philippines. It has 10 private villas along 100 metres of cliff front with ocean views, spectacular sunsets, a pool and a cave, plus a wellness spa and a restaurant & bar. The atmosphere is warm, private and unhurried, with genuine Filipino hospitality.

VILLAS
- Standard Villa: "Simple luxury. Genuine comfort." Ideal for couples and longer stays. Garden and terrace, everything you need to feel at home.
- Premium Villa: "Your best seat in paradise." More space and privacy, ocean views and cliffside atmosphere.
(Current rates are listed under "Prices from the website" when available. Never quote a price that is not listed.)

LONG STAYS
Flexible stays from 4 weeks up to 12 months, in a villa with high-speed internet, quiet spaces and daily housekeeping. Popular for workations, wellness retreats, winter escapes, family long stays and sabbaticals.

HOW GUESTS BOOK
Guests send an enquiry through the contact section of the website and the team replies personally. You cannot check availability or confirm a booking.

TONE
Warm, calm and helpful, like a good front-desk host. Short answers. If a guest writes in Bisaya/Cebuano or Tagalog, answer in that language.

IMPORTANT
Replace this text in the admin with the real details: check-in and check-out times, policies, airport transfers, how to get to Camotes, dining hours, spa menu, and anything guests often ask.
TXT,
    ],

    // Shown when there is no matching quick answer and no AI.
    'not_sure' => "I'm not sure about that yet. Please send your question through the contact section and our team will reply to you personally.",

    // Quick answers: free, no AI needed. A guest's message that contains any keyword gets that answer.
    // Facts below come from the website; please check them and add your own in the admin.
    'faq' => [
        [
            'question' => 'Hello',
            'answer' => 'Hello! Welcome to Casa Verde. You can ask me about our villas, rates, long stays, or how to book.',
            'keywords' => ['hello', 'hi', 'hey', 'good morning', 'good afternoon', 'good evening', 'kumusta', 'maayong adlaw'],
        ],
        [
            'question' => 'What villa types do you have?',
            'answer' => 'We have two villa styles: the Standard Villa (simple luxury and genuine comfort, ideal for couples and longer stays) and the Premium Villa (more space and privacy, with ocean views and cliffside atmosphere). You can see both on our Villas page.',
            'keywords' => ['villa', 'villas', 'room', 'rooms', 'accommodation', 'types', 'standard', 'premium', 'stay'],
        ],
        [
            'question' => 'How much per night?',
            'answer' => 'The Standard Villa starts from PHP 6,500 per night (approx. USD 115) and the Premium Villa from PHP 9,500 per night (approx. USD 170).',
            'keywords' => ['price', 'prices', 'rate', 'rates', 'how much', 'cost', 'per night', 'magkano', 'pila', 'bayad'],
        ],
        [
            'question' => 'Do you offer long stays?',
            'answer' => 'Yes. Long stays run from 4 weeks up to 12 months, in a private villa with breakfast, high-speed Wi-Fi, pool and wellness access, and housekeeping. See the Long Stay page for details.',
            'keywords' => ['long stay', 'long-stay', 'longstay', 'monthly', 'month', 'weeks', 'workation', 'sabbatical', 'extended'],
        ],
        [
            'question' => 'How do I book?',
            'answer' => 'Send us an enquiry through the contact section of the website with your dates and the villa you like, and our team will reply to you personally.',
            'keywords' => ['book', 'booking', 'reserve', 'reservation', 'availability', 'available', 'how to book'],
        ],
        [
            'question' => 'Where are you located?',
            'answer' => 'Casa Verde Cliff Resort & Spa is on Camotes Island, Philippines, along 100 metres of cliff front with ocean views and sunsets.',
            'keywords' => ['where', 'location', 'located', 'address', 'camotes', 'map', 'directions', 'asa'],
        ],
        [
            'question' => 'Do you have a spa?',
            'answer' => 'Yes, we have a wellness spa with programs to rest, reset and recharge. See our Wellness page for details.',
            'keywords' => ['spa', 'wellness', 'massage', 'treatment', 'treatments'],
        ],
        [
            'question' => 'Do you have a restaurant?',
            'answer' => 'Yes, there is a restaurant and bar on the property. See our Dining page to learn more.',
            'keywords' => ['restaurant', 'food', 'eat', 'dining', 'bar', 'drink', 'drinks', 'menu', 'kaon'],
        ],
        [
            'question' => 'Do you have special offers?',
            'answer' => 'Please check our Special Offers page for our current promotions.',
            'keywords' => ['offer', 'offers', 'promo', 'promotion', 'discount', 'deal', 'deals'],
        ],
        [
            'question' => 'How can I contact you?',
            'answer' => 'You can reach our team through the contact section of the website, and we will reply to you personally.',
            'keywords' => ['contact', 'phone', 'email', 'call', 'number', 'message'],
        ],
    ],

    'offer' => [
        'label' => 'Before you go',
        'title' => "Wait! Here's a little something for you",
        'text' => 'Book your stay directly with us and enjoy 10% off. Mention the code below when you send your enquiry.',
        'code' => 'CASAVERDE10',
        'button_text' => 'Claim my offer',
        'button_url' => '/#contact',
        'decline' => 'No thanks',
        'image' => 'images/main.jpg',
    ],

    // Settings for when the offer appears, in this order (admin: Chat & offers > Offer popup: rules).
    'rules' => [
        ['title' => 'Minimum time on the website before the offer can appear (seconds)', 'value' => 60],
        ['title' => 'Do not show it again to the same visitor for this many days (0 = once per visit)', 'value' => 7],
        ['title' => 'Show it when a visitor comes back to the tab after switching away (1 = yes, 0 = no)', 'value' => 1],
        ['title' => 'Show it when the mouse moves up to leave the page (1 = yes, 0 = no)', 'value' => 1],
    ],

];
