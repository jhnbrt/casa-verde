<?php

namespace Database\Seeders;

use App\Models\HomeContent;
use Illuminate\Database\Seeder;

class PageContentDefaultsSeeder extends Seeder
{
    /**
     * Load the Dining, Experiences, Wellness and Villas page content (and a few Home
     * labels) from config/pagecontent.php into the admin. Existing entries are never
     * overwritten, so this is safe to run more than once.
     */
    public function run(): void
    {
        foreach (config('pagecontent', []) as $section => $rows) {
            foreach (array_values($rows) as $index => $attributes) {
                HomeContent::firstOrCreate(
                    ['section' => $section, 'sort_order' => $index + 1],
                    $attributes + ['active' => true],
                );
            }
        }

        $this->seedVillaHighlights();
    }

    /**
     * Each villa's icon row (config/villas.php) becomes editable entries: label, villa name,
     * and an icon image (placeholder until the real icons arrive).
     */
    protected function seedVillaHighlights(): void
    {
        $position = 0;

        foreach (config('villas', []) as $slug => $detail) {
            foreach ($detail['highlights'] ?? [] as $highlight) {
                $position++;

                $icon = $highlight['icon'] ?? 'lotus';
                $placeholder = 'images/icons/placeholders/'.$icon.'.svg';

                HomeContent::firstOrCreate(
                    ['section' => 'villas_highlights', 'sort_order' => $position],
                    [
                        'title' => $highlight['label'] ?? '',
                        'subtitle' => \Illuminate\Support\Str::headline($slug),
                        'icon' => file_exists(public_path($placeholder)) ? $placeholder : $icon,
                        'active' => true,
                    ],
                );
            }
        }
    }
}
