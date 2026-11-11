<?php

namespace Database\Seeders;

use App\Models\HomeContent;
use Illuminate\Database\Seeder;

/**
 * Loads the Winterresidenz page (and its Long Stay banner) from config/winter.php into the
 * admin so it can be edited there. A section that already has entries is left alone, so
 * nothing the client has edited is ever overwritten.
 */
class WinterResidenceSeeder extends Seeder
{
    public function run(): void
    {
        $winter = config('winter');

        $this->seed('winter', [[
            'title' => $winter['header']['title'],
            'subtitle' => $winter['header']['eyebrow'],
            'description' => $winter['header']['text'],
            'button_text' => $winter['header']['menu'] ?? 'Winterresidenz',
        ]]);

        $this->seed('winter_tabs', array_map(fn (array $tab): array => [
            'title' => $tab['label'],
            'subtitle' => $tab['heading'],
            'description' => $tab['text'],
            'image' => $tab['image'],
            'features' => $tab['ticks'],
            'button_text' => $tab['closing'],
            'sort_order' => $tab['key'],
        ], $winter['tabs']));

        $this->seed('winter_cards', array_map(fn (array $card): array => [
            'title' => $card['title'] !== '' ? $card['title'] : null,
            'subtitle' => (string) $card['panel'],
            'description' => $card['text'] !== '' ? $card['text'] : null,
            'image' => $card['image'],
        ], $winter['cards']));

        $this->seed('winter_lists', array_map(fn (array $list): array => [
            'title' => $list['title'],
            'subtitle' => (string) $list['panel'],
            'icon' => $list['icon'],
            'features' => $list['items'],
            'button_text' => $list['marker'],
        ], $winter['lists']));

        $this->seed('winter_prices', array_map(fn (array $price): array => [
            'title' => $price['title'],
            'subtitle' => (string) $price['panel'],
            'price' => $price['price'],
            'button_text' => $price['format'],
            'description' => $price['note'],
        ], $winter['prices']));

        $this->seed('winter_badges', array_map(fn (array $badge): array => [
            'title' => $badge['title'],
            'subtitle' => (string) $badge['panel'],
            'icon' => $badge['icon'],
            'description' => ($badge['text'] ?? '') !== '' ? $badge['text'] : null,
        ], $winter['badges']));

        $teaser = $winter['teaser'];

        $this->seed('long_stay_winter', [[
            'title' => $teaser['title'],
            'subtitle' => $teaser['eyebrow'],
            'description' => $teaser['text'],
            'image' => $teaser['image'],
            'button_text' => $teaser['button_text'],
            'button_url' => $teaser['button_url'],
        ]]);

        $this->seed('winter_contact', array_map(fn (array $line): array => [
            'title' => $line['title'],
            'subtitle' => (string) $line['panel'],
            'icon' => $line['icon'] !== '' ? $line['icon'] : null,
            'button_url' => $line['url'] !== '' ? $line['url'] : null,
        ], $winter['contact']));
    }

    /**
     * @param  array<int, array<string, mixed>>  $rows
     */
    protected function seed(string $section, array $rows): void
    {
        if (HomeContent::where('section', $section)->exists()) {
            return;
        }

        foreach (array_values($rows) as $index => $attributes) {
            HomeContent::create($attributes + [
                'section' => $section,
                'sort_order' => $index + 1,
                'active' => true,
            ]);
        }
    }
}
