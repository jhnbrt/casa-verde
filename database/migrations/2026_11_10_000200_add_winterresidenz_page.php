<?php

use App\Models\HomeContent;
use Database\Seeders\WinterResidenceSeeder;
use Illuminate\Database\Migrations\Migration;

/**
 * The German Winterresidenz offer lives on its own page (/winterresidenz), reached from a
 * dropdown under Long Stay in the header, a banner on the Long Stay page and the footer's
 * Explore links. This loads its content into the admin and adds the footer link. Anything
 * already edited is left alone.
 */
return new class extends Migration
{
    public function up(): void
    {
        // An earlier version showed the content in the footer, under "footer_winter*".
        foreach (['', '_tabs', '_cards', '_lists', '_prices', '_badges', '_contact'] as $suffix) {
            if (! HomeContent::where('section', 'winter'.$suffix)->exists()) {
                HomeContent::where('section', 'footer_winter'.$suffix)->update(['section' => 'winter'.$suffix]);
            }
        }

        app(WinterResidenceSeeder::class)->run();

        // Footer Explore column: add the link after "Long Stay" when the admin has its own list.
        $explore = HomeContent::where('section', 'footer_explore')->orderBy('sort_order')->get();

        if ($explore->isEmpty() || $explore->contains(fn (HomeContent $row): bool => str_contains(strtolower((string) $row->button_url), 'winterresidenz'))) {
            return;
        }

        $longStay = $explore->first(fn (HomeContent $row): bool => strtolower(trim((string) $row->title)) === 'long stay');
        $position = $longStay ? $longStay->sort_order + 1 : ($explore->max('sort_order') ?? 0) + 1;

        HomeContent::where('section', 'footer_explore')->where('sort_order', '>=', $position)->increment('sort_order');
        HomeContent::create(['section' => 'footer_explore', 'title' => 'Winterresidenz', 'button_url' => '/winterresidenz', 'sort_order' => $position, 'active' => true]);
    }

    public function down(): void
    {
        // Intentionally empty: removing content is not something to undo silently.
    }
};
