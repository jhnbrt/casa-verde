<?php

namespace App\Support;

use App\Models\HomeContent;
use Throwable;

/**
 * Lists offered as a dropdown instead of a typed value in the admin forms
 * (the "select_source" of a section in config/admin.php).
 */
class AdminChoices
{
    /**
     * @return array{label: string, options: array<int|string, string>}
     */
    public static function for(string $source): array
    {
        return match ($source) {
            'winter_tabs' => ['label' => 'Choose a tab…', 'options' => WinterResidence::tabChoices()],
            'menu_categories' => ['label' => 'Choose a category…', 'options' => static::menuCategories()],
            default => ['label' => 'Choose…', 'options' => []],
        };
    }

    /**
     * Menu categories as "id => name" (or "name => name" before they are saved in the admin).
     *
     * @return array<int|string, string>
     */
    protected static function menuCategories(): array
    {
        try {
            $rows = HomeContent::where('section', 'dining_page_food_menu_categories')->orderBy('sort_order')->get();
        } catch (Throwable) {
            $rows = collect();
        }

        if ($rows->isEmpty()) {
            return collect(config('pagecontent.dining_page_food_menu_categories', []))
                ->mapWithKeys(fn (array $row): array => [(string) $row['title'] => (string) $row['title']])
                ->all();
        }

        return $rows->mapWithKeys(fn (HomeContent $row): array => [$row->id => (string) $row->title])->all();
    }
}
