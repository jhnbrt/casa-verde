<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Turns the printed menu pages (appetizer, soup, salad, pizza, pasta, kids, burgers, Filipino) into
     * real, editable content: categories, dishes with photos and icons, the icon list and the cover
     * texts. Only adds what is missing; nothing already edited in the admin is overwritten.
     */
    public function up(): void
    {
        $now = now();

        // ---- Categories (in the order of the book) ----
        $categoryIds = [];
        $existing = DB::table('home_contents')->where('section', 'dining_page_food_menu_categories')->get();

        foreach ($existing as $row) {
            $categoryIds[strtolower(trim((string) $row->title))] = $row->id;
        }

        foreach (array_values(config('pagecontent.dining_page_food_menu_categories', [])) as $i => $row) {
            $key = strtolower(trim($row['title']));

            if (isset($categoryIds[$key])) {
                $id = $categoryIds[$key];
                $current = DB::table('home_contents')->where('id', $id)->first();
                $update = ['sort_order' => $i + 1, 'updated_at' => $now];

                if (trim((string) $current->subtitle) === '' && isset($row['subtitle'])) {
                    $update['subtitle'] = $row['subtitle'];
                }

                DB::table('home_contents')->where('id', $id)->update($update);

                continue;
            }

            $categoryIds[$key] = DB::table('home_contents')->insertGetId($row + [
                'section' => 'dining_page_food_menu_categories',
                'sort_order' => $i + 1,
                'active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // ---- Dishes ----
        $have = DB::table('home_contents')->where('section', 'dining_page_food_menu_dishes')->pluck('title')
            ->map(fn ($title) => strtolower(trim((string) $title)))->all();
        $next = (int) DB::table('home_contents')->where('section', 'dining_page_food_menu_dishes')->max('sort_order');

        foreach (array_values(config('pagecontent.dining_page_food_menu_dishes', [])) as $row) {
            if (in_array(strtolower(trim($row['title'])), $have, true)) {
                continue;
            }

            $row['features'] = json_encode(array_values((array) ($row['features'] ?? [])));
            $row['subtitle'] = (string) ($categoryIds[strtolower(trim((string) $row['subtitle']))] ?? $row['subtitle']);

            DB::table('home_contents')->insert($row + [
                'section' => 'dining_page_food_menu_dishes',
                'sort_order' => ++$next,
                'active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // ---- Icons ----
        if (! DB::table('home_contents')->where('section', 'dining_page_food_menu_icons')->exists()) {
            foreach (array_values(config('pagecontent.dining_page_food_menu_icons', [])) as $i => $row) {
                DB::table('home_contents')->insert($row + [
                    'section' => 'dining_page_food_menu_icons',
                    'sort_order' => $i + 1,
                    'active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        // ---- Cover: footer text and button wording ----
        $cover = DB::table('home_contents')->where('section', 'dining_page_food_menu')->orderBy('sort_order')->first();

        if ($cover) {
            $update = ['updated_at' => $now];

            if (trim((string) $cover->subtitle) === '') {
                $update['subtitle'] = 'Casa Verde Cliff Resort & Spa';
            }

            if (trim((string) $cover->button_text) === '' || strcasecmp(trim((string) $cover->button_text), 'Click here') === 0) {
                $update['button_text'] = 'Open Menu';
            }

            DB::table('home_contents')->where('id', $cover->id)->update($update);
        }

        // ---- The picture pages from the first import are replaced by the editable pages above ----
        DB::table('home_contents')
            ->where('section', 'dining_page_food_menu_pages')
            ->where('image', 'like', 'images/dining/menu/pages/%')
            ->delete();
    }

    public function down(): void
    {
        DB::table('home_contents')->where('section', 'dining_page_food_menu_icons')->delete();
    }
};
