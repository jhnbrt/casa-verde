<?php

use App\Models\HomeContent;
use Database\Seeders\YogaContentSeeder;
use Illuminate\Database\Migrations\Migration;

/**
 * The redesigned Yoga Reset page adds a questions section, an optional teachers section and a price on each
 * class. This loads the new content into the admin (anything already edited is left alone) and removes the
 * placeholder photos the page used to start with: they were too small for the page and two of them were
 * restaurant photos. Photos uploaded in the admin are never touched.
 */
return new class extends Migration
{
    public function up(): void
    {
        app(YogaContentSeeder::class)->run();

        $placeholders = [
            'images/experiences/hero.jpg',
            'images/wellness4.jpg',
            'images/exp1.jpg',
            'images/dining3.jpg',
        ];

        HomeContent::where('section', 'like', 'yoga\_%')
            ->whereIn('image', $placeholders)
            ->update(['image' => null]);
    }

    public function down(): void
    {
        // Intentionally empty: removing content is not something to undo silently.
    }
};
