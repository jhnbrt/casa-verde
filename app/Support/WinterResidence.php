<?php

namespace App\Support;

use App\Models\HomeContent;
use Illuminate\Support\Collection;
use Throwable;

/**
 * Content of the Winterresidenz page (German long-stay offer): the admin-managed "winter_*"
 * entries over the defaults in config/winter.php.
 */
class WinterResidence
{
    public const SECTIONS = [
        'winter', 'winter_tabs', 'winter_cards', 'winter_lists', 'winter_prices', 'winter_badges', 'winter_contact',
    ];

    /**
     * The text of the page's link in the header dropdown (and null once the heading entry has been
     * switched off in the admin: the page, its menu link and its Long Stay banner all disappear together).
     */
    public static function menuLabel(): ?string
    {
        static $label = false;

        if ($label !== false && ! app()->runningUnitTests()) {
            return $label;
        }

        $default = (string) config('winter.header.menu', 'Winterresidenz');

        try {
            $entries = HomeContent::where('section', 'winter')->orderBy('sort_order')->get();

            if ($entries->isEmpty()) {
                return $label = $default;
            }

            $row = $entries->where('active', true)->first();

            return $label = $row === null ? null : (trim((string) $row->button_text) !== '' ? trim((string) $row->button_text) : $default);
        } catch (Throwable) {
            return $label = $default;
        }
    }

    public static function isVisible(): bool
    {
        return static::menuLabel() !== null;
    }

    /**
     * The tabs as "number => name", for the Tab choice in the admin forms of the cards, lists,
     * prices, badges and contact lines (the number is the tab's position number).
     *
     * @return array<int, string>
     */
    public static function tabChoices(): array
    {
        try {
            $rows = HomeContent::where('section', 'winter_tabs')->orderBy('sort_order')->get();
        } catch (Throwable) {
            $rows = collect();
        }

        if ($rows->isEmpty()) {
            return collect(config('winter.tabs'))->mapWithKeys(fn (array $tab): array => [(int) $tab['key'] => (string) $tab['label']])->all();
        }

        return $rows->values()->mapWithKeys(fn (HomeContent $row, int $index): array => [(int) ($row->sort_order ?: $index + 1) => (string) $row->title])->all();
    }

    /**
     * @return array<string, mixed>|null  null when the page has been switched off in the admin
     */
    public static function content(): ?array
    {
        try {
            $rows = HomeContent::query()
                ->whereIn('section', self::SECTIONS)
                ->orderBy('sort_order')
                ->get()
                ->groupBy('section');
        } catch (Throwable) {
            $rows = collect();
        }

        return static::build($rows, config('winter'));
    }

    /**
     * The Winterresidenz page: a heading and tabs,
     * each tab with its own text, photo cards, lists, prices, badges and contact lines. Everything
     * comes from the admin "winter_*" entries over the defaults in config/winter.php. The
     * page is hidden when its heading entry is switched off. Cards, lists, prices, badges and
     * contact lines belong to a tab through their "Tab" field (the tab's number or its label).
     *
     * @param  Collection<string, Collection<int, HomeContent>>  $rows  every Winterresidenz entry grouped by section
     * @param  array<string, mixed>  $defaults
     * @return array<string, mixed>|null
     */
    protected static function build(Collection $rows, array $defaults): ?array
    {
        $active = fn (string $section): ?Collection => $rows->has($section)
            ? $rows->get($section)->where('active', true)->values()
            : null;

        $headRows = $active('winter');

        if ($headRows === null) {
            $header = $defaults['header'];
        } elseif (($head = $headRows->first()) === null) {
            return null;
        } else {
            $header = [
                'eyebrow' => (string) $head->subtitle,
                'title' => $head->title ?: $defaults['header']['title'],
                'text' => (string) $head->description,
                'menu' => trim((string) $head->button_text) !== '' ? trim((string) $head->button_text) : ($defaults['header']['menu'] ?? 'Winterresidenz'),
            ];
        }

        // Children of the tabs, as plain arrays tagged with the tab they belong to ("panel").
        $children = fn (string $section, array $fallback, callable $map): array => ($list = $active($section)) === null
            ? $fallback
            : $list->map(fn (HomeContent $row): array => ['panel' => trim((string) $row->subtitle)] + $map($row))->all();

        $cards = $children('winter_cards', $defaults['cards'], fn (HomeContent $row): array => [
            'title' => (string) $row->title,
            'text' => (string) $row->description,
            'image' => (string) $row->image,
        ]);

        $lists = $children('winter_lists', $defaults['lists'], fn (HomeContent $row): array => [
            'title' => (string) $row->title,
            'icon' => (string) $row->icon,
            'marker' => trim((string) $row->button_text) !== '' ? trim((string) $row->button_text) : '✓',
            'items' => (array) $row->features,
        ]);

        $prices = $children('winter_prices', $defaults['prices'], fn (HomeContent $row): array => [
            'title' => (string) $row->title,
            'price' => $row->price,
            'format' => trim((string) $row->button_text) !== '' ? trim((string) $row->button_text) : 'ab {price} € pro Monat',
            'note' => (string) $row->description,
        ]);

        $badges = $children('winter_badges', $defaults['badges'], fn (HomeContent $row): array => [
            'title' => (string) $row->title,
            'icon' => (string) $row->icon,
            'text' => (string) $row->description,
        ]);

        $contact = $children('winter_contact', $defaults['contact'], fn (HomeContent $row): array => [
            'title' => (string) $row->title,
            'icon' => (string) $row->icon,
            'url' => (string) $row->button_url,
        ]);

        $tabRows = $active('winter_tabs');

        $tabs = $tabRows === null
            ? $defaults['tabs']
            : $tabRows->map(fn (HomeContent $row, int $index): array => [
                'key' => (int) ($row->sort_order ?: $index + 1),
                'label' => (string) $row->title,
                'heading' => (string) $row->subtitle,
                'text' => (string) $row->description,
                'image' => (string) $row->image,
                'ticks' => (array) $row->features,
                'closing' => (string) $row->button_text,
            ])->all();

        $belongs = fn (array $tab) => fn (array $child): bool => $child['panel'] !== ''
            && (strtolower((string) $child['panel']) === (string) $tab['key']
                || strtolower((string) $child['panel']) === strtolower(trim((string) $tab['label'])));

        $tabs = array_map(fn (array $tab): array => $tab + [
            'cards' => array_values(array_filter($cards, $belongs($tab))),
            'lists' => array_values(array_filter($lists, $belongs($tab))),
            'prices' => array_values(array_filter($prices, $belongs($tab))),
            'badges' => array_values(array_filter($badges, $belongs($tab))),
            'contact' => array_values(array_filter($contact, $belongs($tab))),
        ], $tabs);

        return ['header' => $header, 'tabs' => $tabs];
    }

}
