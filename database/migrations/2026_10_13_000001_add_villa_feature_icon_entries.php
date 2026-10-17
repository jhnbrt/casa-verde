<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        foreach (['Standard Villa', 'Premium Villa'] as $villa) {
            for ($position = 1; $position <= 3; $position++) {
                $exists = DB::table('home_contents')->where('section', 'villas_feature_icons')
                    ->where('title', $villa)->where('sort_order', $position)->exists();
                if (! $exists) {
                    DB::table('home_contents')->insert([
                        'section' => 'villas_feature_icons', 'title' => $villa,
                        'sort_order' => $position, 'icon' => '♧', 'active' => true,
                        'created_at' => now(), 'updated_at' => now(),
                    ]);
                }
            }
        }
    }
    public function down(): void
    {
        DB::table('home_contents')->where('section', 'villas_feature_icons')->delete();
    }
};
