<?php

namespace App\Http\Controllers;

use App\Models\HomeContent;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(): View
    {
        $contents = HomeContent::where('active', true)
            ->orderBy('sort_order')
            ->get()
            ->groupBy('section');

        $site = $contents->get('site', collect())->first();
        $page = $this->buildPage($contents, config('events'));

        return view('events', compact('contents', 'site', 'page'));
    }

    /**
     * Merge admin-managed "events_*" content over the mock-up defaults.
     *
     * @param  Collection<string, Collection<int, HomeContent>>  $contents
     * @param  array<string, mixed>  $defaults
     * @return array<string, mixed>
     */
    protected function buildPage(Collection $contents, array $defaults): array
    {
        $featureItems = $contents->get('events_features', collect());
        $ctaRows = $contents->get('events_cta', collect());
        $typeRows = $contents->get('events_types', collect());

        return [
            'hero' => $this->single($contents, 'events_hero', $defaults['hero']),
            'intro' => $this->single($contents, 'events_intro', $defaults['intro']),
            'types' => $typeRows->isNotEmpty()
                ? $typeRows->map(fn (HomeContent $row): array => $this->fromRow($row, []))->all()
                : $defaults['types'],
            'features' => [
                'title' => $contents->get('events_features_header', collect())->first()?->title
                    ?: $defaults['features']['title'],
                'items' => $featureItems->isNotEmpty()
                    ? $featureItems->map(fn (HomeContent $row): array => $this->fromRow($row, []))->all()
                    : $defaults['features']['items'],
            ],
            'personal' => $this->single($contents, 'events_personal', $defaults['personal']),
            'cta' => $ctaRows->isEmpty() ? $defaults['cta'] : [
                'title' => $ctaRows->first()->title ?: $defaults['cta']['title'],
                'image' => $ctaRows->first()->image ?: $defaults['cta']['image'],
                'buttons' => $ctaRows
                    ->filter(fn (HomeContent $row): bool => filled($row->button_text))
                    ->map(fn (HomeContent $row): array => [
                        'text' => $row->button_text,
                        'url' => $row->button_url ?: '/#contact',
                    ])
                    ->values()
                    ->all(),
            ],
        ];
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
}
