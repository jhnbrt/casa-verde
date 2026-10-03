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
        'home' => ['label' => 'Home', 'match' => ['hero', 'features'], 'url' => '/'],
        'villas' => ['label' => 'Villas', 'match' => ['villas'], 'url' => '/villas'],
        'experiences' => ['label' => 'Experiences', 'match' => ['experiences'], 'url' => '/experiences'],
        'wellness' => ['label' => 'Wellness', 'match' => ['wellness'], 'url' => '/wellness'],
        'dining' => ['label' => 'Dining', 'match' => ['dining'], 'url' => '/dining'],
        'long_stay' => ['label' => 'Long stay', 'match' => ['long_stay'], 'url' => '/long-stay'],
        'events' => ['label' => 'Events', 'match' => ['events'], 'url' => '/events'],
        'offers' => ['label' => 'Special offers', 'match' => ['offers'], 'url' => '/special-offers'],
        'gallery' => ['label' => 'Gallery', 'match' => ['gallery'], 'url' => '/gallery'],
        'about' => ['label' => 'About', 'match' => ['about'], 'url' => '/#about'],
        'contact' => ['label' => 'Contact', 'match' => ['contact'], 'url' => '/#contact'],
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
            'hide' => ['image', 'icon', 'price', 'features', 'button_text', 'button_url'],
        ],
        'events_types' => [
            'title' => 'Event cards',
            'help' => 'One entry per card (Weddings, Celebrations, Retreats). Replace the photo to change the card image.',
            'fields' => ['title' => 'Card title', 'description' => 'Short description', 'image' => 'Card photo', 'icon' => 'Icon', 'button_text' => 'Button text', 'button_url' => 'Button link'],
            'hints' => ['icon' => 'Use: rings, cake, lotus.'],
            'hide' => ['subtitle', 'price', 'features'],
        ],
        'events_features_header' => [
            'title' => 'Highlights heading',
            'help' => 'The heading of the dark "Everything, beautifully considered" strip.',
            'fields' => ['title' => 'Heading'],
            'hide' => ['subtitle', 'description', 'image', 'icon', 'price', 'features', 'button_text', 'button_url'],
        ],
        'events_features' => [
            'title' => 'Highlights',
            'help' => 'One entry per highlight in the dark strip (Cliffside Venues, Thoughtful Menus, and so on).',
            'fields' => ['title' => 'Highlight title', 'description' => 'Short description', 'icon' => 'Icon'],
            'hints' => ['icon' => 'Use: cliff, cloche, clipboard, island.'],
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

        // ---- Special offers -------------------------------------------------
        'offers_header' => [
            'title' => 'Page heading',
            'help' => 'The heading and intro at the top of the Special Offers page.',
            'fields' => ['subtitle' => 'Small label above the heading', 'title' => 'Heading', 'description' => 'Intro paragraph'],
            'hide' => ['image', 'icon', 'price', 'features', 'button_text', 'button_url'],
        ],
        'offers' => [
            'title' => 'Offer cards',
            'help' => 'One entry per offer card. Add a new entry to add a card; the layout adjusts automatically.',
            'fields' => [
                'title' => 'Offer name',
                'subtitle' => 'Highlight',
                'description' => 'Short description',
                'icon' => 'Icon (emoji)',
                'button_text' => 'Button text',
                'button_url' => 'Button link',
            ],
            'hints' => ['subtitle' => 'Line 1 is shown in bold (e.g. "1 NIGHT FREE"). Line 2 is the smaller text next to it.'],
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
                'icon' => 'Use: instagram, facebook, tiktok or youtube.',
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
