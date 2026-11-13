<?php

use App\Models\HomeContent;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * The three articles of the old Resort Journal, as far as they were available (title, date, photo and the
     * opening lines shown on the old blog page). Replace the text with the complete article in the admin
     * (Journal > Articles). Nothing is added when the admin already has articles.
     */
    public function up(): void
    {
        if (HomeContent::where('section', 'journal_articles')->exists()) {
            return;
        }

        $articles = [
            [
                'title' => 'The Natural Wonders of Camotes Island',
                'subtitle' => 'August 06, 2026',
                'image' => 'images/journal/natural-wonders-of-camotes-island.jpg',
                'description' => 'Welcome to the Camotes Islands, an untouched tropical escape located east of the Cebu mainland. Often overlooked by main...',
            ],
            [
                'title' => 'Discover Hilom Cave',
                'subtitle' => 'August 05, 2026',
                'image' => 'images/journal/discover-hilom-cave.jpg',
                'description' => 'Nestled within the pristine paradise of Camotes Islands in Cebu, Philippines, lies one of the region’s most spectacular...',
            ],
            [
                'title' => 'The Cliff Lounge Experience',
                'subtitle' => 'August 05, 2026',
                'image' => 'images/journal/the-cliff-lounge-experience.jpg',
                'description' => 'Perched dramatically on the pristine clifftops of Camotes Islands, the Cliff Lounge at Casa Verde Cliff Resort and Spa r...',
            ],
        ];

        foreach ($articles as $index => $article) {
            HomeContent::create($article + ['section' => 'journal_articles', 'sort_order' => $index + 1, 'active' => true]);
        }
    }

    public function down(): void
    {
        // Intentionally empty: removing content is not something to undo silently.
    }
};
