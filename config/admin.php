<?php

/*
|--------------------------------------------------------------------------
| Admin content groups
|--------------------------------------------------------------------------
|
| The admin screens group the flat "section" names stored in home_contents
| by the area of the public site they belong to. A section belongs to a group
| when it equals one of the group's prefixes or starts with "<prefix>_", so a
| new section such as "dining_menu" lands under Dining without editing this
| file. Anything that matches no group is listed under "Other".
|
| "url" is the public page the group edits (used for the "View page" link).
|
*/

$galleryPhotos = fn (string $title, array $extra = []) => array_replace_recursive([
    'title' => $title,
    'help' => 'One entry per photo, in the order shown on the Gallery. Replace the photo to change it; add an entry to add a photo.',
    'fields' => ['title' => 'Caption', 'image' => 'Photo'],
    'hints' => ['title' => 'Describes the photo for visitors who cannot see it, and appears in the enlarged view.'],
    'hide' => ['subtitle', 'description', 'icon', 'price', 'features', 'button_text', 'button_url'],
], $extra);

return [

    'content_groups' => [
        'site' => ['label' => 'Site-wide', 'match' => ['site', 'navigation'], 'url' => '/'],
        'home' => ['label' => 'Home', 'match' => ['hero', 'features', 'home'], 'url' => '/'],
        'villas' => ['label' => 'Villas', 'match' => ['villas'], 'url' => '/villas'],
        'experiences' => ['label' => 'Experiences', 'match' => ['experiences'], 'url' => '/experiences'],
        'wellness' => ['label' => 'Wellness', 'match' => ['wellness'], 'url' => '/wellness'],
        'dining' => ['label' => 'Dining', 'match' => ['dining'], 'url' => '/dining'],
        'long_stay' => ['label' => 'Long stay', 'match' => ['long_stay'], 'url' => '/long-stay'],
        'winter' => ['label' => 'Winterresidenz', 'match' => ['winter'], 'url' => '/winterresidenz'],
        'events' => ['label' => 'Events', 'match' => ['events'], 'url' => '/events'],
        'yoga' => ['label' => 'Yoga Reset', 'match' => ['yoga'], 'url' => '/yoga-reset'],
        'offers' => ['label' => 'Special offers', 'match' => ['offers'], 'url' => '/special-offers'],
        'reviews' => ['label' => 'Reviews', 'match' => ['reviews'], 'url' => '/reviews'],
        'journal' => ['label' => 'Journal', 'match' => ['journal'], 'url' => '/blog'],
        'booking' => ['label' => 'Booking', 'match' => ['booking'], 'url' => '/book'],
        'assistant' => ['label' => 'Chat & offers', 'match' => ['ai_chat', 'chat_faq', 'offer_popup'], 'url' => '/'],
        'gallery' => ['label' => 'Gallery', 'match' => ['gallery'], 'url' => '/gallery'],
        'about' => ['label' => 'About', 'match' => ['about'], 'url' => '/about'],
        'contact' => ['label' => 'Contact', 'match' => ['contact'], 'url' => '/contact'],
        'footer' => ['label' => 'Footer', 'match' => ['footer'], 'url' => '/#footer'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Friendly section names and field labels
    |--------------------------------------------------------------------------
    |
    | Optional. For a section listed here the admin shows a plain-language name,
    | a short help note, relabelled fields, and hides the fields that section
    | does not use. Sections not listed keep the generic form.
    |
    |   title  - name shown in the admin
    |   help   - one-sentence explanation shown above the form
    |   fields - relabel title / subtitle / description / icon / button_text /
    |            button_url / features / image / price
    |   hide   - fields this section does not use
    |
    */

    'sections' => [

        'about_intro' => [
            'title' => 'About Us: Our Story',
            'help' => 'Edit the story shown near the top of the About page.',
            'fields' => ['title' => 'Heading', 'description' => 'Story paragraphs'],
            'hide' => ['subtitle', 'icon', 'price', 'features', 'button_text', 'button_url', 'image'],
        ],
        'about_values' => [
            'title' => 'About Us: Mission, Philosophy and Pets',
            'help' => 'Edit the main information cards. Separate paragraphs with a blank line.',
            'fields' => ['title' => 'Heading', 'description' => 'Paragraphs'],
            'hide' => ['subtitle', 'icon', 'price', 'features', 'button_text', 'button_url', 'image'],
        ],
        'about_sustainability' => [
            'title' => 'About Us: Sustainability',
            'help' => 'Edit each sustainability initiative. You may reorder these entries.',
            'fields' => ['title' => 'Initiative name', 'description' => 'Description'],
            'hide' => ['subtitle', 'icon', 'price', 'features', 'button_text', 'button_url', 'image'],
        ],

        'contact_page' => [
            'title' => 'Contact page heading and details',
            'help' => 'Edit the Contact page heading, address and contact information.',
            'fields' => ['title' => 'Main heading', 'subtitle' => 'Eyebrow label', 'description' => 'Introductory text', 'button_text' => 'Contact email', 'button_url' => 'Contact phone (display text)'],
            'hide' => ['image', 'icon', 'price', 'features'],
        ],
        'contact_map' => [
            'title' => 'Google Map (Contact page and Home "Find us")',
            'help' => 'Paste the Google Maps EMBED URL from Google Maps > Share > Embed a map. Use the URL inside src="..." (not the entire iframe). The map is interactive and requires no API key. The same map is shown on the Contact page and under "Find us" on the Home page. For the satellite view, add &t=k to the URL.',
            'fields' => ['title' => 'Map heading', 'subtitle' => 'Resort address', 'description' => 'Google Maps embed URL', 'button_url' => 'Open in Google Maps URL', 'button_text' => 'Map button text'],
            'hints' => ['description' => 'Must start with https://www.google.com/maps/embed? or https://www.google.com/maps?; copy the exact location embed URL from Google Maps.', 'button_url' => 'Full Google Maps share link (https://...)'],
            'hide' => ['image', 'icon', 'price', 'features'],
        ],

        'site' => [
            'title' => 'Logo and resort name',
            'help' => 'The logo is shown in the header, in the round badge inside every "Book your stay" style button, in the footer, on the Events page and in this admin. Upload a square PNG or SVG with a transparent or dark-green background. Only one entry is used.',
            'fields' => ['title' => 'Resort name (header)', 'subtitle' => 'Tagline under the name (header)', 'image' => 'Logo'],
            'hints' => ['image' => 'Square image, at least 200 x 200 px. Replace it here and the whole site follows.'],
            'hide' => ['description', 'icon', 'price', 'features', 'button_text', 'button_url'],
        ],
        'features' => [
            'title' => 'Home page: icon strip under the banner',
            'help' => 'The six icons along the bottom of the Home banner, in order: cliff, sunsets, villas, ocean, wellness, restaurant. Upload an image (PNG or SVG) under Icon to replace one; clear it to go back to the original picture.',
            'fields' => ['title' => 'First line of text', 'subtitle' => 'Second line of text', 'icon' => 'Icon image'],
            'hints' => ['icon' => 'Upload a transparent PNG or SVG, roughly square. Leave empty to keep the original picture.'],
            'hide' => ['description', 'image', 'price', 'features', 'button_text', 'button_url'],
        ],
        'home_villa_images' => [
            'title' => 'Home page: Villa card photos',
            'help' => 'Change only the villa photos on the Home Page. These photos are independent from the Villas Page. Keep each entry title as Standard Villa or Premium Villa.',
            'fields' => ['title' => 'Villa name (Standard Villa or Premium Villa)', 'image' => 'Home Page villa photo'],
            'hints' => ['title' => 'Keep the villa name unchanged so the correct card uses this image.'],
            'hide' => ['subtitle', 'description', 'icon', 'price', 'features', 'button_text', 'button_url'],
        ],
        'villas' => [
            'title' => 'Villas',
            'help' => 'One entry per villa (Standard Villa, Premium Villa). The Photo is the picture shown on the villa card. Below it, Gallery photos are the pictures that slide open when a visitor clicks that picture on the Villas page: add as many as you like (8 looks best), change their order, or remove them.',
            'fields' => ['image' => 'Card photo (shown on the Villas page)'],
            'gallery' => true,
        ],
        'villas_detail_photos_standard_villa' => [
            'title' => 'Standard Villa detail: Interior photos',
            'help' => 'These are the three interior photographs beside “Designed for comfort” on the Standard Villa detail page. Replace each Photo to update that position; Sort order 1 is the large image, then 2 and 3 are the smaller images. These are separate from the Villas listing and Home photos.',
            'fields' => ['title' => 'Photo label', 'image' => 'Interior photo'],
            'hide' => ['subtitle', 'description', 'icon', 'price', 'features', 'button_text', 'button_url'],
        ],
        'villas_detail_photos_premium_villa' => [
            'title' => 'Premium Villa detail: Interior photos',
            'help' => 'Photos beside the description on the Premium Villa detail page. Sort order 1 is the large photo; 2 and 3 are smaller. These are independent of the Home and Villas listing photos.',
            'fields' => ['title' => 'Photo label', 'image' => 'Interior photo'],
            'hide' => ['subtitle', 'description', 'icon', 'price', 'features', 'button_text', 'button_url'],
        ],
        'home_villa_feature_icons' => [
            'title' => 'Home page: Villa card feature icons',
            'help' => 'Each entry controls one feature icon on the villa cards on the Home page. These are separate from the Villas page, so changing them here never changes the Villas page. Keep Title exactly as Standard Villa or Premium Villa, and Sort order as 1, 2 or 3 to match the feature line. Upload a PNG, JPG, WebP or SVG under Icon, or enter an icon name (bed, leaf, house, sunset, balcony, star).',
            'fields' => ['title' => 'Villa name (Standard Villa or Premium Villa)', 'icon' => 'Feature icon / SVG / image'],
            'hints' => ['title' => 'Must match the villa title.', 'sort_order' => '1 = first feature; 2 = second; 3 = third.'],
            'hide' => ['subtitle', 'description', 'image', 'price', 'features', 'button_text', 'button_url'],
        ],
        'villas_feature_icons' => [
            'title' => 'Villas page: Villa card feature icons',
            'help' => 'Each entry controls one feature icon on the Villas page. These are separate from the Home page, so changing them here never changes the Home page. Keep Title exactly as Standard Villa or Premium Villa, and Sort order as 1, 2 or 3 to match the feature line. Upload a PNG, JPG, WebP or SVG under Icon, or enter an icon name. The feature text is edited separately in the villa entry.',
            'fields' => ['title' => 'Villa name (Standard Villa or Premium Villa)', 'icon' => 'Feature icon / SVG / image'],
            'hints' => ['title' => 'Must match the villa title.', 'sort_order' => '1 = first feature; 2 = second; 3 = third.'],
            'hide' => ['subtitle', 'description', 'image', 'price', 'features', 'button_text', 'button_url'],
        ],

        'experiences_header' => [
            'title' => 'Home page: Experiences heading',
            'help' => 'The label, heading and text above the Experiences carousel. The divider under the label follows the site-wide Line design (Site-wide > Line design), so the picture set here is not used.',
            'fields' => ['price' => 'Turn to the next card every (seconds)', 'icon' => 'Experiences decoration image / symbol'],
            'hints' => ['price' => 'Leave empty for 5 seconds. Type 0 to stop the automatic turning (visitors can still use the arrows).'],
        ],
        'experiences' => [
            'title' => 'Home page: Experience cards',
            'help' => 'One entry per card in the Experiences carousel, in the order shown. The first card opens in the middle. Add an entry to add a card (3 or more cards make the 3D carousel). A landscape photo at least 1000 px wide looks best.',
            'fields' => ['title' => 'Card title', 'description' => 'Short text under the title', 'image' => 'Photo'],
            'hide' => ['subtitle', 'icon', 'price', 'features', 'button_text', 'button_url'],
        ],
        'dining_intro' => [
            'title' => 'Home page: Dining intro',
            'help' => 'The divider under the Dining section now follows the site-wide Line design (Site-wide > Line design), so the ornament set here is no longer used.',
            'fields' => ['icon' => 'Dining divider ornament (image / symbol)'],
            'hints' => ['icon' => 'Upload an image (PNG or SVG), or type an emoji or symbol such as ♧ 🍽️.'],
        ],
        'wellness_quote' => [
            'title' => 'Home page: Wellness quote',
            'help' => 'Upload an image or type a symbol under Icon to change the ornament between the two lines under the Wellness quote. Clear Icon to use the shared ornament in Home page: symbols and wording.',
            'fields' => ['icon' => 'Quote ornament (image / symbol)'],
            'hints' => ['icon' => 'Upload an image (PNG or SVG), or type an emoji or symbol.'],
        ],
        'wellness_intro' => [
            'title' => 'Home page: Wellness intro',
            'help' => 'The Icon is the small symbol before the Wellness button text. Upload an image or type a symbol; clear it to use the shared symbol in Home page: symbols and wording.',
            'fields' => ['icon' => 'Button symbol (image / symbol)'],
        ],
        'language_settings' => [
            'title' => 'Languages (use the Languages page)',
            'help' => 'These are managed on the Languages page in the sidebar. Changing them here may break the language menu.',
            'fields' => ['title' => 'Name'],
            'hide' => ['subtitle', 'image', 'icon', 'price', 'features', 'button_text', 'button_url'],
        ],
        'booking_page' => [
            'title' => 'Booking page: heading',
            'help' => 'The heading and intro at the top of the Book page.',
            'fields' => ['subtitle' => 'Small label', 'title' => 'Heading', 'description' => 'Intro text'],
            'hide' => ['image', 'icon', 'price', 'features', 'button_text', 'button_url'],
        ],
        'booking_rates' => [
            'title' => 'Booking page: rates & terms',
            'help' => 'One entry per villa; the Title must match the villa name. Set the regular and promo price per night, the promo dates and the Terms & Conditions guests agree to. Turn an entry OFF to remove that villa from online booking. The photos come from the villa\'s Gallery photos in Villas.',
            'fields' => [
                'title' => 'Villa name',
                'subtitle' => 'Regular price per night',
                'price' => 'Promo price per night',
                'button_text' => 'Promo starts (YYYY-MM-DD)',
                'button_url' => 'Promo ends (YYYY-MM-DD)',
                'description' => 'Terms & Conditions',
            ],
            'hints' => [
                'subtitle' => 'Numbers only, for example 9000.',
                'price' => 'Leave empty for no promo. The promo applies to every night from the start date to the end date, inclusive.',
                'description' => 'Press Enter for a new line.',
            ],
            'hide' => ['image', 'icon', 'features'],
        ],
        'booking_labels' => [
            'title' => 'Booking page: wording',
            'help' => 'Every entry is one piece of text on the Book page, in this order: 1-3 the three steps under the heading, 4 "Reserve this villa" button, 5 button text while the form is open, 6 "/ night", 7 note under the price, 8 "Promo dates:", 9 Dates heading, 10 Guests heading, 11 Your details heading, 12 submit button, 13 note under the total, 14 agree checkbox, 15 Terms heading, 16 Your stay heading, 17 Estimated total. Edit the text of each entry; an empty one uses the original wording.',
            'fields' => ['title' => 'Text'],
            'hide' => ['subtitle', 'description', 'image', 'icon', 'price', 'features', 'button_text', 'button_url'],
        ],
        'booking_confirmation' => [
            'title' => 'Booking page: "request received" message',
            'help' => 'What guests read right after sending a request. Type :name where the guest\'s first name should appear and :email for their email address.',
            'fields' => ['subtitle' => 'Small label', 'title' => 'Heading', 'description' => 'Message'],
            'hide' => ['image', 'icon', 'price', 'features', 'button_text', 'button_url'],
        ],
        'booking_settings' => [
            'title' => 'Booking: settings',
            'help' => 'Where requests go and the limits guests must stay within. Turn this entry OFF to pause online booking: the Book page then shows the message below instead of the villas.',
            'fields' => [
                'title' => 'Email that receives booking requests',
                'subtitle' => 'Longest stay (nights)',
                'price' => 'Most guests per villa (adults and children each)',
                'description' => 'Message shown while booking is paused',
            ],
            'hints' => ['title' => 'Leave empty to use the website\'s main email address.'],
            'hide' => ['image', 'icon', 'features', 'button_text', 'button_url'],
        ],
        'long_stay_booking' => [
            'title' => 'Home page: Long stay booking',
            'help' => 'The Icon is the small symbol in the Long stay booking bar. Upload an image or type a symbol; clear it to use the shared symbol in Home page: symbols and wording.',
            'fields' => ['icon' => 'Booking symbol (image / symbol)'],
        ],
        'hero' => [
            'title' => 'Home page: Hero',
            'help' => 'The Icon is the small symbol in the hero button. Upload an image or type a symbol; clear it to use the shared symbol in Home page: symbols and wording.',
            'fields' => ['icon' => 'Hero button symbol (image / symbol)'],
        ],

        // ---- Dining, Experiences, Wellness, Villas pages and Home labels ----
        'dining_page_hero' => [
            'title' => 'Top banner',
            'help' => 'The heading, text and photo at the top of the Dining page.',
            'fields' => ['subtitle' => 'Small label', 'title' => 'Headings', 'description' => 'Intro text', 'image' => 'Banner photo'],
            'hints' => ['title' => 'Line 1 is the big heading. Line 2 (press Enter) is the smaller italic line.'],
            'hide' => ['icon', 'price', 'features', 'button_text', 'button_url'],
        ],
        'dining_page_features' => [
            'title' => 'Dining highlights',
            'help' => 'One entry per card (cuisine, cocktails, seafood...). Add an entry to add a card.',
            'fields' => ['title' => 'Card title', 'description' => 'Short description', 'image' => 'Card photo', 'icon' => 'Emoji / icon'],
            'hints' => ['icon' => 'An emoji (🍽️) or an icon name such as cloche, cocktail, fish, sunset, coffee, cutlery, wine, lotus, leaf, calendar. Leave empty for none.'],
            'hide' => ['subtitle', 'price', 'features', 'button_text', 'button_url'],
        ],
        'dining_page_info' => [
            'title' => 'Bottom information',
            'help' => 'The two notes at the bottom. The first sits on the left, the second on the right.',
            'fields' => ['title' => 'Heading', 'description' => 'Text', 'icon' => 'Emoji / icon'],
            'hints' => ['icon' => 'An emoji (🍽️) or an icon name such as cloche, cocktail, fish, sunset, coffee, cutlery, wine, lotus, leaf, calendar. Leave empty for none.'],
            'hide' => ['subtitle', 'image', 'price', 'features', 'button_text', 'button_url'],
        ],
        'dining_page_menus' => [
            'title' => 'Menu buttons',
            'help' => 'The buttons between the two bottom notes. Link to a PDF, a page or a full address.',
            'fields' => ['button_text' => 'Button text', 'button_url' => 'Button link'],
            'hints' => ['button_url' => 'A page such as /gallery, a section such as /#contact, or a full address starting with https://. Use # for no link yet. Use #food-menu to open the food menu book (see "Food menu book" below).'],
            'hide' => ['title', 'subtitle', 'description', 'image', 'icon', 'price', 'features'],
        ],
        'dining_page_food_menu' => [
            'title' => 'Food menu book: cover',
            'help' => 'The cover of the menu book that opens when guests click the EXPLORE FOOD MENU button (its link must be #food-menu). Guests see the cover with a "Click here" hint; clicking it opens the book. The pages are built from "Food menu book: categories" and "Food menu book: dishes". Remove the cover image to turn the book off.',
            'fields' => ['title' => 'Menu name', 'image' => 'Cover image', 'button_text' => 'Button on the cover', 'description' => 'Helper line under the open book', 'subtitle' => 'Footer text on every page', 'icon' => 'Footer logo on every page (image / symbol)'],
            'hints' => [
                'title' => 'Used as the name of the book for screen readers.',
                'image' => 'A portrait image, like the cover and menu pages (about 1414 x 2000 px). All pages should have the same shape.',
                'button_text' => 'The text on the button over the closed cover, for example Open Menu.',
                'subtitle' => 'The small line at the bottom of every menu page, under the logo.',
                'icon' => 'The logo at the bottom of every menu page. Upload an image or type a symbol; clear it to use the default lotus.',
            ],
            'hide' => ['price', 'features', 'button_url'],
        ],
        'dining_page_food_menu_categories' => [
            'title' => 'Food menu book: categories',
            'help' => 'The headings of the menu pages (Mains, Starters, Desserts...), in the order they appear in the book. Each category gets as many pages as its dishes need (you set how many dishes fit on a page). Add the dishes under "Food menu book: dishes" and choose their category there.',
            'fields' => ['title' => 'Category name', 'subtitle' => 'Dishes per page', 'description' => 'Small label above the heading (optional)'],
            'hints' => [
                'title' => 'Shown as the big heading at the top of its pages.',
                'subtitle' => 'A number from 1 to 4. Keep it at 2 for the large photos and text of the main dishes. Empty means 2.',
                'description' => 'For example Menu, shown in small green letters above the heading. Leave empty for none.',
            ],
            'hide' => ['image', 'icon', 'price', 'features', 'button_text', 'button_url'],
        ],
        'dining_page_food_menu_dishes' => [
            'title' => 'Food menu book: dishes',
            'help' => 'One entry per dish. Add a new recipe here with its name, price, description and photo, then choose its category: the book makes room for it automatically (photos alternate left and right). Use the position number to reorder dishes within a category.',
            'fields' => [
                'title' => 'Dish name',
                'subtitle' => 'Category',
                'price' => 'Price',
                'description' => 'Description',
                'image' => 'Photo',
                'features' => 'Icons (one per line)',
            ],
            'hints' => [
                'description' => 'Keep it to about 150 characters (two to four short lines) so it fits the page at a size that is easy to read.',
                'subtitle' => 'Choose the category (the page heading) this dish belongs to.',
                'price' => 'A number such as 695. It is shown without a currency, like on the printed menu.',
                'image' => 'A photo of the plate, ideally with the dish in the middle and a plain background. A square photo is shown in a circle; any other shape is shown whole with soft edges.',
                'features' => 'Dietary icons, one per line, named as in "Food menu book: icons" (seafood, pork, chili, chicken, beef, nuts, cheese...).',
            ],
            'select_source' => 'menu_categories',
            'hide' => ['icon', 'button_text', 'button_url'],
        ],
        'dining_page_food_menu_icons' => [
            'title' => 'Food menu book: icons',
            'help' => 'The dietary icons shown next to a dish name and in the legend at the bottom of every page, in the order they appear. Upload your own image for an icon (a small square PNG with a transparent background looks best) or leave it empty to use the built-in drawing. Add an entry to add a new icon, then use its name in a dish.',
            'fields' => ['title' => 'Icon name', 'subtitle' => 'Label under the icon', 'image' => 'Icon image'],
            'hints' => [
                'title' => 'The name typed in a dish icon list, in lowercase without spaces (for example seafood, vegan).',
                'subtitle' => 'The small word under the icon in the legend.',
                'image' => 'Optional. Without an image, the built-in drawing is used for seafood, pork, chili, chicken, beef, nuts and cheese.',
            ],
            'hide' => ['description', 'icon', 'price', 'features', 'button_text', 'button_url'],
        ],
        'dining_page_food_menu_pages' => [
            'title' => 'Food menu book: picture pages (optional)',
            'help' => 'Extra pages that are a single picture, for example a printed page. Choose the category after which each one appears (empty: at the end of the book). Use the same portrait shape as the cover. For dishes, use \"Food menu book: dishes\" instead: their text, photos and icons stay editable.',
            'fields' => ['title' => 'Page name', 'subtitle' => 'Show after category', 'image' => 'Page image'],
            'hints' => ['title' => 'Used as the picture description.', 'subtitle' => 'The page appears right after the last page of this category.'],
            'select_source' => 'menu_categories',
            'hide' => ['description', 'icon', 'price', 'features', 'button_text', 'button_url'],
        ],
        'experiences_page_hero' => [
            'title' => 'Top banner',
            'help' => 'The heading, text and photo at the top of the Experiences page.',
            'fields' => ['subtitle' => 'Small label', 'title' => 'Heading', 'description' => 'Intro text', 'image' => 'Banner photo'],
            'hints' => ['title' => 'Press Enter to start a new line.'],
            'hide' => ['icon', 'price', 'features', 'button_text', 'button_url'],
        ],
        'experiences_page_signature_header' => [
            'title' => 'Signature: heading',
            'help' => 'The heading above the three large signature photos.',
            'fields' => ['title' => 'Heading'],
            'hide' => ['subtitle', 'description', 'image', 'icon', 'price', 'features', 'button_text', 'button_url'],
        ],
        'experiences_page_signature' => [
            'title' => 'Signature experiences',
            'help' => 'One entry per large photo card (Cliffside Jacuzzi, Hidden Cave...).',
            'fields' => ['title' => 'Name', 'image' => 'Photo'],
            'hide' => ['subtitle', 'description', 'icon', 'price', 'features', 'button_text', 'button_url'],
        ],
        'experiences_page_more_header' => [
            'title' => 'More experiences: heading',
            'help' => 'The heading above the smaller experience cards.',
            'fields' => ['title' => 'Heading'],
            'hide' => ['subtitle', 'description', 'image', 'icon', 'price', 'features', 'button_text', 'button_url'],
        ],
        'experiences_page_more' => [
            'title' => 'More experiences',
            'help' => 'One entry per smaller card (Padel, Snorkeling, Yoga...). Add an entry to add a card.',
            'fields' => ['title' => 'Name', 'description' => 'Short line', 'image' => 'Photo'],
            'hide' => ['subtitle', 'icon', 'price', 'features', 'button_text', 'button_url'],
        ],
        'experiences_page_concierge' => [
            'title' => 'Concierge notes',
            'help' => 'The three notes in the bottom bar.',
            'fields' => ['title' => 'Heading', 'description' => 'Text', 'icon' => 'Emoji / icon'],
            'hints' => ['icon' => 'An emoji (💬) or an icon name such as cloche, whatsapp, calendar, clock, phone, mail. Leave empty for none.', 'description' => 'Press Enter to start a new line.'],
            'hide' => ['subtitle', 'image', 'price', 'features', 'button_text', 'button_url'],
        ],
        'experiences_page_cta' => [
            'title' => 'Plan button',
            'help' => 'The button at the end of the bottom bar.',
            'fields' => ['button_text' => 'Button text', 'button_url' => 'Button link'],
            'hide' => ['title', 'subtitle', 'description', 'image', 'icon', 'price', 'features'],
        ],
        'wellness_page_hero' => [
            'title' => 'Top banner',
            'help' => 'The heading, text and photo at the top of the Wellness page.',
            'fields' => ['subtitle' => 'Small label', 'title' => 'Heading', 'description' => 'Intro text', 'image' => 'Banner photo'],
            'hints' => ['title' => 'Press Enter to start a new line.'],
            'hide' => ['icon', 'price', 'features', 'button_text', 'button_url'],
        ],
        'wellness_page_treatments' => [
            'title' => 'Treatments',
            'help' => 'One entry per treatment card. Replace the photo to change the card image; add an entry to add a card. The round Badge icon on the photo is separate from the picture: upload your own, or type an icon name or emoji. Leave it empty for no badge. If the photo itself already has a badge drawn on it, upload a photo without one.',
            'fields' => ['title' => 'Treatment name', 'description' => 'Short description', 'image' => 'Photo', 'icon' => 'Badge icon on the photo', 'price' => 'Badge size'],
            'hints' => [
                'icon' => 'An icon name: hands, stones, leaf, foot, spaset, lotus, meditation, cocktail... or an emoji (🌿). Or upload an image above. Clear this box to remove the badge.',
                'price' => 'How big the round badge is, as a % of the photo width. Leave empty for 17. Smaller (for example 12) for a smaller badge, larger for a bigger one. Between 8 and 30. If the photo has a badge drawn into it, going much below 17 lets that old badge show around the new one.',
            ],
            'hide' => ['subtitle', 'features', 'button_text', 'button_url'],
        ],
        'wellness_page_perks' => [
            'title' => 'Spa highlights',
            'help' => 'The notes in the bottom bar. The first two sit left of the button, the rest to the right.',
            'fields' => ['title' => 'Heading', 'description' => 'Text', 'icon' => 'Emoji / icon'],
            'hints' => ['icon' => 'An emoji (🌿) or an icon name such as lotus, leaf, calendar, shield, stones, hands. Leave empty for none.', 'description' => 'Press Enter to start a new line.'],
            'hide' => ['subtitle', 'image', 'price', 'features', 'button_text', 'button_url'],
        ],
        'wellness_page_menu' => [
            'title' => 'Spa menu button',
            'help' => 'The button in the middle of the bottom bar.',
            'fields' => ['button_text' => 'Button text', 'button_url' => 'Button link'],
            'hints' => ['button_url' => 'A page, a section such as /#contact, or a full address. Use # for no link yet.'],
            'hide' => ['title', 'subtitle', 'description', 'image', 'icon', 'price', 'features'],
        ],
        'villas_page_style' => [
            'title' => 'Symbols and wording',
            'help' => 'The small symbols and words repeated on every villa card.',
            'fields' => ['icon' => 'Symbol before the villa name and on the button', 'subtitle' => 'Bullet symbol before each feature', 'image' => 'Bullet image before each feature (optional)', 'title' => 'Price prefix', 'description' => 'Price suffix', 'button_text' => 'Default button text'],
            'hints' => ['icon' => 'Any emoji or symbol (✧ 🌴). Leave empty for none.', 'subtitle' => 'Any emoji or symbol (♧ 🌿). Ignored when a bullet image is uploaded.', 'image' => 'Upload a small square image (PNG or SVG) to use instead of the bullet symbol.', 'button_text' => 'Used when a villa has no button text of its own.'],
            'hide' => ['price', 'features', 'button_url'],
        ],
        'villas_page_bottom' => [
            'title' => 'Bottom information',
            'help' => 'The notes in the dark bar at the bottom. The first is the large question; the others are short points.',
            'fields' => ['title' => 'Heading (first entry only)', 'description' => 'Text', 'icon' => 'Symbol / emoji'],
            'hints' => ['icon' => 'Any emoji or symbol (⚑ 🌴). Leave empty for none.'],
            'hide' => ['subtitle', 'image', 'price', 'features', 'button_text', 'button_url'],
        ],
        'villas_page_book' => [
            'title' => 'Booking button',
            'help' => 'The button at the end of the bottom bar.',
            'fields' => ['button_text' => 'Button text', 'button_url' => 'Button link', 'icon' => 'Symbol / emoji'],
            'hide' => ['title', 'subtitle', 'description', 'image', 'price', 'features'],
        ],
        'villas_detail_labels' => [
            'title' => 'Villa detail page: wording',
            'help' => 'Every entry is one label on a villa detail page, in this order: 1 small label, 2 availability button, 3 amenities heading, 4 layout heading, 5 interior, 6 terrace, 7 total, 8 "From", 9 "per night", 10 "approx. USD". Edit the text of each entry.',
            'fields' => ['title' => 'Label text'],
            'hide' => ['subtitle', 'description', 'image', 'icon', 'price', 'features', 'button_text', 'button_url'],
        ],
        'home_labels' => [
            'title' => 'Home page: symbols and wording',
            'help' => 'Each entry is one small piece of the Home page, in this order: 1 "Explore the resort" button text + symbol, 2 Contact column heading, 3 fallback heading, 4 hero button symbol, 5 video play symbol, 6 Experiences divider symbol, 7 Wellness button symbol, 8 Dining divider symbol, 9 Long stay booking symbol, 10 Wellness quote ornament, 11 map marker. Replace the icon with an emoji, symbol or uploaded image.',
            'fields' => ['title' => 'Label (for your reference; entries 1-3 show this text)', 'icon' => 'Symbol / emoji / image'],
            'hints' => ['icon' => 'Any emoji or symbol, or upload an icon image (PNG or SVG). Leave empty for none.'],
            'hide' => ['subtitle', 'description', 'image', 'price', 'features', 'button_text', 'button_url'],
        ],
        'site_icons' => [
            'title' => 'Site icons',
            'help' => 'Small icons used in several places. Entries, in order: 1 Arrow (newsletter button, gallery links), 2 Leaf (gallery banner), 3 Map pin, 4 Lotus (villa amenities heading and highlight fallback), 5 Villa card lotus (small lotus before each villa name on the Home page). Upload an image to replace each.',
            'fields' => ['title' => 'Label (for your reference)', 'icon' => 'Icon image'],
            'hints' => ['icon' => 'Upload an icon image (PNG or SVG), or type an emoji.'],
            'hide' => ['subtitle', 'description', 'image', 'price', 'features', 'button_text', 'button_url'],
        ],
        'villas_highlights' => [
            'title' => 'Villa highlights (icon row)',
            'help' => 'The icon row under the photo on each villa detail page. One entry per highlight. "Villa name" must match the villa title exactly (e.g. Standard Villa).',
            'fields' => ['title' => 'Highlight label', 'subtitle' => 'Villa name', 'icon' => 'Icon image'],
            'hints' => ['subtitle' => 'The villa this highlight belongs to, exactly as its title (e.g. Standard Villa).', 'icon' => 'Upload an icon image (PNG or SVG), or type an emoji.'],
            'hide' => ['description', 'image', 'price', 'features', 'button_text', 'button_url'],
        ],

        // ---- Site-wide line design -------------------------------------------
        'site_divider' => [
            'title' => 'Line design',
            'help' => 'The one divider used everywhere on the site: a thin line with a small ornament in the middle. It appears under page headings, between Home sections and above the footer button. Change it once here and every page follows. For your own ornament, upload an image under Icon image (PNG or SVG with a transparent background).',
            'fields' => ['title' => 'Line colour', 'subtitle' => 'Line style', 'icon' => 'Ornament in the middle'],
            'hints' => [
                'title' => 'A colour code such as #c9a06a. Leave empty for the default gold.',
                'subtitle' => 'Type one of: solid, dashed, dotted, double, fade.',
                'icon' => 'Upload an image under Icon image (roughly square), or type an emoji (🌿 ✿ ❖) or an icon name (lotus, leaf, island). Type none for a plain line.',
            ],
            'hide' => ['description', 'image', 'price', 'features', 'button_text', 'button_url'],
        ],

        // ---- Long stay ------------------------------------------------------
        'long_stay_hero' => [
            'title' => 'Top banner',
            'help' => 'The large photo and headline at the top of the Long Stay page.',
            'fields' => ['subtitle' => 'Small label above the heading', 'title' => 'Heading', 'description' => 'Intro text', 'features' => 'Gold line under the text (tagline)', 'image' => 'Banner photo (right side, fades into the page)', 'icon' => 'Photo position (optional)', 'button_text' => 'Button text', 'button_url' => 'Button link'],
            'hints' => [
                'features' => 'One line, for example: Your paradise. Your pace. Your life.',
                'title' => 'Press Enter to start a new line.',
                'image' => 'Use a wide, high-quality photo (at least 1800 px wide). It also shows through the green strip below.',
                'icon' => 'Which part of the photo stays in view. Type two percentages, left-right then top-bottom: 50% 50% is the centre, 80% 50% favours the right side, 50% 20% favours the top. Leave empty for the centre.',
            ],
            'hide' => ['price'],
        ],
        'long_stay_strip' => [
            'title' => 'Highlights strip',
            'help' => 'One entry per item in the dark strip under the banner (Flexible Stays, Feel at Home, and so on).',
            'fields' => ['title' => 'Title', 'description' => 'Short description', 'icon' => 'Emoji / icon (optional)'],
            'hints' => ['icon' => 'Upload a PNG or SVG (gold line art works best on the green strip), or type an icon name such as villa, wifi, lotus. Leave empty to keep the original icon.'],
            'hide' => ['subtitle', 'image', 'price', 'features', 'button_text', 'button_url'],
        ],
        'long_stay_uses_header' => [
            'title' => 'Ways to stay: heading',
            'help' => 'The line above the six ways to stay.',
            'fields' => ['subtitle' => 'Heading'],
            'hide' => ['title', 'description', 'image', 'icon', 'price', 'features', 'button_text', 'button_url'],
        ],
        'long_stay_uses' => [
            'title' => 'Ways to stay',
            'help' => 'One entry per way to stay (Workation, Sabbatical, and so on). Add an entry to add one.',
            'fields' => ['title' => 'Title', 'description' => 'Short description', 'icon' => 'Emoji / icon (optional)'],
            'hints' => ['icon' => 'Upload a PNG or SVG (gold line art works best), or type an icon name such as island, suitcase, family. Leave empty to keep the original icon.'],
            'hide' => ['subtitle', 'image', 'price', 'features', 'button_text', 'button_url'],
        ],
        'long_stay_intro' => [
            'title' => 'Introduction',
            'help' => 'The heading and paragraph above the three boxes.',
            'fields' => ['subtitle' => 'Small label above the heading', 'title' => 'Heading', 'description' => 'Intro paragraph'],
            'hide' => ['icon', 'image', 'price', 'features', 'button_text', 'button_url'],
        ],
        'long_stay_panels' => [
            'title' => 'Information boxes',
            'help' => 'One entry per box, in order (Who it is for, What is included, How it works). Only the title and style are here; the lines inside each box, with their icons, are under "Box list items".',
            'fields' => ['title' => 'Box title', 'subtitle' => 'Style'],
            'hints' => ['subtitle' => 'Type numbered to show the list as steps 1, 2, 3. Leave empty for a list with icons.'],
            'hide' => ['description', 'image', 'icon', 'price', 'features', 'button_text', 'button_url'],
        ],
        'long_stay_panel_items' => [
            'title' => 'Box list items',
            'help' => 'One entry per line inside the three boxes, in the order shown. Use the Box field to say which box a line belongs to.',
            'fields' => ['title' => 'Text', 'subtitle' => 'Box (1, 2 or 3)', 'icon' => 'Icon'],
            'hints' => [
                'subtitle' => '1 = Who it is for, 2 = What is included, 3 = How it works. You can also type the box title exactly.',
                'icon' => 'Upload a PNG or SVG (gold line art works best), or type an icon name such as laptop, book, breakfast. Not used in a numbered box.',
            ],
            'hide' => ['description', 'image', 'price', 'features', 'button_text', 'button_url'],
        ],
        'long_stay_gallery' => [
            'title' => 'Side photos',
            'help' => 'The two photos beside the boxes. Entry 1 is the tall photo in the ornate frame, entry 2 is the wide photo underneath.',
            'fields' => ['title' => 'Description of the photo (for screen readers)', 'image' => 'Photo'],
            'hints' => ['image' => 'Entry 1: tall photo (portrait works best). Entry 2: wide photo.'],
            'hide' => ['subtitle', 'description', 'icon', 'price', 'features', 'button_text', 'button_url'],
        ],
        'long_stay_bar' => [
            'title' => 'Closing bar',
            'help' => 'The dark bar at the bottom: your logo, two lines of text and a button. The logo comes from Site-wide > Logo and resort name.',
            'fields' => ['title' => 'First line', 'subtitle' => 'Second line (gold, italic)', 'button_text' => 'Button text', 'button_url' => 'Button link'],
            'hide' => ['description', 'image', 'icon', 'price', 'features'],
        ],

        // ---- Events ---------------------------------------------------------
        'events_hero' => [
            'title' => 'Top banner',
            'help' => 'The large photo and headline at the top of the Events page.',
            'fields' => ['subtitle' => 'Small label above the heading', 'title' => 'Heading', 'description' => 'Intro text', 'image' => 'Banner photo', 'button_text' => 'Button text', 'button_url' => 'Button link'],
            'hide' => ['icon', 'price', 'features'],
        ],
        'events_intro' => [
            'title' => 'Introduction',
            'help' => 'The heading and intro paragraph above the three event cards.',
            'fields' => ['subtitle' => 'Small label above the heading', 'title' => 'Heading', 'description' => 'Intro paragraph'],
            'hide' => ['icon', 'image', 'price', 'features', 'button_text', 'button_url'],
        ],
        'events_types' => [
            'title' => 'Event cards',
            'help' => 'One entry per card (Weddings, Celebrations, Retreats). Replace the photo to change the card image.',
            'fields' => ['title' => 'Card title', 'description' => 'Short description', 'image' => 'Card photo', 'icon' => 'Icon', 'button_text' => 'Button text', 'button_url' => 'Button link'],
            'hints' => ['icon' => 'An emoji (💍) or an icon name: rings, cake, lotus.'],
            'hide' => ['subtitle', 'price', 'features'],
        ],
        'events_features_header' => [
            'title' => 'Highlights heading',
            'help' => 'The heading of the dark "Everything, beautifully considered" strip.',
            'fields' => ['title' => 'Heading'],
            'hide' => ['icon', 'subtitle', 'description', 'image', 'price', 'features', 'button_text', 'button_url'],
        ],
        'events_features' => [
            'title' => 'Highlights',
            'help' => 'One entry per highlight in the dark strip (Cliffside Venues, Thoughtful Menus, and so on).',
            'fields' => ['title' => 'Highlight title', 'description' => 'Short description', 'icon' => 'Icon'],
            'hints' => ['icon' => 'An emoji (🌴) or an icon name: cliff, cloche, clipboard, island.'],
            'hide' => ['subtitle', 'image', 'price', 'features', 'button_text', 'button_url'],
        ],
        'events_personal' => [
            'title' => '"Made personal" section',
            'help' => 'The photo and text block beside it, under the highlights strip.',
            'fields' => ['subtitle' => 'Small label above the heading', 'title' => 'Heading', 'description' => 'Paragraph', 'image' => 'Photo', 'button_text' => 'Button text', 'button_url' => 'Button link'],
            'hide' => ['icon', 'price', 'features'],
        ],
        'events_cta' => [
            'title' => 'Closing banner',
            'help' => 'The banner at the bottom. The first entry sets the heading and background photo; every entry adds one button.',
            'fields' => ['title' => 'Heading (first entry only)', 'image' => 'Background photo (first entry only)', 'button_text' => 'Button text', 'button_url' => 'Button link'],
            'hide' => ['subtitle', 'description', 'icon', 'price', 'features'],
        ],

        // ---- Yoga Reset -----------------------------------------------------
        'yoga_hero' => [
            'title' => 'Top banner',
            'help' => 'The large photo and headline at the top of the Yoga Reset page.',
            'fields' => ['subtitle' => 'Small label above the heading', 'title' => 'Heading', 'description' => 'Intro text', 'image' => 'Banner photo', 'button_text' => 'Button text', 'button_url' => 'Button link'],
            'hide' => ['icon', 'price', 'features'],
        ],
        'yoga_stats' => [
            'title' => 'Quick facts bar',
            'help' => 'The white bar that overlaps the bottom of the banner. One entry per fact (for example "60 min" with the label "Per class").',
            'fields' => ['title' => 'Big text', 'subtitle' => 'Small label', 'icon' => 'Icon'],
            'hints' => ['icon' => 'An emoji (🧘) or an icon name: sunset, clock, lotus, cliff, calendar, leaf.'],
            'hide' => ['description', 'image', 'price', 'features', 'button_text', 'button_url'],
        ],
        'yoga_intro' => [
            'title' => 'Introduction',
            'help' => 'The heading and intro paragraph above the three session cards.',
            'fields' => ['subtitle' => 'Small label above the heading', 'title' => 'Heading', 'description' => 'Intro paragraph'],
            'hide' => ['icon', 'image', 'price', 'features', 'button_text', 'button_url'],
        ],
        'yoga_types' => [
            'title' => 'Session cards',
            'help' => 'One entry per card (Sunrise Flow, Sunset Restore, Private Sessions). Replace the photo to change the card image.',
            'fields' => ['title' => 'Card title', 'subtitle' => 'Details (separate with ·)', 'description' => 'Short description', 'image' => 'Card photo', 'icon' => 'Icon', 'button_text' => 'Button text', 'button_url' => 'Button link'],
            'hints' => ['icon' => 'An emoji (🧘) or an icon name: lotus.', 'subtitle' => 'Shown as small tags, for example: 6:00 AM · 60 min · All levels'],
            'hide' => ['price', 'features'],
        ],
        'yoga_schedule_header' => [
            'title' => 'Daily rhythm heading',
            'help' => 'The small label and heading above the daily timeline.',
            'fields' => ['subtitle' => 'Small label above the heading', 'title' => 'Heading'],
            'hide' => ['description', 'icon', 'image', 'price', 'features', 'button_text', 'button_url'],
        ],
        'yoga_schedule' => [
            'title' => 'Daily rhythm',
            'help' => 'One entry per time slot in the timeline, in the order shown.',
            'fields' => ['title' => 'Time (for example 6:00 AM)', 'subtitle' => 'Session name', 'description' => 'Short description'],
            'hide' => ['icon', 'image', 'price', 'features', 'button_text', 'button_url'],
        ],
        'yoga_features_header' => [
            'title' => 'Highlights heading',
            'help' => 'The heading of the dark highlights strip.',
            'fields' => ['title' => 'Heading'],
            'hide' => ['icon', 'subtitle', 'description', 'image', 'price', 'features', 'button_text', 'button_url'],
        ],
        'yoga_features' => [
            'title' => 'Highlights',
            'help' => 'One entry per highlight in the dark strip (Cliffside Deck, Expert Teachers, and so on).',
            'fields' => ['title' => 'Highlight title', 'description' => 'Short description', 'icon' => 'Icon'],
            'hints' => ['icon' => 'An emoji (🌴) or an icon name: cliff, cloche, clipboard, island.'],
            'hide' => ['subtitle', 'image', 'price', 'features', 'button_text', 'button_url'],
        ],
        'yoga_personal' => [
            'title' => '"Your pace" section',
            'help' => 'The photo and text block beside it, under the highlights strip.',
            'fields' => ['subtitle' => 'Small label above the heading', 'title' => 'Heading', 'description' => 'Paragraph', 'image' => 'Photo', 'button_text' => 'Button text', 'button_url' => 'Button link'],
            'hide' => ['icon', 'price', 'features'],
        ],
        'yoga_quote' => [
            'title' => 'Guest quote',
            'help' => 'One short guest comment shown above the closing banner. Replace the placeholder with a real guest review.',
            'fields' => ['title' => 'Guest name', 'subtitle' => 'Where from', 'description' => 'Quote'],
            'hide' => ['icon', 'image', 'price', 'features', 'button_text', 'button_url'],
        ],
        'yoga_cta' => [
            'title' => 'Closing banner',
            'help' => 'The banner at the bottom. The first entry sets the heading and background photo; every entry adds one button.',
            'fields' => ['title' => 'Heading (first entry only)', 'image' => 'Background photo (first entry only)', 'button_text' => 'Button text', 'button_url' => 'Button link'],
            'hide' => ['subtitle', 'description', 'icon', 'price', 'features'],
        ],

        // ---- Journal (articles) -----------------------------------------------
        'journal_header' => [
            'title' => 'Page heading and wording',
            'help' => 'The heading and tagline at the top of the Journal page, and the small wording around the articles. Leave a field empty to keep the standard wording.',
            'fields' => ['title' => 'Heading', 'description' => 'Tagline under the heading', 'button_text' => 'Link text on each article card', 'subtitle' => 'Heading of the other articles at the bottom of an article'],
            'hints' => [
                'button_text' => 'Standard: Read Article',
                'subtitle' => 'Standard: More Stories',
            ],
            'hide' => ['image', 'icon', 'price', 'features', 'button_url'],
        ],
        'journal_articles' => [
            'title' => 'Articles',
            'help' => 'One entry per article, in the order shown on the Journal page (use the position number to move one up or down; newest first is usual). Each article gets its own page. Switch an entry off to hide it without deleting it. Add more photos to an article under "Journal: article photos".',
            'fields' => ['title' => 'Article title', 'subtitle' => 'Date (for example August 06, 2026)', 'image' => 'Main photo', 'button_text' => 'Short text on the Journal page (optional)', 'description' => 'Article text'],
            'hints' => [
                'description' => 'Write the whole article here. Leave an empty line between paragraphs. Formatting: start a line with ## for a heading ### for a smaller heading or #### for an even smaller one (for example: ## Getting there); start lines with - for a bullet list; put **two stars** around words to make them bold.',
                'image' => 'A landscape photo (about 1600 x 1000 px). It is the picture on the article card and at the top of the article.',
                'button_text' => 'The couple of lines shown on the card. Leave empty to use the first lines of the article text.',
            ],
            'hide' => ['icon', 'price', 'features', 'button_url'],
        ],
        'journal_photos' => [
            'title' => 'Article photos',
            'help' => 'Extra photos shown in a gallery under an article. Add one entry per photo, choose the article it belongs to, and upload the photo. Photos appear in the order of the position number.',
            'fields' => ['title' => 'Caption (optional)', 'subtitle' => 'Article', 'image' => 'Photo'],
            'hints' => ['image' => 'A landscape photo (about 1600 x 1000 px).'],
            'select_source' => 'journal_articles',
            'hide' => ['description', 'icon', 'price', 'features', 'button_text', 'button_url'],
        ],

        // ---- Reviews --------------------------------------------------------
        'reviews_header' => [
            'title' => 'Page heading',
            'help' => 'The small label, heading and tagline at the top of the Reviews page. The round "+" button under the avatars uses the button text and link.',
            'fields' => ['subtitle' => 'Small label above the heading', 'title' => 'Heading', 'description' => 'Tagline under the heading', 'image' => 'Page background photo (optional)', 'icon' => 'Round "+" button icon (image)', 'price' => 'Change review automatically every (seconds)', 'button_text' => 'Round "+" button label', 'button_url' => 'Round "+" button link'],
            'hints' => [
                'button_url' => 'A page such as /#contact, or a full address starting with https:// (for example your Google or TripAdvisor reviews page). Clear the button text to hide the "+" button.',
                'image' => 'Shown very faintly behind the whole page. Leave empty for the plain cream background.',
                'icon' => 'Upload a square PNG or SVG. Leave empty for the built-in plus sign.',
                'price' => 'Leave empty for 7 seconds. Type 0 to stop the automatic change.',
            ],
            'hide' => ['features'],
        ],
        'reviews_items' => [
            'title' => 'Guest reviews',
            'help' => 'One entry per review card, in the order shown. The first entry opens in the middle. Add an entry to add a review.',
            'fields' => ['title' => 'Guest name (for example Sophie M.)', 'subtitle' => 'Town or country', 'description' => 'Review text', 'image' => 'Photo on the card', 'icon' => 'Guest photo (round picture under the cards)', 'price' => 'Stars (1 to 5)'],
            'hints' => ['icon' => 'Upload a small square picture. Without one, the first letter of the name is shown.', 'price' => 'Leave empty for 5 stars.'],
            'hide' => ['features', 'button_text', 'button_url'],
        ],
        'reviews_stats' => [
            'title' => 'Figures',
            'help' => 'The three figures under the reviews. The digits spin like a slot machine when the figure comes into view; other characters (. , / + %) stay still.',
            'fields' => ['title' => 'Figure (for example 4.9 / 5, 500+ or 98 %)', 'subtitle' => 'Label under the figure'],
            'hide' => ['description', 'image', 'icon', 'price', 'features', 'button_text', 'button_url'],
        ],

        'reviews_icons' => [
            'title' => 'Icons',
            'help' => 'The small icons on the Reviews page, in order: 1 Star (shown five times on every review), 2 Previous arrow, 3 Next arrow. Upload an image (PNG or SVG) under Icon to replace one; leave it empty to keep the built-in drawing. The line design and its colour are under Site-wide > Line design.',
            'fields' => ['title' => 'Label (for your reference)', 'icon' => 'Icon image'],
            'hints' => ['icon' => 'Upload a square PNG or SVG, ideally gold on a transparent background. Leave empty to keep the built-in icon.'],
            'hide' => ['subtitle', 'description', 'image', 'price', 'features', 'button_text', 'button_url'],
        ],

        // ---- AI chat assistant and counter-offer popup -------------------------
        'ai_chat' => [
            'title' => 'AI chat assistant',
            'help' => 'The chat bubble at the bottom corner of every page. The AI brain is where you tell the assistant what to know and how to behave: write it like instructions to a new front-desk staff member (rooms, policies, check-in times, tone, what to say about bookings). The villa prices on the website and the active counter offer are added automatically. To switch the chat off, turn this entry off. It works best with a free Gemini key in the .env file on the server (GEMINI_API_KEY, see AI_PROVIDER). Without any key the chat still works from the quick answers (Quick answers section), at no cost.',
            'fields' => [
                'title' => 'Assistant name',
                'subtitle' => 'Greeting shown when the chat opens',
                'description' => 'AI brain (instructions and knowledge)',
                'features' => 'Suggested questions (one per line)',
                'button_text' => 'Chat button label',
                'button_url' => 'Booking / contact link the assistant gives guests',
                'price' => 'Messages allowed per visitor per day',
                'icon' => 'Chat button icon (image)',
                'image' => 'Assistant photo (optional)',
            ],
            'hints' => [
                'description' => 'The assistant only knows what is written here, plus the prices on the Villas page. Anything missing, it will honestly say it does not know. Update it whenever rates, policies or services change. You can write in English, Bisaya or Tagalog.',
                'features' => 'Up to 5 short questions guests can tap to start. Leave empty for none.',
                'button_url' => 'For example /#contact. The assistant sends guests here to book or ask the team.',
                'price' => 'Keeps the AI cost under control. Leave empty for 30.',
                'icon' => 'Upload a small square PNG or SVG. Leave empty for the built-in speech bubble.',
            ],
            'rows' => ['description' => 20],
            'hide' => [],
        ],
        'chat_faq' => [
            'title' => 'Chat quick answers',
            'help' => 'Free answers that need no AI. When a guest\'s message contains one of an entry\'s keywords, the chat replies with its answer straight away. With a free AI key set, these also act as a backup when the AI is busy, and the AI knows them too. One entry per question. Check the prices and facts, and add the questions your guests ask most.',
            'fields' => ['title' => 'Question (for your reference)', 'description' => 'Answer shown to the guest', 'features' => 'Keywords (one per line)'],
            'hints' => [
                'features' => 'Words or short phrases a guest might type, in English, Bisaya or Tagalog, for example: price, how much, magkano. The entry with the most matching keywords answers.',
                'description' => 'Write it as you would reply to a guest. You can include a page such as /villas or /#contact and it becomes a link.',
            ],
            'rows' => ['description' => 6],
            'hide' => ['subtitle', 'image', 'icon', 'price', 'button_text', 'button_url'],
        ],
        'offer_popup' => [
            'title' => 'Counter-offer popup',
            'help' => 'The offer shown to a visitor who has spent some time on the website and is about to leave (see "Offer popup: rules" for the timing). Turn this entry on to start showing it; it is off until you do. To preview it, add ?preview-offer to the end of any page address, for example casa-verde.test/?preview-offer.',
            'fields' => [
                'subtitle' => 'Small label above the headline',
                'title' => 'Headline',
                'description' => 'Offer text',
                'features' => 'Offer code (first line only)',
                'image' => 'Photo (optional)',
                'button_text' => 'Button text',
                'button_url' => 'Button link',
            ],
            'hints' => [
                'features' => 'The code visitors can copy and mention when they enquire, for example CASAVERDE10. Leave empty for no code. The assistant also mentions this offer in the chat.',
                'button_url' => 'For example /#contact, or a full address starting with https://',
            ],
            'hide' => ['icon', 'price'],
        ],
        'offer_popup_rules' => [
            'title' => 'Offer popup: rules',
            'help' => 'Four settings, in this order: 1 minimum time on the website, 2 days before showing again to the same visitor, 3 show when the visitor comes back to the tab, 4 show when the mouse moves up to leave. Change the number in the Value box of each entry and keep the entries in this order.',
            'fields' => ['title' => 'Setting (for your reference)', 'price' => 'Value'],
            'hints' => ['price' => 'A number. For the two yes/no settings use 1 for yes and 0 for no.'],
            'hide' => ['subtitle', 'description', 'image', 'icon', 'features', 'button_text', 'button_url'],
        ],

        // ---- Special offers -------------------------------------------------
        'offers_header' => [
            'title' => 'Page heading',
            'help' => 'The heading and intro at the top of the Special Offers page.',
            'fields' => ['subtitle' => 'Small label above the heading', 'title' => 'Heading', 'description' => 'Intro paragraph'],
            'hide' => ['icon', 'image', 'price', 'features', 'button_text', 'button_url'],
        ],
        'offers' => [
            'title' => 'Offer cards',
            'help' => 'One entry per offer card. Add a new entry to add a card; the layout adjusts automatically.',
            'fields' => [
                'title' => 'Offer name',
                'subtitle' => 'Highlight',
                'description' => 'Short description',
                'icon' => 'Card icon (big, beside the title)',
                'perk_icon' => 'Small icon (beside the highlight line)',
                'button_text' => 'Button text',
                'button_url' => 'Button link',
            ],
            'hints' => [
                'subtitle' => 'Line 1 is shown in bold (e.g. "1 NIGHT FREE"). Line 2 is the smaller text next to it.',
                'icon' => 'Upload an image, or type an emoji or an icon name (island, lotus, heart, leaf, stones, spaset, villa...). Clear it to use the original drawing.',
                'perk_icon' => 'Upload an image, or type an emoji or an icon name (tag, lotus, heart, leaf...). Clear it to use the original drawing.',
            ],
            'hide' => ['price', 'features'],
        ],
        'offers_note' => [
            'title' => 'Closing note',
            'help' => 'The small line shown below the offer cards.',
            'fields' => ['description' => 'Note'],
            'hide' => ['title', 'subtitle', 'image', 'icon', 'price', 'features', 'button_text', 'button_url'],
        ],

        // ---- Gallery --------------------------------------------------------
        'gallery_header' => [
            'title' => 'Page heading',
            'help' => 'The heading and intro at the top of the Gallery page.',
            'fields' => ['subtitle' => 'Small label above the heading', 'title' => 'Heading', 'description' => 'Intro paragraph'],
            'hide' => ['image', 'icon', 'price', 'features', 'button_text', 'button_url'],
        ],
        'gallery_overview' => [
            'title' => 'Overview mosaic',
            'help' => 'The 7 tiles at the top of the Gallery, in order: Sunsets, Villas, Wellness, Dining, Resort, Resort, Experience. Replace the photo or rename the label; each tile keeps its category.',
            'fields' => ['title' => 'Label on the tile', 'image' => 'Tile photo'],
            'hide' => ['subtitle', 'description', 'icon', 'price', 'features', 'button_text', 'button_url'],
        ],
        'gallery_headings' => [
            'title' => 'Category headings',
            'help' => 'Titles and intro lines of the 6 gallery categories, in order: Resort, Villas, Dining, Wellness, Experiences, Sunsets.',
            'fields' => ['title' => 'Category title', 'subtitle' => 'Intro line'],
            'hide' => ['description', 'image', 'icon', 'price', 'features', 'button_text', 'button_url'],
        ],
        'gallery_resort' => $galleryPhotos('Photos: The Resort'),
        'gallery_villas' => $galleryPhotos('Photos: Villas'),
        'gallery_dining' => $galleryPhotos('Photos: Dining'),
        'gallery_wellness' => $galleryPhotos('Photos: Wellness'),
        'gallery_experiences' => [
            'title' => 'Photos: Experiences',
            'help' => 'One entry per photo. Add a label to show a name and icon under the photo.',
            'fields' => ['title' => 'Caption', 'image' => 'Photo', 'subtitle' => 'Label under the photo', 'icon' => 'Label icon'],
            'hints' => [
                'title' => 'Describes the photo for visitors who cannot see it, and appears in the enlarged view.',
                'icon' => 'Use: cave, snorkel, padel, island, leaf, sunset.',
            ],
            'hide' => ['description', 'price', 'features', 'button_text', 'button_url'],
        ],
        'gallery_sunsets' => $galleryPhotos('Photos: Sunsets'),

        // ---- Winterresidenz page (/winterresidenz) -----------------------------
        'winter' => [
            'title' => 'Winterresidenz: Heading',
            'help' => 'The heading at the top of the Winterresidenz page, above the tabs. Turn this entry off to hide the whole page, its menu link and its Long Stay banner.',
            'fields' => ['title' => 'Heading', 'subtitle' => 'Small line above the heading', 'description' => 'Sentence under the heading', 'button_text' => 'Menu link text'],
            'hints' => ['button_text' => 'The page link in the header dropdown under Long Stay. Empty uses "Winterresidenz".'],
            'hide' => ['image', 'icon', 'price', 'features', 'button_url'],
        ],
        'winter_tabs' => [
            'title' => 'Winterresidenz: Tabs',
            'help' => 'One entry per tab, in order (the position number is the tab number). The tab button shows the Tab name; the panel shows the Heading, the Text (a blank line starts a new paragraph), an optional Photo on the right, the check-marked lines and a closing sentence. Photo cards, lists, prices, icon badges and contact lines are added in the other "Winterresidenz" lists and attach to a tab through their Tab field.',
            'fields' => [
                'title' => 'Tab name',
                'subtitle' => 'Heading',
                'description' => 'Text',
                'image' => 'Photo (optional)',
                'features' => 'Check-marked lines',
                'button_text' => 'Closing sentence',
            ],
            'hints' => [
                'description' => 'A blank line starts a new paragraph. On a tab with photo cards, a line with only --- splits the text: what comes before it shows above the photos, what comes after shows below them.',
                'features' => 'One line per check mark (✓).',
                'button_text' => 'Shown in bold gold beside the photo, or in a framed bar when the tab has no photo.',
            ],
            'hide' => ['icon', 'price', 'button_url'],
        ],
        'winter_cards' => [
            'title' => 'Winterresidenz: Photo cards',
            'help' => 'Photos with an optional label and sentence, shown in a row inside a tab. Set Tab to the number of the tab they belong to (for example 2) or to its name.',
            'fields' => ['title' => 'Label (optional)', 'subtitle' => 'Tab', 'description' => 'Sentence (optional)', 'image' => 'Photo'],
            'hints' => ['subtitle' => 'Choose the tab this belongs to. The numbers are the positions of the tabs in Winterresidenz: Tabs.'],
            'select_source' => 'winter_tabs',
            'hide' => ['icon', 'price', 'features', 'button_text', 'button_url'],
        ],
        'winter_lists' => [
            'title' => 'Winterresidenz: Lists',
            'help' => 'Checklists shown side by side inside a tab (for example "Im Aufenthalt inklusive" and "Optionale Services"). Set Tab to the number of the tab they belong to.',
            'fields' => ['title' => 'List heading', 'subtitle' => 'Tab', 'icon' => 'Heading icon', 'features' => 'List lines', 'button_text' => 'Symbol before each line'],
            'hints' => [
                'subtitle' => 'Choose the tab this belongs to. The numbers are the positions of the tabs in Winterresidenz: Tabs.',
                'icon' => 'An icon name (villa, cloche, leaf...) or upload your own image.',
                'features' => 'One line per entry.',
                'button_text' => 'For example ✓ or +. Empty uses ✓.',
            ],
            'select_source' => 'winter_tabs',
            'hide' => ['description', 'image', 'price', 'button_url'],
        ],
        'winter_prices' => [
            'title' => 'Winterresidenz: Prices',
            'help' => 'Price cards inside a tab. Set Tab to the number of the tab they belong to. The amount is shown with thousands dots, for example 2.899.',
            'fields' => ['title' => 'Name (e.g. Einzelresidenz)', 'subtitle' => 'Tab', 'price' => 'Amount', 'button_text' => 'Wording around the amount', 'description' => 'Small note under the price'],
            'hints' => [
                'subtitle' => 'Choose the tab this belongs to. The numbers are the positions of the tabs in Winterresidenz: Tabs.',
                'button_text' => 'Write {price} where the amount goes, e.g. "ab {price} € pro Monat". Empty uses that wording.',
            ],
            'select_source' => 'winter_tabs',
            'hide' => ['image', 'icon', 'features', 'button_url'],
        ],
        'winter_badges' => [
            'title' => 'Winterresidenz: Icon badges',
            'help' => 'Icons in a row under a tab. With only a Caption they are small icons with a label (for example Schnelles WLAN). With a Description too they become the framed feature strip of the first tab. Set Tab to the number of the tab they belong to.',
            'fields' => ['title' => 'Caption', 'subtitle' => 'Tab', 'icon' => 'Icon', 'description' => 'Description (optional)'],
            'hints' => [
                'subtitle' => 'Choose the tab this belongs to. The numbers are the positions of the tabs in Winterresidenz: Tabs.',
                'icon' => 'An icon name (wifi, island, coffee, laptop, lotus, shield, tropical-home, cloche, hands...) or upload your own image.',
            ],
            'select_source' => 'winter_tabs',
            'hide' => ['image', 'price', 'features', 'button_text', 'button_url'],
        ],
        'winter_contact' => [
            'title' => 'Winterresidenz: Contact lines',
            'help' => 'Contact lines at the end of a tab (WhatsApp, email, website). Set Tab to the number of the tab they belong to. An entry without an icon is shown as a small heading above the lines.',
            'fields' => ['title' => 'Text shown', 'subtitle' => 'Tab', 'icon' => 'Icon', 'button_url' => 'Link (optional)'],
            'hints' => [
                'subtitle' => 'Choose the tab this belongs to. The numbers are the positions of the tabs in Winterresidenz: Tabs.',
                'icon' => 'whatsapp, mail, phone, globe or pin. Leave empty for a heading.',
                'button_url' => 'e.g. https://wa.me/639171364931 or mailto:you@example.com',
            ],
            'select_source' => 'winter_tabs',
            'hide' => ['description', 'image', 'price', 'features', 'button_text'],
        ],

        'long_stay_winter' => [
            'title' => 'Long stay: Winterresidenz banner',
            'help' => 'The banner on the Long Stay page that leads to the Winterresidenz page. Turn it off to hide the banner.',
            'fields' => ['title' => 'Heading', 'subtitle' => 'Small line above the heading', 'description' => 'Sentence', 'image' => 'Photo', 'button_text' => 'Button text', 'button_url' => 'Button link'],
            'hide' => ['icon', 'price', 'features'],
        ],

        // ---- Footer ---------------------------------------------------------
        'footer_cta' => [
            'title' => 'Call-to-action banner',
            'help' => 'The "Ready to experience Casa Verde?" banner above the footer. Turn it off to hide it.',
            'fields' => ['title' => 'Heading', 'button_text' => 'Button text', 'button_url' => 'Button link'],
            'hide' => ['subtitle', 'description', 'image', 'icon', 'price', 'features'],
        ],
        'footer_brand' => [
            'title' => 'Brand block',
            'help' => 'The name and tagline shown beside the logo in the footer.',
            'fields' => ['title' => 'Resort name', 'subtitle' => 'Tagline / location'],
            'hide' => ['description', 'image', 'icon', 'price', 'features', 'button_text', 'button_url'],
        ],
        'footer_explore' => [
            'title' => 'Explore links',
            'help' => 'Links in the "Explore" column. Add an entry to add a link; turn one off to hide it.',
            'fields' => ['title' => 'Link text', 'button_url' => 'Link address'],
            'hints' => ['button_url' => 'A page such as /villas, a section such as /#contact, or a full address starting with https://.'],
            'hide' => ['subtitle', 'description', 'image', 'icon', 'price', 'features', 'button_text'],
        ],
        'footer_links' => [
            'title' => 'Useful links',
            'help' => 'Links in the "Useful Links" column.',
            'fields' => ['title' => 'Link text', 'button_url' => 'Link address'],
            'hints' => ['button_url' => 'A page such as /gallery, a section such as /#contact, or a full address starting with https://.'],
            'hide' => ['subtitle', 'description', 'image', 'icon', 'price', 'features', 'button_text'],
        ],
        'footer_contact' => [
            'title' => 'Contact details',
            'help' => 'Address, phone and email shown in the footer.',
            'fields' => ['title' => 'Text shown', 'icon' => 'Icon', 'button_url' => 'Link (optional)'],
            'hints' => [
                'icon' => 'Use: pin (address), whatsapp, phone or mail.',
                'button_url' => 'e.g. https://wa.me/639622558380 or mailto:you@example.com. Leave empty for plain text.',
            ],
            'hide' => ['subtitle', 'description', 'image', 'price', 'features', 'button_text'],
        ],
        'footer_social' => [
            'title' => 'Social links',
            'help' => 'The social media icons in the footer.',
            'fields' => ['title' => 'Name (for screen readers)', 'icon' => 'Icon', 'button_url' => 'Profile address'],
            'hints' => [
                'icon' => 'Leave as it is for the built-in dark icon, or type: instagram, facebook, tiktok or youtube. To use your own, upload a dark green or black PNG/SVG, because it sits on a gold circle.',
                'button_url' => 'Full address, e.g. https://instagram.com/yourpage',
            ],
            'hide' => ['subtitle', 'description', 'image', 'price', 'features', 'button_text'],
        ],
        'footer_newsletter' => [
            'title' => 'Newsletter text',
            'help' => 'The heading and sentence above the email sign-up box.',
            'fields' => ['title' => 'Heading', 'description' => 'Sentence'],
            'hide' => ['subtitle', 'image', 'icon', 'price', 'features', 'button_text', 'button_url'],
        ],
        'footer_legal' => [
            'title' => 'Legal links',
            'help' => 'Privacy Policy, Terms and similar links at the very bottom.',
            'fields' => ['title' => 'Link text', 'button_url' => 'Link address'],
            'hide' => ['subtitle', 'description', 'image', 'icon', 'price', 'features', 'button_text'],
        ],
        'footer_bottom' => [
            'title' => 'Copyright line',
            'help' => 'Shown after "© year". The year updates by itself.',
            'fields' => ['title' => 'Text'],
            'hide' => ['subtitle', 'description', 'image', 'icon', 'price', 'features', 'button_text', 'button_url'],
        ],
    ],

];
