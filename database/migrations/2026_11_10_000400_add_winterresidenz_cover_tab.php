<?php

use App\Models\HomeContent;
use Illuminate\Database\Migrations\Migration;

/**
 * Adds the cover slide ("Den Winter neu erleben – sorgenfrei unter Palmen") as the first
 * tab of the Winterresidenz page. The other tabs move one place to the right, so the tab
 * numbers used by their photo cards, lists, prices, badges and contact lines are raised by
 * one. Installs that were loaded straight from the current defaults already have it.
 */
return new class extends Migration
{
    public function up(): void
    {
        $tabs = HomeContent::where('section', 'winter_tabs')->get();

        if ($tabs->isEmpty() || $tabs->contains(fn (HomeContent $tab): bool => trim((string) $tab->title) === 'Willkommen')) {
            return;
        }

        // Make room: tab 1 becomes 2, and so on.
        foreach ($tabs->sortByDesc('sort_order') as $tab) {
            $tab->update(['sort_order' => $tab->sort_order + 1]);
        }

        foreach (['winter_cards', 'winter_lists', 'winter_prices', 'winter_badges', 'winter_contact'] as $section) {
            foreach (HomeContent::where('section', $section)->get() as $row) {
                $panel = trim((string) $row->subtitle);

                if (ctype_digit($panel)) {
                    $row->update(['subtitle' => (string) ((int) $panel + 1)]);
                }
            }
        }

        $cover = collect(config('winter.tabs'))->firstWhere('label', 'Willkommen');

        HomeContent::create([
            'section' => 'winter_tabs',
            'title' => $cover['label'],
            'subtitle' => $cover['heading'],
            'description' => $cover['text'],
            'image' => $cover['image'],
            'features' => $cover['ticks'],
            'button_text' => $cover['closing'],
            'sort_order' => 1,
            'active' => true,
        ]);

        foreach (collect(config('winter.cards'))->where('panel', 1)->values() as $index => $card) {
            HomeContent::create([
                'section' => 'winter_cards',
                'title' => $card['title'] !== '' ? $card['title'] : null,
                'subtitle' => '1',
                'description' => $card['text'] !== '' ? $card['text'] : null,
                'image' => $card['image'],
                'sort_order' => $index,
                'active' => true,
            ]);
        }

        foreach (collect(config('winter.badges'))->where('panel', 1)->values() as $index => $badge) {
            HomeContent::create([
                'section' => 'winter_badges',
                'title' => $badge['title'],
                'subtitle' => '1',
                'icon' => $badge['icon'],
                'description' => $badge['text'],
                'sort_order' => $index,
                'active' => true,
            ]);
        }
    }

    public function down(): void
    {
        // Intentionally empty.
    }
};
