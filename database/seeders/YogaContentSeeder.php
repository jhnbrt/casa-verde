<?php

namespace Database\Seeders;

use App\Models\HomeContent;
use Illuminate\Database\Seeder;

class YogaContentSeeder extends Seeder
{
    /**
     * Load the Yoga Reset page mock-up content into the admin without touching
     * any other content. Safe to run more than once.
     */
    public function run(): void
    {
        $yoga = config('yoga');

        $this->seed('yoga_hero', 1, [
            'subtitle' => $yoga['hero']['eyebrow'],
            'title' => $yoga['hero']['title'],
            'description' => $yoga['hero']['text'],
            'image' => $yoga['hero']['image'],
            'button_text' => $yoga['hero']['button_text'],
            'button_url' => $yoga['hero']['button_url'],
        ]);

        foreach ($yoga['stats'] as $index => $stat) {
            $this->seed('yoga_stats', $index + 1, [
                'title' => $stat['title'],
                'subtitle' => $stat['eyebrow'],
                'icon' => $stat['icon'],
            ]);
        }

        $this->seed('yoga_intro', 1, [
            'subtitle' => $yoga['intro']['eyebrow'],
            'title' => $yoga['intro']['title'],
            'description' => $yoga['intro']['text'],
        ]);

        foreach ($yoga['types'] as $index => $type) {
            $this->seed('yoga_types', $index + 1, [
                'title' => $type['title'],
                'subtitle' => $type['eyebrow'] ?? null,
                'description' => $type['text'],
                'image' => $type['image'],
                'icon' => $type['icon'],
                'button_text' => $type['button_text'],
                'button_url' => $type['button_url'],
            ]);
        }

        $this->seed('yoga_schedule_header', 1, [
            'subtitle' => $yoga['schedule']['eyebrow'],
            'title' => $yoga['schedule']['title'],
        ]);

        foreach ($yoga['schedule']['items'] as $index => $slot) {
            $this->seed('yoga_schedule', $index + 1, [
                'title' => $slot['title'],
                'subtitle' => $slot['eyebrow'],
                'description' => $slot['text'],
            ]);
        }

        $this->seed('yoga_features_header', 1, ['title' => $yoga['features']['title']]);

        foreach ($yoga['features']['items'] as $index => $feature) {
            $this->seed('yoga_features', $index + 1, [
                'title' => $feature['title'],
                'description' => $feature['text'],
                'icon' => $feature['icon'],
            ]);
        }

        $this->seed('yoga_personal', 1, [
            'subtitle' => $yoga['personal']['eyebrow'],
            'title' => $yoga['personal']['title'],
            'description' => $yoga['personal']['text'],
            'image' => $yoga['personal']['image'],
            'button_text' => $yoga['personal']['button_text'],
            'button_url' => $yoga['personal']['button_url'],
        ]);

        $this->seed('yoga_quote', 1, [
            'title' => $yoga['quote']['title'],
            'subtitle' => $yoga['quote']['eyebrow'],
            'description' => $yoga['quote']['text'],
        ]);

        $this->seed('yoga_faq_header', 1, [
            'subtitle' => $yoga['faq']['eyebrow'],
            'title' => $yoga['faq']['title'],
        ]);

        foreach ($yoga['faq']['items'] as $index => $item) {
            $this->seed('yoga_faq', $index + 1, [
                'title' => $item['title'],
                'description' => $item['text'],
            ]);
        }

        $this->seed('yoga_teachers_header', 1, [
            'subtitle' => $yoga['teachers']['eyebrow'],
            'title' => $yoga['teachers']['title'],
        ]);

        foreach ($yoga['cta']['buttons'] as $index => $button) {
            $this->seed('yoga_cta', $index + 1, [
                'title' => $index === 0 ? $yoga['cta']['title'] : null,
                'image' => $index === 0 ? $yoga['cta']['image'] : null,
                'button_text' => $button['text'],
                'button_url' => $button['url'],
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
