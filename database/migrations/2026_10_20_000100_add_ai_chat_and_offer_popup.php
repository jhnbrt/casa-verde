<?php

use Database\Seeders\AssistantContentSeeder;
use Illuminate\Database\Migrations\Migration;

/**
 * Loads the AI chat assistant and counter-offer popup settings into the admin
 * (Chat & offers). Existing entries are never overwritten.
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
