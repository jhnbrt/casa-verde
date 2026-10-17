<?php

use Database\Seeders\LongStayContentSeeder;
use Database\Seeders\PageContentDefaultsSeeder;
use Illuminate\Database\Migrations\Migration;

/**
 * The Dining, Experiences, Wellness and Villas pages (and a few Home labels) lived
 * only in code. This loads the current content into the admin where it can be
 * edited. Existing entries are never overwritten.
 */
return new class extends Migration
{
    public function up(): void
    {
        app(LongStayContentSeeder::class)->run();
        app(PageContentDefaultsSeeder::class)->run();
    }

    public function down(): void
    {
        // Intentionally empty: removing content is not something to undo silently.
    }
};
