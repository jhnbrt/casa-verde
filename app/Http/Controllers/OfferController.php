<?php

namespace App\Http\Controllers;

use App\Models\HomeContent;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class OfferController extends Controller
{
    public function index(): View
    {
        $contents = HomeContent::where('active', true)
            ->orderBy('sort_order')
            ->get()
            ->groupBy('section');

        $site = $contents->get('site', collect())->first();
        $page = $this->buildPage($contents, config('offers'));

        return view('offers', compact('contents', 'site', 'page'));
    }

    /**
     * Merge admin-managed "offers*" content over the defaults in config/offers.php.
     *
     * @param  Collection<string, Collection<int, HomeContent>>  $contents
     * @param  array<string, mixed>  $defaults
     * @return array<string, mixed>
     */
    protected function buildPage(Collection $contents, array $defaults): array
    {
        $header = $contents->get('offers_header', collect())->first();
        $rows = $contents->get('offers', collect());

        $items = $rows->isNotEmpty()
            ? $rows->map(fn (HomeContent $row): array => $this->fromRow($row))->all()
            : array_map(fn (array $item): array => $this->fromDefault($item), $defaults['items']);

        return [
            'header' => [
                'eyebrow' => $header?->subtitle ?: $defaults['header']['eyebrow'],
                'title' => $header?->title ?: $defaults['header']['title'],
                'text' => $header?->description ?: $defaults['header']['text'],
            ],
            'items' => $items,
            'note' => $contents->get('offers_note', collect())->first()?->description ?: $defaults['note'],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function fromRow(HomeContent $row): array
    {
        return $this->item(
            $row->title,
            $row->icon,
            $row->description,
            $row->subtitle,
            $row->image,
            $row->button_text,
            $row->button_url,
        );
    }

    /**
     * @param  array<string, mixed>  $item
     * @return array<string, mixed>
     */
    protected function fromDefault(array $item): array
    {
        return $this->item(
            $item['title'],
            $item['icon'],
            $item['text'],
            $item['highlight'],
            $item['image'],
            $item['button_text'],
            $item['button_url'],
        );
    }

    /**
     * The highlight is stored as two lines: a bold perk, then the smaller detail text.
     *
     * @return array<string, mixed>
     */
    protected function item(?string $title, ?string $icon, ?string $text, ?string $highlight, ?string $image, ?string $buttonText, ?string $buttonUrl): array
    {
        $lines = preg_split('/\R/', trim((string) $highlight), 2) ?: [];

        return [
            'title' => $title,
            'icon' => $icon,
            'text' => $text,
            'perk' => trim($lines[0] ?? ''),
            'perk_text' => trim($lines[1] ?? ''),
            'image' => $image,
            'button_text' => $buttonText ?: 'View offer',
            'button_url' => $buttonUrl ?: '/#contact',
        ];
    }
}
