<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Icons were picked from a fixed list of names (lotus, rings, cake...). They are now
 * images that can be replaced from the admin (Icon image upload). This points every
 * entry that still uses an icon name at an editable placeholder image of the same
 * icon. Emoji and entries with no icon are left alone.
 */
return new class extends Migration
{
    public function up(): void
    {
        foreach (glob(public_path('images/icons/placeholders/*.svg')) ?: [] as $file) {
            $name = pathinfo($file, PATHINFO_FILENAME);

            DB::table('home_contents')
                ->where('icon', $name)
                ->update(['icon' => 'images/icons/placeholders/'.$name.'.svg']);
        }
    }

    public function down(): void
    {
        // Intentionally empty: the placeholder images keep working if this is not undone.
    }
};
