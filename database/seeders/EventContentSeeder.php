<?php

namespace Database\Seeders;

use App\Models\HomeContent;
use Illuminate\Database\Seeder;

class EventContentSeeder extends Seeder
{
    /**
     * Load the Events page mock-up content into the admin without touching
     * any other content. Safe to run more than once.
     */
    public function run(): void
    {
        $events = config('events');

        $this->seed('events_hero', 1, [
            'subtitle' => $events['hero']['eyebrow'],
            'title' => $events['hero']['title'],
            'description' => $events['hero']['text'],
            'image' => $events['hero']['image'],
            'button_text' => $events['hero']['button_text'],
            'button_url' => $events['hero']['button_url'],
        ]);

        $this->seed('events_intro', 1, [
            'subtitle' => $events['intro']['eyebrow'],
            'title' => $events['intro']['title'],
            'description' => $events['intro']['text'],
        ]);

        foreach ($events['types'] as $index => $type) {
            $this->seed('events_types', $index + 1, [
                'title' => $type['title'],
                'description' => $type['text'],
                'image' => $type['image'],
                'icon' => $type['icon'],
                'button_text' => $type['button_text'],
                'button_url' => $type['button_url'],
            ]);
        }

        $this->seed('events_features_header', 1, ['title' => $events['features']['title']]);

        foreach ($events['features']['items'] as $index => $feature) {
            $this->seed('events_features', $index + 1, [
                'title' => $feature['title'],
                'description' => $feature['text'],
                'icon' => $feature['icon'],
            ]);
        }

        $this->seed('events_personal', 1, [
            'subtitle' => $events['personal']['eyebrow'],
            'title' => $events['personal']['title'],
            'description' => $events['personal']['text'],
            'image' => $events['personal']['image'],
            'button_text' => $events['personal']['button_text'],
            'button_url' => $events['personal']['button_url'],
        ]);

        foreach ($events['cta']['buttons'] as $index => $button) {
            $this->seed('events_cta', $index + 1, [
                'title' => $index === 0 ? $events['cta']['title'] : null,
                'image' => $index === 0 ? $events['cta']['image'] : null,
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
