<?php

namespace App\Http\Controllers;

use App\Models\HomeContent;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class YogaController extends Controller
{
    public function index(): View
    {
        $contents = HomeContent::where('active', true)
            ->orderBy('sort_order')
            ->get()
            ->groupBy('section');

        $site = $contents->get('site', collect())->first();
        $page = $this->buildPage($contents, config('yoga'));

        return view('yoga', compact('contents', 'site', 'page'));
    }

    /**
     * Merge admin-managed "yoga_*" content over the mock-up defaults.
     *
     * @param  Collection<string, Collection<int, HomeContent>>  $contents
     * @param  array<string, mixed>  $defaults
     * @return array<string, mixed>
     */
    protected function buildPage(Collection $contents, array $defaults): array
    {
        $featureItems = $contents->get('yoga_features', collect());
        $ctaRows = $contents->get('yoga_cta', collect());
        $typeRows = $contents->get('yoga_types', collect());

        $statRows = $contents->get('yoga_stats', collect());
        $scheduleRows = $contents->get('yoga_schedule', collect());
        $scheduleHeader = $contents->get('yoga_schedule_header', collect())->first();

        return [
            'hero' => $this->single($contents, 'yoga_hero', $defaults['hero']),
            'stats' => $statRows->isNotEmpty()
                ? $statRows->map(fn (HomeContent $row): array => $this->fromRow($row, []))->all()
                : $defaults['stats'],
            'schedule' => [
                'eyebrow' => $scheduleHeader?->subtitle ?: $defaults['schedule']['eyebrow'],
                'title' => $scheduleHeader?->title ?: $defaults['schedule']['title'],
                'items' => $scheduleRows->isNotEmpty()
                    ? $scheduleRows->map(fn (HomeContent $row): array => $this->fromRow($row, []))->all()
                    : $defaults['schedule']['items'],
            ],
            'quote' => $this->single($contents, 'yoga_quote', $defaults['quote']),
            'intro' => $this->single($contents, 'yoga_intro', $defaults['intro']),
            'types' => $typeRows->isNotEmpty()
                ? $typeRows->map(fn (HomeContent $row): array => $this->fromRow($row, []))->all()
                : $defaults['types'],
            'features' => [
                'title' => $contents->get('yoga_features_header', collect())->first()?->title
                    ?: $defaults['features']['title'],
                'icon' => $contents->get('yoga_features_header', collect())->first()?->icon,
                'items' => $featureItems->isNotEmpty()
                    ? $featureItems->map(fn (HomeContent $row): array => $this->fromRow($row, []))->all()
                    : $defaults['features']['items'],
            ],
            'personal' => $this->single($contents, 'yoga_personal', $defaults['personal']),
            'faq' => [
                'eyebrow' => $contents->get('yoga_faq_header', collect())->first()?->subtitle
                    ?: $defaults['faq']['eyebrow'],
                'title' => $contents->get('yoga_faq_header', collect())->first()?->title
                    ?: $defaults['faq']['title'],
                'items' => $contents->get('yoga_faq', collect())
                    ->filter(fn (HomeContent $row): bool => filled($row->title))
                    ->map(fn (HomeContent $row): array => $this->fromRow($row, []))
                    ->values()
                    ->all(),
            ],
            'teachers' => [
                'eyebrow' => $contents->get('yoga_teachers_header', collect())->first()?->subtitle
                    ?: $defaults['teachers']['eyebrow'],
                'title' => $contents->get('yoga_teachers_header', collect())->first()?->title
                    ?: $defaults['teachers']['title'],
                'items' => $contents->get('yoga_teachers', collect())
                    ->filter(fn (HomeContent $row): bool => filled($row->title))
                    ->map(fn (HomeContent $row): array => $this->fromRow($row, []))
                    ->values()
                    ->all(),
            ],
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
            'price' => $row->price ?? ($defaults['price'] ?? null),
            'button_text' => $row->button_text ?: ($defaults['button_text'] ?? null),
            'button_url' => $row->button_url ?: ($defaults['button_url'] ?? '/#contact'),
        ];
    }
}
