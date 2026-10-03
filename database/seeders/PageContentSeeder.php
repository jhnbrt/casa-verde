<?php

namespace Database\Seeders;

use App\Http\Controllers\GalleryController;
use App\Models\HomeContent;
use Illuminate\Database\Seeder;

class PageContentSeeder extends Seeder
{
    /**
     * Load the Special Offers, Gallery and Footer content into the admin so it
     * can be edited there. Safe to run more than once: an entry that already
     * exists (same section and position) is never overwritten.
     */
    public function run(): void
    {
        $this->offers();
        $this->gallery();
        $this->footer();
    }

    protected function offers(): void
    {
        $offers = config('offers');

        $this->seed('offers_header', 1, [
            'subtitle' => $offers['header']['eyebrow'],
            'title' => $offers['header']['title'],
            'description' => $offers['header']['text'],
        ]);

        foreach ($offers['items'] as $index => $item) {
            $this->seed('offers', $index + 1, [
                'title' => $item['title'],
                'subtitle' => $item['highlight'],
                'description' => $item['text'],
                'image' => $item['image'],
                'icon' => $item['icon'],
                'button_text' => $item['button_text'],
                'button_url' => $item['button_url'],
            ]);
        }

        $this->seed('offers_note', 1, ['description' => $offers['note']]);
    }

    protected function gallery(): void
    {
        [$overview, $sections] = app(GalleryController::class)->defaultLayout();

        $this->seed('gallery_header', 1, [
            'subtitle' => 'Gallery',
            'title' => 'A visual journey through Casa Verde.',
            'description' => 'Discover the villas, sunsets, flavors and quiet island moments that shape every stay.',
        ]);

        foreach ($overview as $index => $tile) {
            $this->seed('gallery_overview', $index + 1, [
                'title' => $tile['label'],
                'image' => $tile['img'],
            ]);
        }

        $position = 0;

        foreach ($sections as $key => $section) {
            $this->seed('gallery_headings', ++$position, [
                'title' => $section['title'],
                'subtitle' => $section['subtitle'],
            ]);

            foreach ($section['items'] as $index => $item) {
                $this->seed("gallery_{$key}", $index + 1, [
                    'title' => $item['alt'],
                    'subtitle' => $item['label'] ?? null,
                    'icon' => $item['icon'] ?? null,
                    'image' => $item['img'],
                ]);
            }
        }
    }

    protected function footer(): void
    {
        $footer = config('footer');

        $this->seed('footer_cta', 1, [
            'title' => $footer['cta']['title'],
            'button_text' => $footer['cta']['button_text'],
            'button_url' => $footer['cta']['button_url'],
        ]);

        $this->seed('footer_brand', 1, [
            'title' => $footer['brand']['title'],
            'subtitle' => $footer['brand']['subtitle'],
        ]);

        foreach (['explore' => 'footer_explore', 'useful' => 'footer_links', 'legal' => 'footer_legal'] as $key => $section) {
            foreach ($footer[$key] as $index => $link) {
                $this->seed($section, $index + 1, [
                    'title' => $link['title'],
                    'button_url' => $link['url'],
                ]);
            }
        }

        foreach (['contact' => 'footer_contact', 'social' => 'footer_social'] as $key => $section) {
            foreach ($footer[$key] as $index => $link) {
                $this->seed($section, $index + 1, [
                    'title' => $link['title'],
                    'icon' => $link['icon'],
                    'button_url' => $link['url'] ?: null,
                ]);
            }
        }

        $this->seed('footer_newsletter', 1, [
            'title' => $footer['newsletter']['title'],
            'description' => $footer['newsletter']['text'],
        ]);

        $this->seed('footer_bottom', 1, ['title' => $footer['bottom']]);
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
