<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * The Home page villa cards and the Villas page used to share one set of feature-icon entries,
 * so changing an icon for one page changed it on the other. The Home page now has its own
 * entries, starting with the icons its cards already show. The Villas page keeps its entries.
 */
return new class extends Migration
{
    public function up(): void
    {
        $icons = [
            'Standard Villa' => ['bed', 'leaf', 'house'],
            'Premium Villa' => ['sunset', 'balcony', 'star'],
        ];

        foreach ($icons as $villa => $names) {
            foreach ($names as $index => $icon) {
                $exists = DB::table('home_contents')
                    ->where('section', 'home_villa_feature_icons')
                    ->where('title', $villa)
                    ->where('sort_order', $index + 1)
                    ->exists();

                if (! $exists) {
                    DB::table('home_contents')->insert([
                        'section' => 'home_villa_feature_icons',
                        'title' => $villa,
                        'icon' => $icon,
                        'sort_order' => $index + 1,
                        'active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    public function down(): void
    {
        DB::table('home_contents')->where('section', 'home_villa_feature_icons')->delete();
    }
};
