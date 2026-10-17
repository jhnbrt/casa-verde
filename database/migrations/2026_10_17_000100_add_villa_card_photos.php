<?php

use Database\Seeders\VillaPhotosSeeder;
use Illuminate\Database\Migrations\Migration;

/**
 * Loads the photos of each villa card on the Villas page into the admin
 * ("Villa card: Photos") so the sliding gallery can be edited there.
 */
return new class extends Migration
{
    public function up(): void
    {
        app(VillaPhotosSeeder::class)->run();
    }

    public function down(): void
    {
        // Intentionally empty: removing content is not something to undo silently.
    }
};
