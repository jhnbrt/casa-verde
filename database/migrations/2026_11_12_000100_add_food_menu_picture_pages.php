<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Adds the printed menu pages (appetizer, soup and salad, pizza, pasta, kids and burgers, Filipino)
     * to the food menu book, so they can be reordered or replaced in the admin. A page already
     * present (same image) is left alone.
     */
    public function up(): void
    {
        $now = now();
        $next = (int) DB::table('home_contents')->where('section', 'dining_page_food_menu_pages')->max('sort_order');

        foreach (array_values(config('pagecontent.dining_page_food_menu_pages', [])) as $row) {
            $exists = DB::table('home_contents')
                ->where('section', 'dining_page_food_menu_pages')
                ->where('image', $row['image'])
                ->exists();

            if ($exists) {
                continue;
            }

            DB::table('home_contents')->insert($row + [
                'section' => 'dining_page_food_menu_pages',
                'sort_order' => ++$next,
                'active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        DB::table('home_contents')
            ->where('section', 'dining_page_food_menu_pages')
            ->where('image', 'like', 'images/dining/menu/pages/%')
            ->delete();
    }
};
