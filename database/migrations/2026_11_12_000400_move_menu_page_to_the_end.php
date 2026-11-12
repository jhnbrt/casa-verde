<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /** The printed Menu page (appetizer, soup, salad) becomes the last page of the book. */
    public function up(): void
    {
        DB::table('home_contents')
            ->where('section', 'dining_page_food_menu_pages')
            ->where('image', 'images/dining/menu/pages/menu-appetizer-soup-salad.jpg')
            ->update(['subtitle' => '', 'sort_order' => 99, 'updated_at' => now()]);
    }

    public function down(): void
    {
        //
    }
};
