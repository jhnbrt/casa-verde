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
*/

return [

    'content_groups' => [
        'site' => ['label' => 'Site-wide', 'match' => ['site', 'navigation']],
        'home' => ['label' => 'Home', 'match' => ['hero', 'features']],
        'villas' => ['label' => 'Villas', 'match' => ['villas']],
        'experiences' => ['label' => 'Experiences', 'match' => ['experiences']],
        'wellness' => ['label' => 'Wellness', 'match' => ['wellness']],
        'dining' => ['label' => 'Dining', 'match' => ['dining']],
        'long_stay' => ['label' => 'Long stay', 'match' => ['long_stay']],
        'about' => ['label' => 'About', 'match' => ['about']],
        'contact' => ['label' => 'Contact', 'match' => ['contact']],
    ],

];
