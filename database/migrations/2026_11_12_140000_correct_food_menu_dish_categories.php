<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        $groups = [
            'Mains' => ['Chicken Parmy Katsu Style', 'Chicken Schnitzel Cordon Bleu', 'Buttered Garlic Shrimp', 'Salmon Steak', 'Beef Goulash', 'Chicken Curry'],
            'Filipino' => ['Chopsuey', 'Stir Fried Noodles', 'Beef Pochero', 'Spring Fried Chicken'],
            'Kids' => ['Fried Chicken Nibblers', 'Mac & Cheese'],
            'Burgers and Sandwiches' => ['Casa Verde Monster Burger', 'Crazy Devil Chicken Burger', 'Sunset Clubhouse Sandwich'],
            'Pasta' => ['Seafood Linguini', 'Carbonara', 'Agli Olio'],
            'Pizza' => ['Quatroformaggi', 'Quattroformaggi', 'Meat Lovers', 'Prosciutto de Parma', 'Hawaiian', 'Vegetarian', 'Margherita'],
            'Soup' => ['Mushroom Creme Soup', 'Pumpkin Soup', 'Wanton Soup'],
            'Salad' => ['Caesar Salad', 'Chicken Roasted Sesame Salad'],
            'Appetizer' => ['Fish Ceviche', 'Crispy Calamari', 'French Fries', 'Gambas Al Ajillo', 'Spring Roll'],
        ];
        $now = now();
        foreach ($groups as $category => $names) {
            $categoryRow = DB::table('home_contents')
                ->where('section', 'dining_page_food_menu_categories')
                ->whereRaw('LOWER(TRIM(title)) = ?', [strtolower($category)])
                ->first();
            if (! $categoryRow) {
                continue;
            }
            foreach ($names as $name) {
                DB::table('home_contents')
                    ->where('section', 'dining_page_food_menu_dishes')
                    ->whereRaw('LOWER(TRIM(title)) = ?', [strtolower($name)])
                    ->update(['subtitle' => (string) $categoryRow->id, 'updated_at' => $now]);
            }
        }
    }

    public function down(): void
    {
        // Intentionally retain corrected menu assignments and admin edits.
    }
};
