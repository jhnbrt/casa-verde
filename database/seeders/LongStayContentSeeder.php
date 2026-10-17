<?php

namespace Database\Seeders;

use App\Models\HomeContent;
use Illuminate\Database\Seeder;

class LongStayContentSeeder extends Seeder
{
    /**
     * Load the Long Stay page content (and the site-wide line design) into the
     * admin without touching any other content. Safe to run more than once.
     */
    public function run(): void
    {
        $page = config('longstay');

        $this->seed('site_divider', 1, [
            'title' => '#c9a06a',
            'subtitle' => 'solid',
            'icon' => 'lotus',
        ]);

        $this->seed('long_stay_hero', 1, [
            'subtitle' => $page['hero']['eyebrow'],
            'title' => $page['hero']['title'],
            'description' => $page['hero']['text'],
            'image' => $page['hero']['image'],
            'features' => [$page['hero']['tagline']],
            'button_text' => $page['hero']['button_text'],
            'button_url' => $page['hero']['button_url'],
        ]);

        foreach ($page['strip'] as $index => $item) {
            $this->seed('long_stay_strip', $index + 1, [
                'title' => $item['title'],
                'description' => $item['text'],
                'icon' => $item['icon'],
            ]);
        }

        $this->seed('long_stay_uses_header', 1, ['subtitle' => $page['uses_header']['eyebrow']]);

        foreach ($page['uses'] as $index => $item) {
            $this->seed('long_stay_uses', $index + 1, [
                'title' => $item['title'],
                'description' => $item['text'],
                'icon' => $item['icon'],
            ]);
        }

        $this->seed('long_stay_intro', 1, [
            'subtitle' => $page['intro']['eyebrow'],
            'title' => $page['intro']['title'],
            'description' => $page['intro']['text'],
            'icon' => $page['intro']['icon'],
        ]);

        foreach ($page['panels'] as $index => $panel) {
            $this->seed('long_stay_panels', $index + 1, [
                'title' => $panel['title'],
                'subtitle' => $panel['numbered'] ? 'numbered' : null,
            ]);
        }

        $this->seedPanelItems($page['panels']);

        foreach ($page['gallery'] as $index => $photo) {
            $this->seed('long_stay_gallery', $index + 1, [
                'title' => $photo['alt'],
                'image' => $photo['image'],
            ]);
        }

        $this->seed('long_stay_bar', 1, [
            'title' => $page['bar']['text'],
            'subtitle' => $page['bar']['text_second'],
            'button_text' => $page['bar']['button_text'],
            'button_url' => $page['bar']['button_url'],
        ]);

        $this->fillBlanks($page);
    }

    /**
     * The lines inside the three boxes become one entry each, so every line can have its own
     * icon. Lines already typed into a box in the admin are kept; nothing is added twice.
     *
     * @param  array<int, array<string, mixed>>  $panels
     */
    protected function seedPanelItems(array $panels): void
    {
        if (HomeContent::where('section', 'long_stay_panel_items')->exists()) {
            return;
        }

        $order = 0;

        foreach ($panels as $index => $panel) {
            $typed = (array) HomeContent::where('section', 'long_stay_panels')->where('sort_order', $index + 1)->value('features');
            $lines = array_values(array_filter(array_map('trim', $typed), 'strlen'));

            foreach ($lines ?: array_column($panel['items'], 'text') as $position => $text) {
                $this->seed('long_stay_panel_items', ++$order, [
                    'title' => $text,
                    'subtitle' => (string) ($index + 1),
                    'icon' => $panel['items'][$position]['icon'] ?? null,
                ]);
            }
        }
    }

    /**
     * Entries saved before this design have no icons, tagline or second line. Only blanks are
     * filled; anything already set in the admin stays as it is.
     *
     * @param  array<string, mixed>  $page
     */
    protected function fillBlanks(array $page): void
    {
        foreach (['strip', 'uses'] as $list) {
            foreach ($page[$list] as $index => $item) {
                HomeContent::where('section', 'long_stay_'.$list)
                    ->where('sort_order', $index + 1)
                    ->where(fn ($query) => $query->whereNull('icon')->orWhere('icon', ''))
                    ->update(['icon' => $item['icon']]);
            }
        }

        $hero = HomeContent::where('section', 'long_stay_hero')->orderBy('sort_order')->first();

        if ($hero && ! array_filter((array) $hero->features)) {
            $hero->update(['features' => [$page['hero']['tagline']]]);
        }

        $bar = HomeContent::where('section', 'long_stay_bar')->orderBy('sort_order')->first();

        if ($bar && blank($bar->subtitle)
            && $bar->title === 'Stay for a month. Stay for a season. Stay as long as you need.') {
            $bar->update([
                'title' => $page['bar']['text'],
                'subtitle' => $page['bar']['text_second'],
            ]);
        }
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    protected function seed(string $section, int $sortOrder, array $attributes): void
    {
        HomeContent::firstOrCreate(
            ['section' => $section, 'sort_order' => $sortOrder],
            $attributes + ['active' => true],
        );
    }
}
