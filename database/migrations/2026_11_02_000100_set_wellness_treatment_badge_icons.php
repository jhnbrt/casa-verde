<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * The five treatment photos had their round badge drawn into the picture.
     * This gives each card an editable badge icon (Site content > Wellness page > Treatments)
     * that sits over the old one. Cards that already have an icon are left alone.
     */
    public function up(): void
    {
        $icons = [
            'images/wellness/banana.jpg' => 'hands',
            'images/wellness/hot-stone.jpg' => 'stones',
            'images/wellness/therapeutic.jpg' => 'leaf',
            'images/wellness/foot.jpg' => 'foot',
            'images/wellness/package.jpg' => 'spaset',
        ];

        foreach ($icons as $image => $icon) {
            DB::table('home_contents')
                ->where('section', 'wellness_page_treatments')
                ->where('image', $image)
                ->where(fn ($q) => $q->whereNull('icon')->orWhere('icon', ''))
                ->update(['icon' => $icon, 'updated_at' => now()]);
        }
    }

    public function down(): void
    {
        // Nothing to undo: the icons are ordinary admin content.
    }
};
