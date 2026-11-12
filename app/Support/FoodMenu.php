<?php

namespace App\Support;

use App\Models\HomeContent;
use Illuminate\Support\Collection;

/**
 * The pages of the food menu book on the Dining page, built from the admin:
 * categories (the page headings), dishes (name, price, text, photo, dietary icons) and optional
 * extra pages that are a single picture. Two dishes fit on a page, large and easy to read; a new dish or category
 * simply adds pages.
 */
class FoodMenu
{
    public const PER_PAGE = 2;

    /** Dietary icons: name => [label, svg content on a 24 x 24 grid]. */
    public const ICONS = [
        'seafood' => ['Seafood', '<path d="M2 12c3-4.6 7.2-6.2 11.2-5.1 2 .5 3.7 1.7 4.9 3.1l3.6-2.6v9.2L18.1 14c-1.2 1.4-2.9 2.6-4.9 3.1C9.2 18.2 5 16.6 2 12Zm12.2-1.4a1 1 0 1 0 0 2 1 1 0 0 0 0-2Z" fill-rule="evenodd"/>'],
        'pork' => ['Pork', '<path d="M3.5 10.2C4.6 6.8 8.4 5 12.6 5.4c4.2.4 7.9 3 7.9 6.6 0 3.7-3.9 7-8.6 7-4.6 0-9.4-3.3-8.4-8.8Zm8.1.3a2.6 2.6 0 1 0 0 5.2 2.6 2.6 0 0 0 0-5.2Z" fill-rule="evenodd"/>'],
        'chili' => ['Chili', '<path d="M16.6 3.2c.2-.9 1.2-1.5 2.2-1.2l-.9 2.2c2 1.6 2.6 4.5 1.1 7.6-2.4 5-7.4 8.4-14.3 8.5 4.5-1.9 7-4.3 8.9-8.7 1.1-2.7 2.4-4.4 3-8.4Z"/>'],
        'chicken' => ['Chicken', '<path d="M14.6 3.4a5.6 5.6 0 0 1 4 9.5l-1 1 2.6 2.6a1.7 1.7 0 1 1-2.4 2.3l-.3-.3-.2.7a1.6 1.6 0 1 1-2.3-1.8L12 14.5l-1 .1a5.6 5.6 0 0 1 3.6-11.2Z"/>'],
        'beef' => ['Beef', '<path d="M2.5 5.2 6 6.9C7.4 6.3 9.5 6 12 6s4.6.3 6 .9l3.5-1.7-1.1 4.5-1 .8c.2 1 .3 1.5.2 2.3L19 15c-.3 2.6-2.7 4.5-7 4.5S5.3 17.600 5 15l-.1-1.700c-.1-.8 0-1.300.2-2.300l-1-.8-1.600-5Zm6.500 8a1.100 1.100 0 1 0 0 2.200 1.100 1.100 0 0 0 0-2.200Zm6 0a1.100 1.100 0 1 0 0 2.200 1.100 1.100 0 0 0 0-2.200Z" fill-rule="evenodd"/>'],
        'nuts' => ['Nuts', '<circle cx="8" cy="9.200" r="3.700"/><circle cx="16" cy="9.200" r="3.700"/><circle cx="12" cy="15.600" r="4"/>'],
        'cheese' => ['Cheese', '<path d="m3 11 10.600-7L21 9.200V18H3v-7Zm11.300 3a1.700 1.700 0 1 0 0 3.400 1.700 1.700 0 0 0 0-3.400ZM7.300 14.600a1.200 1.200 0 1 0 0 2.400 1.200 1.200 0 0 0 0-2.400Z" fill-rule="evenodd"/>'],
    ];

    /**
     * The pages in reading order. Each page is either
     * ['type' => 'dishes', 'heading' => string, 'dishes' => [...]] or ['type' => 'image', 'image' => path, 'title' => string].
     *
     * @param  Collection<string, Collection<int, HomeContent>>  $contents  active rows grouped by section
     * @return array<int, array<string, mixed>>
     */
    public static function pages(Collection $contents): array
    {
        // The approved printed-menu references are the source of truth for this book.
        // A title can only appear once, in its correct category, regardless of stale
        // category links, old uploaded full-page images, or duplicate database rows.
        $approved = [
            'Mains' => ['Chicken Parmy Katsu Style', 'Chicken Schnitzel Cordon Bleu', 'Buttered Garlic Shrimp', 'Salmon Steak', 'Beef Goulash', 'Chicken Curry'],
            'Filipino' => ['Chopsuey', 'Stir Fried Noodles', 'Beef Pochero', 'Spring Fried Chicken'],
            'Kids' => ['Fried Chicken Nibblers', 'Mac & Cheese'],
            'Burgers and Sandwiches' => ['Casa Verde Monster Burger', 'Crazy Devil Chicken Burger', 'Sunset Clubhouse Sandwich'],
            'Pasta' => ['Seafood Linguini', 'Carbonara', 'Agli Olio'],
            'Pizza' => ['Quatroformaggi', 'Meat Lovers', 'Prosciutto de Parma', 'Hawaiian', 'Vegetarian', 'Margherita'],
            'Soup' => ['Mushroom Creme Soup', 'Pumpkin Soup', 'Wanton Soup'],
            'Salad' => ['Caesar Salad', 'Chicken Roasted Sesame Salad'],
            'Appetizer' => ['Fish Ceviche', 'Crispy Calamari', 'French Fries', 'Gambas Al Ajillo', 'Spring Roll'],
        ];

        $dishes = HomeContent::pageRows($contents, 'dining_page_food_menu_dishes')
            ->filter(fn (HomeContent $row): bool => trim((string) $row->title) !== '');
        $byName = [];
        foreach ($dishes as $dish) {
            $key = static::menuTitleKey((string) $dish->title);
            // Keep the first active admin row; never render duplicated dish records.
            if (! isset($byName[$key])) {
                $byName[$key] = $dish;
            }
        }

        $icons = static::icons($contents);
        $cover = HomeContent::pageRow($contents, 'dining_page_food_menu');
        $brand = [
            'text' => filled($cover?->subtitle) ? trim((string) $cover->subtitle) : 'Casa Verde Cliff Resort & Spa',
            'icon' => trim((string) $cover?->icon),
        ];

        $pages = [];
        foreach ($approved as $heading => $names) {
            $group = collect($names)
                ->map(fn (string $name) => $byName[static::menuTitleKey($name)] ?? null)
                ->filter()
                ->values();

            foreach ($group->chunk(self::PER_PAGE) as $chunk) {
                $pages[] = [
                    'type' => 'dishes',
                    'kicker' => '',
                    'heading' => $heading,
                    'dishes' => $chunk->map(fn (HomeContent $dish): array => static::dish($dish, $icons))->values()->all(),
                    'legend' => array_values($icons),
                    'brand' => $brand,
                ];
            }
        }

        return $pages;
    }

