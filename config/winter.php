<?php

/*
|--------------------------------------------------------------------------
| Winterresidenz page defaults
|--------------------------------------------------------------------------
|
| German long-stay offer shown on /winterresidenz as six tabs. Each tab can have its
| own photo cards, lists, prices, icon badges and contact lines; they are linked by the
| tab number ("panel"). The migration loads this into the admin (Winterresidenz) for
| editing; it is used as is until then.
|
*/

return [

    'header' => [
        'eyebrow' => 'Langzeitaufenthalt · 3–5 Monate',
        'title' => 'Winterresidenz auf Camotes Island',
        'menu' => 'Winterresidenz',
        'text' => 'Viele unserer Gäste möchten dem kalten Winter entfliehen, ohne auf Komfort, Struktur und Verlässlichkeit zu verzichten.',
    ],

    'tabs' => [
        [
            'key' => 1,
            'label' => 'Willkommen',
            'heading' => 'Den Winter neu erleben – sorgenfrei unter Palmen',
            'text' => "Exklusive Winterresidenz auf Camotes Island, Philippinen\n3–5 Monate Lebensqualität statt Kälte",
            'image' => null,
            'ticks' => [],
            'closing' => 'Ihr Winter. Neu definiert.',
        ],
        [
            'key' => 2,
            'label' => 'Überblick',
            'heading' => 'Mehr als ein Aufenthalt – ein Zuhause für den Winter',
            'text' => "Wir haben diesen Ort mit einer klaren Idee geschaffen:\nEinen Platz, an dem man sich auch fern von zuhause sicher, wohl und verstanden fühlt.\n\nViele unserer Gäste möchten dem kalten Winter entfliehen ohne auf Komfort, Struktur und Verlässlichkeit zu verzichten.\n\nGenau dafür haben wir unsere Winterresidenz entwickelt.\n\nBei uns finden Sie nicht nur eine Unterkunft, sondern ein Umfeld, das Ihnen Ruhe, Lebensqualität und auf Wunsch auch Unterstützung bietet.\n\nUnser Ziel ist es, Ihnen den Aufenthalt so einfach wie möglich zu machen damit Sie sich ganz auf das konzentrieren können, was wirklich zählt:",
            'image' => 'images/winter/winter-overview-aerial.jpg',
            'ticks' => ['persönliche Betreuung', 'klare Strukturen', 'Erfahrung mit internationalen Gästen', 'Unterstützung bei allen organisatorischen Themen'],
            'closing' => 'Ihr Wohlbefinden.',
        ],
        [
            'key' => 3,
            'label' => 'Ihr Aufenthalt',
            'heading' => 'Mehr als Urlaub – ein Leben für den Winter',
            'text' => 'Ihr Aufenthalt – klar strukturiert und vollständig für Sie organisiert',
            'image' => null,
            'ticks' => ['3–5 Monate Aufenthalt', 'Voll organisiert', 'Gemeinschaft & Privatsphäre', 'Betreuung optional'],
            'closing' => null,
        ],
        [
            'key' => 4,
            'label' => 'Alltag',
            'heading' => 'So fühlt sich Ihr Winter bei uns an',
            'text' => 'Ein Alltag zwischen Ruhe, Gemeinschaft und Lebensqualität',
            'image' => null,
            'ticks' => [],
            'closing' => 'Ein Alltag, der sich nach Ihnen richtet – nicht umgekehrt.',
        ],
        [
            'key' => 5,
            'label' => 'Camotes Island',
            'heading' => 'Warum Camotes Island?',
            'text' => "Ein ruhiger, sicherer Ort fernab vom Massentourismus – ideal für einen entspannten und sorgenfreien Aufenthalt.\n\n---\n\nCamotes Island gehört zu den ruhigeren, ursprünglichen Inseln der Philippinen.\nFernab vom Trubel großer Touristenzentren finden Sie hier eine sichere und entspannte Umgebung.\nWeiße Strände, klares Wasser und beeindruckende Natur schaffen den perfekten Rahmen für einen erholsamen Aufenthalt – ganz ohne Hektik.\nGleichzeitig profitieren Sie von einer guten Anbindung an Cebu und medizinischer Versorgung in erreichbarer Nähe.",
            'image' => null,
            'ticks' => ['Ruhig & sicher', 'Kaum Massentourismus', 'Natur & Erholung', 'Gute Erreichbarkeit'],
            'closing' => null,
        ],
        [
            'key' => 6,
            'label' => 'Leistungen',
            'heading' => 'Ihre Langzeit-Aufenthaltsoptionen',
            'text' => null,
            'image' => null,
            'ticks' => [],
            'closing' => 'Einfacher, ruhiger und inspirierender leben und arbeiten.',
        ],
        [
            'key' => 7,
            'label' => 'Preise & Kontakt',
            'heading' => 'Ihr Winter beginnt hier',
            'text' => "Sichern Sie sich Ihren Platz für die kommende Saison auf Camotes Island\n\nVerbringen Sie 3 bis 5 Monate in einer sicheren, komfortablen Umgebung unter Palmen – mit privater Unterkunft, täglicher Verpflegung und auf Wunsch persönlicher Betreuung. Genießen Sie einen Alltag, der für Sie organisiert ist – ruhig, strukturiert und ganz auf Ihre Bedürfnisse abgestimmt.",
            'image' => null,
            'ticks' => ['Deutschsprachige Betreuung möglich', 'Unterstützung bei Visa, Anreise und Organisation', 'Nähe zu medizinischer Versorgung', 'Persönlicher Ansprechpartner vor Ort'],
            'closing' => 'Ein Ort, an dem Sie den Winter neu erleben – in Ruhe, Sicherheit und Lebensqualität.',
        ],
    ],

    // Photo cards (a photo with an optional label and sentence).
    'cards' => [
        ['panel' => 1, 'title' => '', 'text' => '', 'image' => 'images/winter/winter-cover-villa.jpg'],
        ['panel' => 1, 'title' => '', 'text' => '', 'image' => 'images/winter/winter-cover-pool.jpg'],
        ['panel' => 1, 'title' => '', 'text' => '', 'image' => 'images/winter/winter-cover-sunset.jpg'],

        ['panel' => 3, 'title' => 'Ihr Zuhause', 'text' => 'Wohnen Sie in Ihrer eigenen Villa – mit Privatsphäre und allem, was Sie für einen entspannten Alltag benötigen.', 'image' => 'images/winter/winter-villa.jpg'],
        ['panel' => 3, 'title' => 'Alles organisiert', 'text' => 'Verpflegung, Reinigung und tägliche Abläufe sind für Sie vorbereitet – Sie müssen sich um nichts kümmern.', 'image' => 'images/winter/winter-team.jpg'],
        ['panel' => 3, 'title' => 'Leben & genießen', 'text' => 'Nutzen Sie die Zeit für Erholung, Bewegung oder gemeinsame Aktivitäten – ganz nach Ihren Wünschen.', 'image' => 'images/winter/winter-pool-top.jpg'],

        ['panel' => 4, 'title' => 'Zeit für sich', 'text' => 'Genießen Sie Ruhe, Sonne und Ihren eigenen Rhythmus.', 'image' => 'images/winter/winter-relax.jpg'],
        ['panel' => 4, 'title' => 'Morgen / Frühstück', 'text' => 'Beginnen Sie den Morgen mit Blick aufs Meer und frisch zubereitetem Frühstück.', 'image' => 'images/winter/winter-breakfast.jpg'],
        ['panel' => 4, 'title' => 'Gemeinschaft erleben', 'text' => 'Treffen Sie Gleichgesinnte – ganz ungezwungen und nach Wunsch.', 'image' => 'images/winter/winter-lounge.jpg'],
        ['panel' => 4, 'title' => 'Aktiv bleiben', 'text' => 'Bleiben Sie in Bewegung – mit Angeboten, die zu Ihnen passen.', 'image' => 'images/winter/winter-padel.jpg'],

        ['panel' => 5, 'title' => '', 'text' => '', 'image' => 'images/winter/winter-camotes-cave.jpg'],
        ['panel' => 5, 'title' => '', 'text' => '', 'image' => 'images/winter/winter-camotes-sandbar.jpg'],
        ['panel' => 5, 'title' => '', 'text' => '', 'image' => 'images/winter/winter-camotes-beach.jpg'],
        ['panel' => 5, 'title' => '', 'text' => '', 'image' => 'images/winter/winter-camotes-resort.jpg'],
    ],

    // Two-column checklists ("marker" is the symbol before every line).
    'lists' => [
        [
            'panel' => 6, 'title' => 'Im Aufenthalt inklusive', 'icon' => 'villa', 'marker' => '✓',
            'items' => ['Private Villa', 'High-Speed Internet', 'Wöchentliche Reinigung', 'Nebenkosten inklusive', 'Zugang zu Resort-Einrichtungen', 'Ruhige tropische Umgebung', 'Langzeit-Gäste Support', 'Tägliches Frühstück', 'Eigene Workspace-Optionen', 'Zuverlässige Stromversorgung (Backup)', 'Ruhige Arbeits- und Wohnumgebung', 'Zugang zu Pool & Fitnessbereichen', 'Wöchentlicher Wäsche- & Handtuchservice', 'Schnelles WLAN im gesamten Resort', 'Community-Events & gesellige Abende', 'Persönliche lokale Empfehlungen'],
        ],
        [
            'panel' => 6, 'title' => 'Optionale Services', 'icon' => 'cloche', 'marker' => '+',
            'items' => ['Tägliches Abendessen', 'Flughafentransfer', 'Wäscheservice', 'Persönliche Unterstützung', 'Rollervermietung', 'Geführte Inselaktivitäten', 'Wellness & Massagen', 'Erweiterter Reinigungsservice', 'Gesunde Mahlzeitenpläne', 'Private Insel-Touren', 'Fitness-Training / Coaching', 'Unterstützung bei Visum-Verlängerung', 'Einkaufsservice (Lebensmittel & mehr)', 'Motorroller-Lieferservice', 'Massage & Regenerationsbehandlungen', 'Private Bootsausflüge'],
        ],
    ],

    // Price cards. {price} in "format" is replaced by the amount.
    'prices' => [
        ['panel' => 7, 'title' => 'Einzelresidenz', 'price' => 2899, 'format' => 'ab {price} € pro Monat', 'note' => '(je nach Unterkunft und gewünschtem Serviceumfang)'],
        ['panel' => 7, 'title' => 'Paarresidenz', 'price' => 5499, 'format' => 'ab {price} € pro Monat', 'note' => '(je nach Unterkunft und gewünschtem Serviceumfang)'],
    ],

    // Small icon + caption badges.
    'badges' => [
        ['panel' => 1, 'title' => 'Eigene Villa', 'icon' => 'tropical-home', 'text' => 'Ihr privater Rückzugsort mit Komfort und Privatsphäre'],
        ['panel' => 1, 'title' => 'Verpflegung inklusive', 'icon' => 'cloche', 'text' => 'Frühstück und Abendessen täglich frisch zubereitet'],
        ['panel' => 1, 'title' => 'Betreuung optional', 'icon' => 'hands', 'text' => 'Unterstützung und Pflege wenn Sie sie wünschen'],
        ['panel' => 1, 'title' => '3–5 Monate Aufenthalt', 'icon' => 'island', 'text' => 'Genießen Sie den Winter in Wärme und Sicherheit'],
        ['panel' => 1, 'title' => 'Rundum-Service', 'icon' => 'cloche', 'text' => 'Wir organisieren alles – Sie genießen das Leben'],

        ['panel' => 6, 'title' => 'Schnelles WLAN', 'icon' => 'wifi', 'text' => ''],
        ['panel' => 6, 'title' => 'Tropisches Paradies', 'icon' => 'island', 'text' => ''],
        ['panel' => 6, 'title' => 'Tägliches Frühstück', 'icon' => 'coffee', 'text' => ''],
        ['panel' => 6, 'title' => 'Workspace Optionen', 'icon' => 'laptop', 'text' => ''],
        ['panel' => 6, 'title' => 'Wellness & Entspannung', 'icon' => 'lotus', 'text' => ''],
        ['panel' => 6, 'title' => 'Sicher & zuverlässig', 'icon' => 'shield', 'text' => ''],
    ],

    // Contact lines. A line without an icon is shown as a small heading.
    'contact' => [
        ['panel' => 7, 'icon' => '', 'title' => 'Jetzt unverbindlich beraten lassen', 'url' => ''],
        ['panel' => 7, 'icon' => 'whatsapp', 'title' => 'WhatsApp (direkt & schnell): +63 917 1364931', 'url' => 'https://wa.me/639171364931'],
        ['panel' => 7, 'icon' => 'mail', 'title' => 'Email: metin@casaverdecliffresort.com', 'url' => 'mailto:metin@casaverdecliffresort.com'],
        ['panel' => 7, 'icon' => 'globe', 'title' => 'Website: www.casaverdecliffresort.com', 'url' => 'https://www.casaverdecliffresort.com'],
    ],

    // Banner on the Long Stay page that leads here.
    'teaser' => [
        'eyebrow' => 'Neu · Winterresidenz',
        'title' => 'Winterresidenz für 3–5 Monate',
        'text' => 'Dem kalten Winter entfliehen – in Ihrer eigenen Villa, voll organisiert und auf Wunsch mit persönlicher Betreuung.',
        'image' => 'images/winter/winter-overview-aerial.jpg',
        'button_text' => 'Mehr erfahren',
        'button_url' => '/winterresidenz',
    ],

];
