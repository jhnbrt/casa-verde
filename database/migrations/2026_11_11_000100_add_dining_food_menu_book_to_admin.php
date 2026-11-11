<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Puts the food menu book (cover and pages) into the admin and points the EXPLORE FOOD MENU
     * button at it. Only adds what is missing; nothing already edited is changed.
     */
    public function up(): void
    {
        $now = now();

        foreach (['dining_page_food_menu', 'dining_page_food_menu_pages'] as $section) {
            if (DB::table('home_contents')->where('section', $section)->exists()) {
                continue;
            }

            foreach (array_values(config("pagecontent.{$section}", [])) as $i => $row) {
                DB::table('home_contents')->insert($row + [
                    'section' => $section,
                    'sort_order' => $i + 1,
                    'active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        // The food menu button had no link yet ("#"): make it open the book.
        DB::table('home_contents')
            ->where('section', 'dining_page_menus')
            ->whereRaw('LOWER(button_text) LIKE ?', ['%food%'])
            ->where(fn ($query) => $query->whereNull('button_url')->orWhere('button_url', '')->orWhere('button_url', '#'))
            ->update(['button_url' => '#food-menu', 'updated_at' => $now]);
    }

    public function down(): void
    {
        DB::table('home_contents')->whereIn('section', ['dining_page_food_menu', 'dining_page_food_menu_pages'])->delete();
    }
};
