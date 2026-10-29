<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * The "Photo sync" switch for the Gallery page. Shown = every site photo also appears in the
     * gallery; hidden = only the photos chosen in the gallery sections.
     */
    public function up(): void
    {
        if (! DB::table('home_contents')->where('section', 'gallery_sync')->exists()) {
            DB::table('home_contents')->insert([
                'section' => 'gallery_sync',
                'title' => 'More photos',
                'sort_order' => 1,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('home_contents')->where('section', 'gallery_sync')->delete();
    }
};
