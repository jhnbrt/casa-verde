<?php

use Database\Seeders\EventContentSeeder;
use Database\Seeders\PageContentSeeder;
use Illuminate\Database\Migrations\Migration;

/**
 * The Events, Special Offers, Gallery and Footer content lived only in code,
 * so it never showed up in the admin. This loads the current content into the
 * admin where it can be edited. Existing entries are never overwritten.
 */
return new class extends Migration
{
    public function up(): void
    {
        app(EventContentSeeder::class)->run();
        app(PageContentSeeder::class)->run();
    }

    public function down(): void
    {
        // Intentionally empty: removing content is not something to undo silently.
    }
};
