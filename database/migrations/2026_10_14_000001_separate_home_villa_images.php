<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Copy existing photos once; never overwrite photos already customized in admin.
        $villas = DB::table('home_contents')->where('section', 'villas')->get();
        foreach ($villas as $villa) {
            $exists = DB::table('home_contents')
                ->where('section', 'home_villa_images')
                ->where('title', $villa->title)
                ->exists();
            if ($exists) {
                continue;
            }
            DB::table('home_contents')->insert([
                'section' => 'home_villa_images',
                'title' => $villa->title,
                'image' => $villa->image,
                'sort_order' => $villa->sort_order,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        // Preserve user-uploaded photos and their admin entries on rollback.
    }
};
