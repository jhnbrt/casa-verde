<?php

namespace Database\Seeders;

use App\Models\HomeContent;
use Illuminate\Database\Seeder;

class ReviewContentSeeder extends Seeder
{
    /**
     * Load the Reviews page mock-up content into the admin without touching
     * any other content. Safe to run more than once.
     */
    public function run(): void
    {
        $reviews = config('reviews');

        $this->seed('reviews_header', 1, [
            'subtitle' => $reviews['header']['eyebrow'],
            'title' => $reviews['header']['title'],
            'description' => $reviews['header']['text'],
            'button_text' => $reviews['header']['button_text'],
            'button_url' => $reviews['header']['button_url'],
            'price' => $reviews['header']['autoplay'],
        ]);

        foreach ($reviews['items'] as $index => $item) {
            $this->seed('reviews_items', $index + 1, [
                'title' => $item['name'],
                'subtitle' => $item['place'],
                'description' => $item['quote'],
                'price' => $item['rating'],
                'image' => $item['image'],
                'icon' => $item['avatar'],
            ]);
        }

        foreach ($reviews['stats'] as $index => $stat) {
            $this->seed('reviews_stats', $index + 1, [
                'title' => $stat['value'],
                'subtitle' => $stat['label'],
            ]);
        }

        // Icons start empty: the built-in drawings show until a picture is uploaded.
        foreach (['Star', 'Previous arrow', 'Next arrow'] as $index => $label) {
            $this->seed('reviews_icons', $index + 1, ['title' => $label]);
        }

        $this->seedNavigation();
    }

    /**
     * Add "Reviews" to the main menu, just before About, unless it is already there.
     */
    protected function seedNavigation(): void
    {
        $links = HomeContent::where('section', 'navigation')->orderBy('sort_order')->get();

        if ($links->contains(fn (HomeContent $link): bool => strtolower(trim((string) $link->title)) === 'reviews')) {
            return;
        }

        $about = $links->first(fn (HomeContent $link): bool => strtolower(trim((string) $link->title)) === 'about');
        $position = $about?->sort_order ?? (($links->max('sort_order') ?? 0) + 1);

        if ($about) {
            HomeContent::where('section', 'navigation')->where('sort_order', '>=', $position)->increment('sort_order');
        }

        HomeContent::create([
            'section' => 'navigation',
            'title' => 'REVIEWS',
            'button_url' => '/reviews',
            'sort_order' => $position,
            'active' => true,
        ]);
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
