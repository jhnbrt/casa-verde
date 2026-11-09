<?php

use App\Models\HomeContent;
use Database\Seeders\YogaContentSeeder;
use Illuminate\Database\Migrations\Migration;

/**
 * The redesigned Yoga Reset page adds a quick facts bar, session details, a daily
 * timeline and a guest quote. This loads that content into the admin. Anything
 * already edited is left alone.
 */
return new class extends Migration
{
    public function up(): void
    {
        app(YogaContentSeeder::class)->run();

        // Session cards created before the redesign have no details line yet.
        foreach (config('yoga.types') as $index => $type) {
            HomeContent::where('section', 'yoga_types')
                ->where('sort_order', $index + 1)
                ->where('title', $type['title'])
                ->whereNull('subtitle')
                ->update(['subtitle' => $type['eyebrow']]);
        }
    }

    public function down(): void
    {
        // Intentionally empty: removing content is not something to undo silently.
    }
};
