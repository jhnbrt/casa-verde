<?php

use Database\Seeders\ReviewContentSeeder;
use Illuminate\Database\Migrations\Migration;

/**
 * Adds the Reviews page icon entries (star, arrows) to the admin. Existing
 * entries are never overwritten.
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
