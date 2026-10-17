<?php

use App\Models\HomeContent;
use Illuminate\Database\Migrations\Migration;

/**
 * The icon strip under the Home banner and the lotus on the Home villa cards were
 * fixed in the code. This shows the pictures that are on the site today in the admin
 * so each one can be replaced by uploading an image. Entries already changed in the
 * admin are never overwritten.
 */
return new class extends Migration
{
    public function up(): void
    {
        $strip = [
            1 => 'images/icons/cliff.png',
            2 => 'images/icons/sunset.png',
            3 => 'images/icons/villa.png',
            4 => 'images/icons/ocean.png',
            5 => 'images/icons/spa.png',
            6 => 'images/icons/dining.png',
        ];

        $features = HomeContent::where('section', 'features')->orderBy('sort_order')->orderBy('id')->get()->values();

        foreach ($features as $index => $row) {
            $icon = trim((string) $row->icon);
            $isPicture = (bool) preg_match('/\.(png|jpe?g|webp|gif|svg)$/i', $icon);

            if (! $isPicture && isset($strip[$index + 1])) {
                $row->update(['icon' => $strip[$index + 1]]);
            }
        }

        if (HomeContent::where('section', 'site_icons')->exists()) {
            HomeContent::firstOrCreate(
                ['section' => 'site_icons', 'sort_order' => 5],
                ['title' => 'Villa card lotus', 'icon' => 'images/icons/ico-lotus.png', 'active' => true],
            );
        }
    }

    public function down(): void
    {
        // Intentionally empty: removing content is not something to undo silently.
    }
};
