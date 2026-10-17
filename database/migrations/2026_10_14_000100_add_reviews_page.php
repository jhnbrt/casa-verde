<?php

use Database\Seeders\ReviewContentSeeder;
use Illuminate\Database\Migrations\Migration;

/**
 * Loads the Reviews page content into the admin and adds "Reviews" to the main
 * menu. Existing entries are never overwritten.
 */
return new class extends Migration
{
    public function up(): void
    {
        app(ReviewContentSeeder::class)->run();
    }

    public function down(): void
    {
        // Intentionally empty: removing content is not something to undo silently.
    }
};
