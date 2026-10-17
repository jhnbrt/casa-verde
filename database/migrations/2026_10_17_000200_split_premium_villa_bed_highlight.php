<?php

use Illuminate\Database\Migrations\Migration;

/**
 * Replaced: the Premium Villa beds stay ONE highlight with two icons. See
 * 2026_10_17_000300_join_premium_villa_bed_icons.php. Kept (doing nothing) so that
 * installs that already recorded this migration are unaffected.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Intentionally empty.
    }

    public function down(): void
    {
        // Intentionally empty.
    }
};
