<?php

use Illuminate\Database\Migrations\Migration;

/**
 * Superseded by 2026_11_10_000200_add_winterresidenz_page, which loads the same content
 * for the standalone Winterresidenz page instead of the footer. Kept only so installs that
 * already ran it stay consistent; it does nothing.
 */
return new class extends Migration
{
    public function up(): void
    {
        //
    }

    public function down(): void
    {
        //
    }
};
