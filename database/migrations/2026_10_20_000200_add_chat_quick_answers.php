<?php

use Database\Seeders\AssistantContentSeeder;
use Illuminate\Database\Migrations\Migration;

/**
 * Loads the chat's free quick answers into the admin (Chat & offers > Chat quick answers).
 * Existing entries are never overwritten.
 */
return new class extends Migration
{
    public function up(): void
    {
        app(AssistantContentSeeder::class)->run();
    }

    public function down(): void
    {
        // Intentionally empty: removing content is not something to undo silently.
    }
};
