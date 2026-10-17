<?php

namespace App\Http\Controllers;

use App\Models\HomeContent;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\View\View;

class LongStayController extends Controller
{
    public function index(): View
    {
        $contents = HomeContent::where('active', true)
            ->orderBy('sort_order')
            ->get()
            ->groupBy('section');

        $site = $contents->get('site', collect())->first();
        $page = $this->buildPage($contents, config('longstay'));

        return view('longstay', compact('contents', 'site', 'page'));
    }

    /**
     * Merge admin-managed "long_stay_*" content over the defaults in config/longstay.php.
     *
     * @param  Collection<string, Collection<int, HomeContent>>  $contents
     * @param  array<string, mixed>  $defaults
     * @return array<string, mixed>
     */
    protected function buildPage(Collection $contents, array $defaults): array
    {
        $heroRow = $contents->get('long_stay_hero', collect())->first();
        $hero = $heroRow ? $this->fromRow($heroRow, $defaults['hero']) : $defaults['hero'];
        // Where the banner photo is anchored, "left% top%" (for example 70% 40%); blank = centre.
        $hero['focus'] = preg_match('/^\d{1,3}%\s+\d{1,3}%$/', trim((string) $heroRow?->icon)) ? preg_replace('/\s+/', ' ', trim($heroRow->icon)) : null;
        $hero['tagline'] = $heroRow
            ? (trim((string) ($heroRow->features[0] ?? '')) ?: $defaults['hero']['tagline'])
            : $defaults['hero']['tagline'];

        return [
            'hero' => $hero,
            'strip' => $this->list($contents->get('long_stay_strip', collect()), $defaults['strip']),
            'uses_header' => $this->single($contents, 'long_stay_uses_header', $defaults['uses_header']),
            'uses' => $this->list($contents->get('long_stay_uses', collect()), $defaults['uses']),
            'intro' => $this->single($contents, 'long_stay_intro', $defaults['intro']),
            'panels' => $this->panels($contents, $defaults['panels']),
            'gallery' => $this->gallery($contents->get('long_stay_gallery', collect()), $defaults['gallery']),
            'bar' => $this->bar($contents->get('long_stay_bar', collect())->first(), $defaults['bar']),
        ];
    }

    /**
     * Icon + title + text lists (highlights strip, ways to stay). A row without an icon
     * keeps the built-in icon at the same position.
     *
     * @param  Collection<int, HomeContent>  $rows
     * @param  array<int, array<string, mixed>>  $defaults
     * @return array<int, array<string, mixed>>
     */
    protected function list(Collection $rows, array $defaults): array
    {
        if ($rows->isEmpty()) {
            return $defaults;
        }

        return $rows->values()->map(function (HomeContent $row, int $index) use ($defaults): array {
            $item = $this->fromRow($row, []);
            $item['icon'] = $row->icon ?: ($defaults[$index]['icon'] ?? null);

            return $item;
        })->all();
    }

    /**
     * The three boxes. Each box's list comes from the "Box list items" entries whose
     * "Box" field is the box number (1, 2, 3) or its title; with none, the defaults show.
     *
     * @param  Collection<string, Collection<int, HomeContent>>  $contents
     * @param  array<int, array<string, mixed>>  $defaults
     * @return array<int, array<string, mixed>>
     */
    protected function panels(Collection $contents, array $defaults): array
    {
        $rows = $contents->get('long_stay_panels', collect())->values();
        $items = $contents->get('long_stay_panel_items', collect());

        if ($rows->isEmpty()) {
            $panels = array_map(fn (array $panel): array => $panel + ['key' => null], $defaults);
        } else {
            $panels = $rows->map(fn (HomeContent $row, int $index): array => [
                'title' => (string) $row->title,
                'numbered' => strtolower(trim((string) $row->subtitle)) === 'numbered',
                'items' => array_map(
                    fn (string $text): array => ['text' => $text, 'icon' => null],
                    (array) $row->features,
                ),
            ])->all();
        }

        foreach ($panels as $index => &$panel) {
            $own = $items->filter(function (HomeContent $item) use ($index, $panel): bool {
                $box = strtolower(trim((string) $item->subtitle));

                return $box === (string) ($index + 1) || $box === strtolower(trim($panel['title']));
            });

            if ($own->isNotEmpty()) {
                $panel['items'] = $own->values()->map(fn (HomeContent $item): array => [
                    'text' => (string) $item->title,
                    'icon' => $item->icon,
                ])->all();

                continue;
            }

            // Lists typed into the box itself keep the built-in icons of the same position.
            $fallback = $defaults[$index]['items'] ?? [];

            foreach ($panel['items'] as $position => &$entry) {
                $entry['icon'] = $entry['icon'] ?: ($fallback[$position]['icon'] ?? null);
            }
            unset($entry);
        }
        unset($panel);

        return $panels;
    }

    /**
     * @param  Collection<int, HomeContent>  $rows
     * @param  array<int, array<string, mixed>>  $defaults
     * @return array<int, array<string, mixed>>
     */
    protected function gallery(Collection $rows, array $defaults): array
    {
        $rows = $rows->values();

        return array_map(fn (array $default, int $index): array => [
            'image' => $rows->get($index)?->image ?: $default['image'],
            'alt' => $rows->get($index)?->title ?: $default['alt'],
        ], $defaults, array_keys($defaults));
    }

    /**
     * @param  Collection<string, Collection<int, HomeContent>>  $contents
     * @param  array<string, mixed>  $defaults
     * @return array<string, mixed>
     */
    protected function single(Collection $contents, string $section, array $defaults): array
    {
        $row = $contents->get($section, collect())->first();

        return $row ? $this->fromRow($row, $defaults) : $defaults;
    }

    /**
     * Map an admin row onto the page fields, falling back to the defaults for blanks.
     *
     * @param  array<string, mixed>  $defaults
     * @return array<string, mixed>
     */
    protected function fromRow(HomeContent $row, array $defaults): array
    {
        return [
            'eyebrow' => $row->subtitle ?: ($defaults['eyebrow'] ?? null),
            'title' => $row->title ?: ($defaults['title'] ?? null),
            'text' => $row->description ?: ($defaults['text'] ?? null),
            'image' => $row->image ?: ($defaults['image'] ?? null),
            'icon' => $row->icon ?: ($defaults['icon'] ?? null),
            'button_text' => $row->button_text ?: ($defaults['button_text'] ?? null),
            'button_url' => $row->button_url ?: ($defaults['button_url'] ?? '/#contact'),
        ];
    }

    /**
     * @param  array<string, mixed>  $defaults
     * @return array<string, mixed>
     */
    protected function bar(?HomeContent $row, array $defaults): array
    {
        $text = $row?->title ?: $defaults['text'];

        return [
            'text' => $text,
            // Left empty in the admin, the second (italic) line is only the default when the first line is too.
            'text_second' => $row ? (string) $row->subtitle : $defaults['text_second'],
            'button_text' => $row?->button_text ?: $defaults['button_text'],
            'button_url' => $row?->button_url ?: $defaults['button_url'],
        ];
    }
}
