<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        foreach (['standard-villa', 'premium-villa'] as $slug) {
            $section = 'villas_detail_hero_' . str_replace('-', '_', $slug);
            if (DB::table('home_contents')->where('section', $section)->exists()) {
                continue;
            }
            $image = config("villas.{$slug}.hero");
            DB::table('home_contents')->insert([
                'section' => $section,
                'title' => 'Main exterior photo',
                'image' => $image,
                'sort_order' => 1,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        // Keep photos saved through the admin panel.
    }
};
