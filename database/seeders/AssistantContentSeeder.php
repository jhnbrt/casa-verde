<?php

namespace Database\Seeders;

use App\Models\HomeContent;
use Illuminate\Database\Seeder;

class AssistantContentSeeder extends Seeder
{
    /**
     * Load the AI chat and counter-offer settings into the admin (Chat & offers). Safe to run
     * more than once; anything already saved is left as it is. The popup starts switched off so
     * the sample offer never goes live by accident.
     */
    public function run(): void
    {
        $config = config('aichat');
        $chat = $config['chat'];
        $offer = $config['offer'];

        $this->seed('ai_chat', 1, [
            'title' => $chat['name'],
            'subtitle' => $chat['greeting'],
            'description' => $chat['brain'],
            'features' => $chat['suggestions'],
            'button_text' => $chat['button_text'],
            'button_url' => $chat['booking_url'],
            'price' => $chat['daily_limit'],
            'active' => true,
        ]);

        foreach ($config['faq'] as $index => $faq) {
            $this->seed('chat_faq', $index + 1, [
                'title' => $faq['question'],
                'description' => $faq['answer'],
                'features' => $faq['keywords'],
                'active' => true,
            ]);
        }

        $this->seed('offer_popup', 1, [
            'subtitle' => $offer['label'],
            'title' => $offer['title'],
            'description' => $offer['text'],
            'features' => [$offer['code']],
            'image' => $offer['image'],
            'button_text' => $offer['button_text'],
            'button_url' => $offer['button_url'],
            'active' => false,
        ]);

        foreach ($config['rules'] as $index => $rule) {
            $this->seed('offer_popup_rules', $index + 1, [
                'title' => $rule['title'],
                'price' => $rule['value'],
                'active' => true,
            ]);
        }
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    protected function seed(string $section, int $sortOrder, array $attributes): void
    {
        HomeContent::firstOrCreate(['section' => $section, 'sort_order' => $sortOrder], $attributes);
    }
}
