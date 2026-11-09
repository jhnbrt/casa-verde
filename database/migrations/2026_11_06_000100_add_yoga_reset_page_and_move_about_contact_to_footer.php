<?php

use App\Models\HomeContent;
use Database\Seeders\YogaContentSeeder;
use Illuminate\Database\Migrations\Migration;

/**
 * Adds the Yoga Reset page (content editable in the admin, menu entry right
 * after Reviews) and moves About and Contact from the header menu into the
 * footer's Useful Links. Existing entries are never overwritten.
 */
return new class extends Migration
{
    public function up(): void
    {
        app(YogaContentSeeder::class)->run();

        $this->addYogaToMenu();
        $this->moveAboutAndContactToFooter();
    }

    public function down(): void
    {
        // Intentionally empty: removing content is not something to undo silently.
    }

    protected function addYogaToMenu(): void
    {
        $links = HomeContent::where('section', 'navigation')->orderBy('sort_order')->get();

        if ($links->contains(fn (HomeContent $link): bool => strtolower(trim((string) $link->title)) === 'yoga reset')) {
            return;
        }

        $reviews = $links->first(fn (HomeContent $link): bool => strtolower(trim((string) $link->title)) === 'reviews');
        $position = $reviews ? $reviews->sort_order + 1 : (($links->max('sort_order') ?? 0) + 1);

        HomeContent::where('section', 'navigation')->where('sort_order', '>=', $position)->increment('sort_order');

        HomeContent::create([
            'section' => 'navigation',
            'title' => 'YOGA RESET',
            'button_url' => '/yoga-reset',
            'sort_order' => $position,
            'active' => true,
        ]);
    }

    protected function moveAboutAndContactToFooter(): void
    {
        // Switch both off in the header (kept in the admin, so they can be switched back on).
        HomeContent::where('section', 'navigation')
            ->whereRaw('lower(trim(title)) in (?, ?)', ['about', 'contact'])
            ->update(['active' => false]);

        $footer = HomeContent::where('section', 'footer_links')->orderBy('sort_order')->get();

        // Never filled in: the footer uses config/footer.php, which already lists both.
        if ($footer->isEmpty()) {
            return;
        }

        $has = fn (string $title): bool => $footer->contains(fn (HomeContent $row): bool => strtolower(trim((string) $row->title)) === $title);

        $contact = $footer->first(fn (HomeContent $row): bool => strtolower(trim((string) $row->title)) === 'contact');
        $next = ($footer->max('sort_order') ?? 0) + 1;

        if (! $has('about')) {
            $position = $contact?->sort_order ?? $next;
            HomeContent::where('section', 'footer_links')->where('sort_order', '>=', $position)->increment('sort_order');
            HomeContent::create(['section' => 'footer_links', 'title' => 'About', 'button_url' => '/about', 'sort_order' => $position, 'active' => true]);
        }

        if (! $has('contact')) {
            HomeContent::create(['section' => 'footer_links', 'title' => 'Contact', 'button_url' => '/contact', 'sort_order' => ($footer->max('sort_order') ?? 0) + 2, 'active' => true]);
        } elseif ($contact && ! $contact->active) {
            $contact->update(['active' => true]);
        }

        // The header's Contact opened the Contact page, so the footer one should too.
        HomeContent::where('section', 'footer_links')->whereRaw('lower(trim(title)) = ?', ['contact'])->where('button_url', '/#contact')->update(['button_url' => '/contact']);
    }
};
