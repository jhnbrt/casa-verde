<?php

use Database\Seeders\LongStayContentSeeder;
use Illuminate\Database\Migrations\Migration;

/**
 * The redesigned Long Stay page: an icon for every highlight, way to stay and box line,
 * two side photos, a tagline under the banner text and a second line in the closing bar.
 * Entries already changed in the admin are never overwritten.
 */
return new class extends Migration
{
    public function up(): void
    {
        app(LongStayContentSeeder::class)->run();
    }

    public function down(): void
    {
        // Intentionally empty: removing content is not something to undo silently.
    }
};
