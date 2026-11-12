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
            'button_url' => '#food-menu',
        ],
        [
            'button_text' => 'EXPLORE DRINKS MENU',
            'button_url' => '#',
        ],
    ],

    // The food menu "book": a cover and its pages, opened by the EXPLORE FOOD MENU button.
    'dining_page_food_menu' => [
        [
            'title' => 'Food menu',
            'image' => 'images/dining/menu/cover.jpg',
            'subtitle' => 'Casa Verde Cliff Resort & Spa',
            'button_text' => 'Open Menu',
            'description' => 'Click the arrows or a page, or swipe, to turn the pages.',
        ],
    ],

    // Menu categories: each one is the heading of its pages (two dishes per page, new pages are added as dishes are).
    // "subtitle" = dishes per page (1 to 4, empty = 2), "description" = small label above the heading (optional).
    'dining_page_food_menu_categories' => [
        ['title' => 'Mains', 'subtitle' => '2'],
        ['title' => 'Pizza', 'subtitle' => '2'],
        ['title' => 'Pasta', 'subtitle' => '2'],
        ['title' => 'Kids', 'subtitle' => '2'],
        ['title' => 'Burgers and Sandwiches', 'subtitle' => '2'],
        ['title' => 'Filipino', 'subtitle' => '2'],
    ],

    // Dishes of the book. "subtitle" is the category (its name here; the admin stores the category itself).
    // "features" are the dietary icons: seafood, pork, chili, chicken, beef, nuts, cheese.
    'dining_page_food_menu_dishes' => [
        [
            'title' => 'Salmon Steak', 'subtitle' => 'Mains', 'price' => 695, 'features' => ['seafood'],
            'description' => 'Perfectly pan-grilled salmon steak topped with lemon garlic butter sauce, served with roasted pumpkin for a light yet flavorful finish.',
            'image' => 'images/dining/menu/dishes/salmon-steak.jpg',
        ],
        [
            'title' => 'Beef Goulash', 'subtitle' => 'Mains', 'price' => 745, 'features' => ['beef'],
            'description' => 'Slow-cooked tender beef in a rich red wine sauce, served with buttered boiled potatoes and French bread for a hearty, comforting classic.',
            'image' => 'images/dining/menu/dishes/beef-goulash.jpg',
        ],
        [
            'title' => 'Chicken Curry', 'subtitle' => 'Mains', 'price' => 595, 'features' => ['chicken', 'chili'],
            'description' => 'Tender chicken simmered in coconut milk with ginger and fish sauce, with chunky potatoes, carrots, and bell peppers for a rich, comforting curry.',
            'image' => 'images/dining/menu/dishes/chicken-curry.jpg',
        ],
        [
            'title' => 'Chicken Parmy Katsu Style', 'subtitle' => 'Mains', 'price' => 660, 'features' => ['chicken'],
            'description' => 'Breaded fried chicken served with buttered mixed vegetables and Asian-style egg fried rice.',
            'image' => 'images/dining/menu/dishes/chicken-parmy-katsu.jpg',
        ],
        [
            'title' => 'Chicken Schnitzel Cordon Bleu', 'subtitle' => 'Mains', 'price' => 695, 'features' => ['chicken'],
            'description' => 'Crispy breaded chicken breast, golden-fried and topped with red schnitzel sauce, served with buttered vegetables and creamy mashed potatoes.',
            'image' => 'images/dining/menu/dishes/chicken-schnitzel-cordon-bleu.jpg',
        ],
        [
            'title' => 'Buttered Garlic Shrimp', 'subtitle' => 'Mains', 'price' => 695, 'features' => ['seafood'],
            'description' => 'Succulent shrimp sautéed in rich garlic butter, served with buttered mixed vegetables and homemade fried potatoes for a hearty, satisfying plate.',
            'image' => 'images/dining/menu/dishes/buttered-garlic-shrimp.jpg',
        ],
        [
            'title' => 'Quatroformaggi', 'subtitle' => 'Pizza', 'price' => 980, 'features' => ['cheese'],
            'description' => 'A rich Italian pie featuring a combination of four distinct cheeses, offering a balanced blend of creamy and nutty flavors.',
            'image' => 'images/dining/menu/dishes/quatroformaggi.jpg',
        ],
        [
            'title' => 'Meat Lovers', 'subtitle' => 'Pizza', 'price' => 980, 'features' => ['cheese', 'pork', 'beef'],
            'description' => 'Hearty pizza loaded with savory beef, sausage, bacon, ham, and ground beef over rich marinara sauce and melted mozzarella cheese. Bold, satisfying, and crafted for true meat enthusiasts.',
            'image' => 'images/dining/menu/dishes/meat-lovers.jpg',
        ],
        [
            'title' => 'Prosciutto de Parma', 'subtitle' => 'Pizza', 'price' => 850, 'features' => ['pork', 'cheese'],
            'description' => 'Traditional pizza base with authentic marinara sauce and melted mozzarella, finished with delicate slices of prosciutto, a drizzle of extra virgin olive oil, and pesto. Elegant, aromatic, and distinctly Italian.',
            'image' => 'images/dining/menu/dishes/prosciutto-de-parma.jpg',
        ],
        [
            'title' => 'Hawaiian', 'subtitle' => 'Pizza', 'price' => 850, 'features' => ['pork', 'cheese'],
            'description' => 'Pizza dough layered with authentic Italian marinara sauce and mozzarella cheese, topped with sliced ham, sweet pineapple chunks, bell peppers, onions, and finished with a drizzle of olive oil. A perfect balance of savory and sweet.',
            'image' => 'images/dining/menu/dishes/hawaiian.jpg',
        ],
        [
            'title' => 'Vegetarian', 'subtitle' => 'Pizza', 'price' => 750, 'features' => ['cheese'],
            'description' => 'Freshly baked pizza topped with marinara sauce, melted mozzarella, and a vibrant medley of bell peppers, onions, mushrooms, and olives. Light, flavorful, and full of garden freshness.',
            'image' => 'images/dining/menu/dishes/vegetarian.jpg',
        ],
        [
            'title' => 'Margherita', 'subtitle' => 'Pizza', 'price' => 650, 'features' => ['cheese'],
            'description' => 'Traditionally created in Naples, Italy, it offers a balance of tangy, bright tomato flavor combined with creamy mozzarella and aromatic basil.',
            'image' => 'images/dining/menu/dishes/margherita.jpg',
        ],
        [
            'title' => 'Seafood Linguini', 'subtitle' => 'Pasta', 'price' => 555, 'features' => ['seafood'],
            'description' => 'Linguini pasta tossed with fresh salmon and peeled shrimp in a creamy white wine sauce, infused with garlic and onions, finished with a touch of truffle oil and grated Parmesan cheese. Served with warm bread.',
            'image' => 'images/dining/menu/dishes/seafood-linguini.jpg',
        ],
        [
            'title' => 'Carbonara', 'subtitle' => 'Pasta', 'price' => 450, 'features' => ['pork', 'cheese'],
            'description' => 'Perfectly cooked pasta enveloped in a velvety blend of cream, Parmesan, and egg, layered with smoky bacon and tender ham, finished with cracked black pepper. Luxurious yet balanced.',
            'image' => 'images/dining/menu/dishes/carbonara.jpg',
        ],
        [
            'title' => 'Agli Olio', 'subtitle' => 'Pasta', 'price' => 400, 'features' => ['chili', 'cheese'],
            'description' => 'Aldente spaghetti tossed in fragrant olive oil with sautéed garlic and chili flakes, finished with Parmesan cheese and parsley. Served with warm bread.',
            'image' => 'images/dining/menu/dishes/agli-olio.jpg',
        ],
        [
            'title' => 'Fried Chicken Nibblers', 'subtitle' => 'Kids', 'price' => 365, 'features' => ['chicken', 'cheese'],
            'description' => '',
            'image' => 'images/dining/menu/dishes/fried-chicken-nibblers.jpg',
        ],
        [
            'title' => 'Mac & Cheese', 'subtitle' => 'Kids', 'price' => 350, 'features' => ['cheese'],
            'description' => '',
            'image' => 'images/dining/menu/dishes/mac-and-cheese.jpg',
        ],
        [
            'title' => 'Casa Verde Monster Burger', 'subtitle' => 'Burgers and Sandwiches', 'price' => 695, 'features' => ['beef'],
            'description' => 'A juicy beef patty layered with melted cheddar, fresh lettuce, tomato, cucumber, and pickles in a soft black bun, served with crispy French fries and ketchup.',
            'image' => 'images/dining/menu/dishes/casa-verde-monster-burger.jpg',
        ],
        [
            'title' => 'Crazy Devil Chicken Burger', 'subtitle' => 'Burgers and Sandwiches', 'price' => 550, 'features' => ['chicken'],
            'description' => 'Crispy chicken breast layered with melted cheddar, fresh lettuce, tomato, and cucumber in a toasted bun, served with golden French fries and ketchup for a bold, satisfying bite.',
            'image' => 'images/dining/menu/dishes/crazy-devil-chicken-burger.jpg',
        ],
        [
            'title' => 'Sunset Clubhouse Sandwich', 'subtitle' => 'Burgers and Sandwiches', 'price' => 550, 'features' => ['pork'],
            'description' => 'A classic triple-layer club sandwich stacked with crispy bacon, ham, egg, fresh lettuce, tomato, and cucumber, spread with creamy mayo and served with golden French fries.',
            'image' => 'images/dining/menu/dishes/sunset-clubhouse-sandwich.jpg',
        ],
        [
            'title' => 'Chopsuey', 'subtitle' => 'Filipino', 'price' => 375, 'features' => [],
            'description' => 'Fresh mixed vegetables, carrots, sayote, bell peppers, and cabbage, stir-fried in garlic and savory oyster sauce for a light yet flavorful classic chop suey.',
            'image' => 'images/dining/menu/dishes/chopsuey.jpg',
        ],
        [
            'title' => 'Stir Fried Noodles', 'subtitle' => 'Filipino', 'price' => 565, 'features' => ['chicken', 'seafood'],
            'description' => 'A hearty combination of canton and sotanghon noodles stir-fried with chicken, shrimp, and squid balls, tossed with fresh vegetables in a rich, savory sauce, locally known as Bam-E.',
            'image' => 'images/dining/menu/dishes/stir-fried-noodles.jpg',
        ],
        [
            'title' => 'Beef Pochero', 'subtitle' => 'Filipino', 'price' => 599, 'features' => ['beef'],
            'description' => 'Slow-simmered beef shank with sweet corn, potatoes, chili finger, string beans, cabbage, and onions, a hearty and comforting Cebuano-style pochero rich in flavor.',
            'image' => 'images/dining/menu/dishes/beef-pochero.jpg',
        ],
        [
            'title' => 'Spring Fried Chicken', 'subtitle' => 'Filipino', 'price' => 660, 'features' => ['chicken'],
            'description' => 'Crispy golden fried chicken marinated in calamansi and garlic, deep-fried to juicy perfection. Served with our rich, homemade brown gravy for that classic Filipino comfort flavor.',
            'image' => 'images/dining/menu/dishes/spring-fried-chicken.jpg',
        ],
    ],

    // Dietary icons: "title" is the name used in a dish's icon list, "subtitle" the label under the icon,
    // "image" an optional uploaded icon (without one the built-in drawing is used).
    'dining_page_food_menu_icons' => [
        ['title' => 'seafood', 'subtitle' => 'Seafood'],
        ['title' => 'pork', 'subtitle' => 'Pork'],
        ['title' => 'chili', 'subtitle' => 'Chili'],
        ['title' => 'chicken', 'subtitle' => 'Chicken'],
        ['title' => 'beef', 'subtitle' => 'Beef'],
        ['title' => 'nuts', 'subtitle' => 'Nuts'],
        ['title' => 'cheese', 'subtitle' => 'Cheese'],
    ],

    // Optional extra pages that are a single picture (for example a printed page); shown after the dishes.
    // "subtitle" = the category after which the page appears (empty: at the very end of the book).
    'dining_page_food_menu_pages' => [
        ['title' => 'Menu: Appetizer, Soup and Salad', 'subtitle' => '', 'image' => 'images/dining/menu/pages/menu-appetizer-soup-salad.jpg'],
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