    /** Normalize minor spelling/spacing differences without changing displayed names. */
    protected static function menuTitleKey(string $title): string
    {
        $title = strtolower(trim($title));
        $title = str_replace(['&', 'quattro', 'crème'], ['and', 'quatro', 'creme'], $title);
        return preg_replace('/[^a-z0-9]+/', '', $title) ?? '';
    }

    /**
     * The dietary icons, keyed by the name used in a dish's icon list. Each is ['label', 'svg' | null, 'image' | null]:
     * an uploaded image wins, then the built-in drawing. The admin can rename, replace, reorder or add icons.
     *
     * @param  Collection<string, Collection<int, HomeContent>>  $contents
     * @return array<string, array{label: string, svg: ?string, image: ?string}>
     */
    public static function icons(Collection $contents): array
    {
        $rows = HomeContent::pageRows($contents, 'dining_page_food_menu_icons');
        $icons = [];

        foreach ($rows as $row) {
            $key = strtolower(trim((string) $row->title));

            if ($key === '') {
                continue;
            }

            $icon = static::resolveIcon($key, trim((string) $row->subtitle), $row->image);

            if ($icon !== null) {
                $icons[$key] = $icon;
            }
        }

        return $icons;
    }

    /** @return array{label: string, svg: ?string, image: ?string}|null */
    protected static function resolveIcon(string $key, string $label, mixed $image): ?array
    {
        $image = filled($image) && file_exists(public_path((string) $image)) ? (string) $image : null;
        $svg = static::ICONS[$key][1] ?? null;

        if ($image === null && $svg === null) {
            return null;
        }

        return [
            'label' => $label !== '' ? $label : (static::ICONS[$key][0] ?? ucfirst($key)),
            'svg' => $svg,
            'image' => $image,
        ];
    }

    /** A dish belongs to a category through the category's id (saved in the admin) or, from the starting content, its name. */
    protected static function belongsTo(HomeContent $dish, HomeContent $category): bool
    {
        return static::linkMatches((string) $dish->subtitle, $category);
    }

    protected static function linkMatches(string $link, HomeContent $category): bool
    {
        $link = trim($link);

        if ($link === '') {
            return false;
        }

        return ($category->id !== null && $link === (string) $category->id)
            || strcasecmp($link, trim((string) $category->title)) === 0;
    }

    /**
     * @param  array<string, array{label: string, svg: ?string, image: ?string}>  $icons
     * @return array<string, mixed>
     */
    protected static function dish(HomeContent $dish, array $icons): array
    {
        $price = $dish->price;

        if (is_numeric($price)) {
            $price = (float) $price == floor((float) $price)
                ? number_format((float) $price, 0, '.', ',')
                : number_format((float) $price, 2, '.', ',');
        }

        $dishIcons = collect((array) $dish->features)
            ->map(fn ($name): string => strtolower(trim((string) $name)))
            ->filter(fn (string $name): bool => $name !== '')
            ->unique()
            ->map(fn (string $name): ?array => $icons[$name] ?? static::resolveIcon($name, '', null))
            ->filter()
            ->values()
            ->all();

        $image = filled($dish->image) && file_exists(public_path($dish->image)) ? $dish->image : null;
        $shape = 'round';

        if ($image !== null && ($size = @getimagesize(public_path($image))) && $size[1] > 0) {
            $ratio = $size[0] / $size[1];
            $shape = $ratio >= 0.88 && $ratio <= 1.14 ? 'round' : 'soft';
        }

        return [
            'name' => (string) $dish->title,
            'price' => $price === null || $price === '' ? '' : (string) $price,
            'text' => (string) $dish->description,
            'image' => $image,
            'shape' => $shape,
            'icons' => $dishIcons,
        ];
    }
}
