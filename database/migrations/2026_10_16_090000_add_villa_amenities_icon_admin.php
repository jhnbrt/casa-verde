<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        if (!DB::table('home_contents')->where('section', 'villas_amenities_icon')->exists()) {
            DB::table('home_contents')->insert([
                'section' => 'villas_amenities_icon',
                'title' => 'Villa Amenities decorative icon',
                'icon' => 'lotus',
                'sort_order' => 1,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
    public function down(): void { /* Preserve admin customizations. */ }
};
