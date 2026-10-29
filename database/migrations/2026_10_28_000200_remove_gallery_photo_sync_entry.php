<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * The automatic "Photo sync" for the Gallery was dropped. If its switch entry was already created,
 * remove it so it does not show up in the admin as an unknown entry.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('home_contents')->where('section', 'gallery_sync')->delete();
    }

    public function down(): void
    {
        // Nothing to restore.
    }
};
