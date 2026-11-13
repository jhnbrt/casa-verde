<?php

use App\Models\HomeContent;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Adds a "Journal" link to the header (it joins Reviews in the "Discover" dropdown) and to the footer's
     * Explore column. Both are added SWITCHED OFF: this site has no /blog page yet, so a visible link would lead
     * nowhere. When the Journal exists, switch them on in the admin (Site-wide > Navigation, and the footer's
     * Explore links) and check the link address.
     */
    public function up(): void
    {
        $this->add('navigation', 'reviews');
        $this->add('footer_explore', 'reviews');
    }

    public function down(): void
    {
        // Intentionally empty: removing content is not something to undo silently.
    }

    protected function add(string $section, string $after): void
    {
        $rows = HomeContent::where('section', $section)->orderBy('sort_order')->get();

        // Never filled in (the footer then uses config/footer.php), or already there.
        if ($rows->isEmpty() || $rows->contains(fn (HomeContent $row): bool => strtolower(trim((string) $row->title)) === 'journal')) {
            return;
        }

        $anchor = $rows->first(fn (HomeContent $row): bool => strtolower(trim((string) $row->title)) === $after);
        $position = $anchor ? $anchor->sort_order + 1 : ($rows->max('sort_order') ?? 0) + 1;

        HomeContent::where('section', $section)->where('sort_order', '>=', $position)->increment('sort_order');

        HomeContent::create([
            'section' => $section,
            'title' => $section === 'navigation' ? 'JOURNAL' : 'Journal',
            'button_url' => '/blog',
            'sort_order' => $position,
            'active' => false,
        ]);
    }
};
