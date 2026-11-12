<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * The food menu book is built from dishes (name, price, description, photo, icons) grouped in
     * categories, instead of flat page pictures. This loads the Mains dishes into the admin, and removes
     * the two starting page pictures they replace. Anything already edited is left alone.
     */
    public function up(): void
    {
        $now = now();

        // The categories first, so the dishes can point at them by id.
        $categoryIds = [];

        if (! DB::table('home_contents')->where('section', 'dining_page_food_menu_categories')->exists()) {
            foreach (array_values(config('pagecontent.dining_page_food_menu_categories', [])) as $i => $row) {
                $categoryIds[strtolower($row['title'])] = DB::table('home_contents')->insertGetId($row + [
                    'section' => 'dining_page_food_menu_categories',
                    'sort_order' => $i + 1,
                    'active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        } else {
            foreach (DB::table('home_contents')->where('section', 'dining_page_food_menu_categories')->get() as $row) {
                $categoryIds[strtolower((string) $row->title)] = $row->id;
            }
        }

        if (! DB::table('home_contents')->where('section', 'dining_page_food_menu_dishes')->exists()) {
            foreach (array_values(config('pagecontent.dining_page_food_menu_dishes', [])) as $i => $row) {
                $row['features'] = json_encode(array_values((array) ($row['features'] ?? [])));
                $row['subtitle'] = (string) ($categoryIds[strtolower((string) $row['subtitle'])] ?? $row['subtitle']);

                DB::table('home_contents')->insert($row + [
                    'section' => 'dining_page_food_menu_dishes',
                    'sort_order' => $i + 1,
                    'active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        // The flat page pictures of the first version are replaced by the dishes.
        DB::table('home_contents')
            ->where('section', 'dining_page_food_menu_pages')
            ->whereIn('image', ['images/dining/menu/mains-1.jpg', 'images/dining/menu/mains-2.jpg'])
            ->delete();
    }

    public function down(): void
    {
        DB::table('home_contents')->whereIn('section', ['dining_page_food_menu_categories', 'dining_page_food_menu_dishes'])->delete();
    }
};
