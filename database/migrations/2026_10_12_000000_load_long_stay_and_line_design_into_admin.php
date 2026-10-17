<?php

use Database\Seeders\LongStayContentSeeder;
use Illuminate\Database\Migrations\Migration;

/**
 * The Long Stay page lived only in code, and the divider line / emoji design
 * could not be changed anywhere. This loads the current content into the admin
 * where it can be edited. Existing entries are never overwritten.
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
