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
    protected function secondIcon(array $highlight): ?string
    {
        $second = $highlight['icon2'] ?? null;
        $placeholder = $second ? 'images/icons/placeholders/'.$second.'.svg' : null;

        return $placeholder && file_exists(public_path($placeholder)) ? $placeholder : null;
    }

    protected function seedVillaHighlights(): void
    {
        $position = (int) HomeContent::where('section', 'villas_highlights')->max('sort_order');

        foreach (config('villas', []) as $slug => $detail) {
            // A villa that already has highlight entries keeps exactly the ones saved in the admin.
            if (HomeContent::where('section', 'villas_highlights')->where('subtitle', \Illuminate\Support\Str::headline($slug))->exists()) {
                continue;
            }

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
                        // Optional second icon shown next to the first (stored in the entry's picture field).
                        'image' => $this->secondIcon($highlight),
                        'active' => true,
                    ],
                );
            }
        }
    }
}
