<?php

use App\Models\HomeContent;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Adds the fourth article of the old Resort Journal (title, date, photo and the opening lines shown on the old
     * blog page). Replace the text with the complete article in the admin (Journal > Articles).
     */
    public function up(): void
    {
        $title = 'Sunset Restaurant : Culinary Artistry Meets Paradise';

        if (HomeContent::where('section', 'journal_articles')->whereRaw('lower(trim(title)) = ?', [strtolower($title)])->exists()) {
            return;
        }

        HomeContent::create([
            'section' => 'journal_articles',
            'title' => $title,
            'subtitle' => 'August 05, 2026',
            'image' => 'images/journal/sunset-restaurant-culinary-artistry-meets-paradise.jpg',
            'description' => 'In the pristine paradise of Camotes Islands, where natural beauty reaches its zenith, there exists a culinary sanctuary...',
            'sort_order' => (int) HomeContent::where('section', 'journal_articles')->max('sort_order') + 1,
            'active' => true,
        ]);
    }

    public function down(): void
    {
        // Intentionally empty: removing content is not something to undo silently.
    }
};
