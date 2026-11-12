<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        $now = now();
        $order = ['Mains', 'Filipino', 'Kids', 'Burgers and Sandwiches', 'Pasta', 'Pizza', 'Soup', 'Salad', 'Appetizer'];
        $ids = [];
        foreach ($order as $index => $name) {
            $row = DB::table('home_contents')->where('section', 'dining_page_food_menu_categories')
                ->whereRaw('LOWER(title) = ?', [strtolower($name)])->first();
            if ($row) {
                $ids[$name] = $row->id;
                DB::table('home_contents')->where('id', $row->id)->update(['sort_order' => $index + 1, 'subtitle' => '2', 'active' => true, 'updated_at' => $now]);
            } else {
                $ids[$name] = DB::table('home_contents')->insertGetId([
                    'section' => 'dining_page_food_menu_categories', 'title' => $name,
                    'subtitle' => '2', 'sort_order' => $index + 1, 'active' => true,
                    'created_at' => $now, 'updated_at' => $now,
                ]);
            }
        }

        // Restore the individual editable dishes previously deleted by the old menu migration.
        $new = [
            ['Soup', 'Mushroom Creme Soup', 295, 'Velvety mushroom creme soup finished with cream and thyme for a rich, earthy, and comforting flavor.', 'mushroom-creme-soup.jpg', []],
            ['Soup', 'Pumpkin Soup', 295, 'Velvety pumpkin blended with warm spices, finished to a smooth and comforting texture.', 'pumpkin-soup.jpg', []],
            ['Soup', 'Wanton Soup', 295, 'Minced pork meat wrapped in wanton mix, carrots, and chicken soup stock with an Asian taste.', 'wanton-soup.jpg', ['pork', 'chicken']],
            ['Salad', 'Caesar Salad', 450, 'Fresh lettuce coated in house Caesar dressing, finished with crispy bacon bites, golden croutons, and parmesan cheese.', 'caesar-salad.jpg', ['pork', 'cheese']],
            ['Salad', 'Chicken Roasted Sesame Salad', 495, 'Lettuce, cucumber, tomato, boiled chicken, and olives with sesame dressing.', 'chicken-roasted-sesame-salad.jpg', ['chicken']],
            ['Appetizer', 'Fish Ceviche', 495, 'Fresh fish fillet, vinaigrette, cucumber, tomato, chili and lemon juice.', 'fish-ceviche.jpg', ['seafood', 'chili']],
            ['Appetizer', 'Crispy Calamari', 495, 'Golden deep-fried squid served with seasoned potato wedges, tartar sauce, and spicy mayo.', 'crispy-calamari.jpg', ['seafood', 'chili']],
            ['Appetizer', 'French Fries', 170, 'Crispy golden fries seasoned with salt and served with tomato catsup.', 'french-fries.jpg', []],
            ['Appetizer', 'Gambas Al Ajillo', 495, 'Plump shrimp sautéed in olive oil with aromatic chopped garlic and green chili, finished with fresh lemon juice and sweet chili sauce, served with toasted garlic butter bread.', 'gambas-al-ajillo.jpg', ['seafood', 'chili']],
            ['Appetizer', 'Spring Roll', 220, 'A popular Asian appetizer featuring savory chopped fillings, carrots, onion and ground pork wrapped in a thin crisp flour pastry.', 'spring-roll.jpg', ['pork']],
        ];
        foreach ($new as $i => [$category, $name, $price, $description, $file, $icons]) {
            $existing = DB::table('home_contents')->where('section', 'dining_page_food_menu_dishes')
                ->whereRaw('LOWER(title) = ?', [strtolower($name)])->first();
            if ($existing) {
                // Preserve all existing admin edits; repair only the category association.
                DB::table('home_contents')->where('id', $existing->id)->update(['subtitle' => (string) $ids[$category], 'updated_at' => $now]);
                continue;
            }
            DB::table('home_contents')->insert([
                'section' => 'dining_page_food_menu_dishes', 'title' => $name,
                'subtitle' => (string) $ids[$category], 'price' => $price,
                'description' => $description, 'image' => 'images/dining/menu/dishes/' . $file,
                'features' => json_encode($icons), 'sort_order' => 100 + $i,
                'active' => true, 'created_at' => $now, 'updated_at' => $now,
            ]);
        }

        // The old combined picture page must not appear after the new editable pages.
        DB::table('home_contents')->where('section', 'dining_page_food_menu_pages')
            ->where('image', 'images/dining/menu/pages/menu-appetizer-soup-salad.jpg')->delete();
    }

    public function down(): void
    {
        // Keep user-editable menu content intact on rollback.
    }
};
