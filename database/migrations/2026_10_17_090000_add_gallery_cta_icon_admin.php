<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        if (! DB::table('home_contents')->where('section', 'gallery_cta_icon')->exists()) {
            DB::table('home_contents')->insert([
                'section' => 'gallery_cta_icon',
                'title' => 'Gallery booking banner icon',
                'icon' => 'leaf',
                'sort_order' => 1,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        // Preserve user-uploaded icon choices if the migration is rolled back.
    }
};
