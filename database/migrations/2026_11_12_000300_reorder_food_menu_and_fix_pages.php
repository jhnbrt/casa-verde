<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * - Main dishes first, then the printed Menu page (appetizer, soup, salad: no photos), then pizza, pasta,
     *   kids, burgers and Filipino, two dishes per page like the main dishes.
     * - Removes the photo versions of appetizer, soup and salad added earlier.
     * - Dishes that are not linked to a category (this is what put two main dishes under another heading) are
     *   linked to Mains.
     */
    public function up(): void
    {
        $now = now();
        $section = 'dining_page_food_menu_categories';

        // ---- 1. Remove the photo appetizer / soup / salad categories and their dishes ----
        $drop = DB::table('home_contents')->where('section', $section)
            ->whereIn(DB::raw('LOWER(title)'), ['appetizer', 'soup', 'salad'])->get();

        foreach ($drop as $category) {
            DB::table('home_contents')
                ->where('section', 'dining_page_food_menu_dishes')
                ->where(fn ($q) => $q->where('subtitle', (string) $category->id)->orWhereRaw('LOWER(subtitle) = ?', [strtolower((string) $category->title)]))
                ->delete();
            DB::table('home_contents')->where('id', $category->id)->delete();
        }

        // ---- 2. Order and two dishes per page ----
        $order = ['mains', 'pizza', 'pasta', 'kids', 'burgers and sandwiches', 'filipino'];
        $ids = [];

        foreach (DB::table('home_contents')->where('section', $section)->get() as $row) {
            $key = strtolower(trim((string) $row->title));
            $ids[$key] = $row->id;
            $position = array_search($key, $order, true);

            $update = ['updated_at' => $now];

            if ($position !== false) {
                $update['sort_order'] = $position + 1;
                $update['subtitle'] = '2';
                $update['description'] = null;
            }

            DB::table('home_contents')->where('id', $row->id)->update($update);
        }

        // ---- 3. Link stray dishes to Mains ----
        $mains = $ids['mains'] ?? null;

        if ($mains !== null) {
            $valid = array_merge(array_map('strval', array_values($ids)), array_keys($ids));

            foreach (DB::table('home_contents')->where('section', 'dining_page_food_menu_dishes')->get() as $dish) {
                $link = strtolower(trim((string) $dish->subtitle));

                if ($link === '' || ! in_array($link, $valid, true)) {
                    DB::table('home_contents')->where('id', $dish->id)->update(['subtitle' => (string) $mains, 'updated_at' => $now]);
                }
            }
        }

        // ---- 4. The Menu picture page, right after the main dishes ----
        $pagesSection = 'dining_page_food_menu_pages';
        $image = 'images/dining/menu/pages/menu-appetizer-soup-salad.jpg';

        // Earlier picture pages (photos of appetizer, soup, salad, pizza...) are replaced by editable dishes.
        DB::table('home_contents')->where('section', $pagesSection)
            ->where('image', 'like', 'images/dining/menu/pages/%')
            ->where('image', '!=', $image)
            ->delete();

        $row = DB::table('home_contents')->where('section', $pagesSection)->where('image', $image)->first();

        if ($row) {
            DB::table('home_contents')->where('id', $row->id)->update(['subtitle' => (string) ($mains ?? 'Mains'), 'updated_at' => $now]);
        } else {
            DB::table('home_contents')->insert([
                'section' => $pagesSection,
                'title' => 'Menu: Appetizer, Soup and Salad',
                'subtitle' => (string) ($mains ?? 'Mains'),
                'image' => $image,
                'sort_order' => 1,
                'active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        DB::table('home_contents')->where('section', 'dining_page_food_menu_pages')
            ->where('image', 'images/dining/menu/pages/menu-appetizer-soup-salad.jpg')->delete();
    }
};
