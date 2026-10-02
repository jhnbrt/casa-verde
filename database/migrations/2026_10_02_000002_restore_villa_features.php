<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Earlier admin saves wiped the "features" list of the villa cards.
 * This puts the original lists back, only where a villa has none.
 * Nothing that already has features is touched.
 */
return new class extends Migration
{
    public function up(): void
    {
        $defaults = [
            'standard-villa' => [
                'Ideal for couples and longer stays',
                'Garden and terrace moments',
                'Everything you need to feel completely at home',
            ],
            'premium-villa' => [
                'More space and privacy',
                'Ocean views and cliffside atmosphere',
                'Made for unforgettable stays',
            ],
        ];

        DB::table('home_contents')
            ->where('section', 'villas')
            ->get()
            ->each(function ($row) use ($defaults) {
                $current = json_decode((string) $row->features, true);

                if (! empty($current)) {
                    return;
                }

                $slug = Str::slug((string) $row->title);

                if (isset($defaults[$slug])) {
                    DB::table('home_contents')
                        ->where('id', $row->id)
                        ->update(['features' => json_encode($defaults[$slug])]);
                }
            });
    }

    public function down(): void
    {
        // Intentionally empty: restoring content is not reversible.
    }
};
