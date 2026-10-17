<?php

use Database\Seeders\PageContentDefaultsSeeder;
use Illuminate\Database\Migrations\Migration;

/**
 * Symbols, shared site icons and the villa highlight icons become editable in the admin
 * (each can be an emoji, a symbol or an uploaded image). Existing entries are never overwritten.
 */
return new class extends Migration
{
    public function up(): void
    {
        app(PageContentDefaultsSeeder::class)->run();
    }

    public function down(): void
    {
        // Intentionally empty: removing content is not something to undo silently.
    }
};
