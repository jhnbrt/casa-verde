<?php

use Database\Seeders\YogaContentSeeder;
use Illuminate\Database\Migrations\Migration;

/**
 * The redesigned Yoga Reset page ends with a questions and answers section.
 * This loads its starting content into the admin. Anything already edited is left alone.
 */
return new class extends Migration
{
    public function up(): void
    {
        app(YogaContentSeeder::class)->run();
    }

    public function down(): void
    {
        // Intentionally empty: removing content is not something to undo silently.
    }
};
