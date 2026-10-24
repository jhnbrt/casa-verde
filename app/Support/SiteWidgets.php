<?php

namespace App\Support;

use App\Models\HomeContent;
use Illuminate\Support\Str;

/**
 * What the chat bubble and the counter-offer popup need on every public page, read from the
 * admin entries (Chat & offers) over the starting values in config/aichat.php.
 */
class SiteWidgets
{
    /**
     * @return array{chat: array<string, mixed>|null, offer: array<string, mixed>|null, rules: array<string, mixed>}
     */
    public static function data(): array
    {
        $defaults = config('aichat');

        try {
            $rows = HomeContent::query()
                ->whereIn('section', ['ai_chat', 'chat_faq', 'offer_popup', 'offer_popup_rules'])
                ->orderBy('sort_order')
                ->get()
                ->groupBy('section');
        } catch (\Throwable) {
            $rows = collect();
        }

        $chatRow = $rows->get('ai_chat', collect())->first();
        $offerRow = $rows->get('offer_popup', collect())->first();
        $ruleRows = $rows->get('offer_popup_rules', collect())->values();

        $rule = fn (int $index) => $ruleRows->get($index) && filled($ruleRows->get($index)->price)
            ? (float) $ruleRows->get($index)->price
            : (float) $defaults['rules'][$index]['value'];

        // The chat needs an entry that is switched on and an API key on the server.
        $chat = null;

        $hasQuickAnswers = $rows->get('chat_faq', collect())->contains(fn ($row) => $row->active);

        if ((AiProvider::ready() || $hasQuickAnswers) && ($chatRow ? $chatRow->active : true)) {
            $suggestions = $chatRow ? array_values((array) $chatRow->features) : $defaults['chat']['suggestions'];

            $chat = [
                'name' => $chatRow?->title ?: $defaults['chat']['name'],
                'greeting' => $chatRow?->subtitle ?: $defaults['chat']['greeting'],
                'suggestions' => array_slice(array_values(array_filter($suggestions, 'filled')), 0, 5),
                'button_text' => $chatRow?->button_text ?: $defaults['chat']['button_text'],
                'icon' => HomeContent::customIconImage($chatRow?->icon),
                'avatar' => $chatRow && filled($chatRow->image) && file_exists(public_path($chatRow->image)) ? $chatRow->image : null,
                'url' => route('ai-chat.reply'),
                'mode' => AiProvider::ready() ? 'ai' : 'quick',
            ];
        }

        // The offer only appears once it has been switched on in the admin.
        $offer = null;

        if ($offerRow && $offerRow->active) {
            $image = $offerRow->image;

            $offer = [
                'label' => $offerRow->subtitle ?: $defaults['offer']['label'],
                'title' => $offerRow->title ?: $defaults['offer']['title'],
                'text' => $offerRow->description ?: $defaults['offer']['text'],
                'code' => trim((string) (($offerRow->features[0] ?? '') ?: '')),
                'button_text' => $offerRow->button_text ?: $defaults['offer']['button_text'],
                'button_url' => $offerRow->button_url ?: $defaults['offer']['button_url'],
                'decline' => $defaults['offer']['decline'],
                'image' => filled($image) && file_exists(public_path($image)) ? $image : null,
            ];
        }

        return [
            'chat' => $chat,
            'offer' => $offer,
            'rules' => [
                'seconds' => max(0, (int) $rule(0)),
                'days' => max(0, (int) $rule(1)),
                'returning' => $rule(2) >= 1,
                'exit' => $rule(3) >= 1,
            ],
        ];
    }

    /**
     * Link target for a button: full address, page anchor or site path.
     */
    public static function link(?string $url): string
    {
        return Str::startsWith((string) $url, ['http', '#', 'mailto:', 'tel:']) ? (string) $url : url($url ?: '/#contact');
    }
}
