<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        foreach (['standard-villa', 'premium-villa'] as $slug) {
            $section = 'villas_detail_photos_' . str_replace('-', '_', $slug);
            $photos = config("villas.{$slug}.gallery", []);
            foreach ($photos as $index => $photo) {
                $order = $index + 1;
                if (DB::table('home_contents')->where('section', $section)->where('sort_order', $order)->exists()) {
                    continue;
                }
                DB::table('home_contents')->insert([
                    'section' => $section,
                    'title' => 'Interior photo ' . $order,
                    'image' => $photo,
                    'sort_order' => $order,
                    'active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        // Preserve photos and user changes made in Admin.
    }
};
