<?php

namespace App\Http\Controllers;

use App\Models\HomeContent;
use Illuminate\Support\Collection;

class GalleryController extends Controller
{
    /** Category order of the six sections, matching "Category headings" in the admin. */
    private const CATEGORIES = ['resort', 'villas', 'dining', 'wellness', 'experiences', 'sunsets'];

    public function index()
    {
        $contents = HomeContent::where('active', true)
            ->orderBy('sort_order')
            ->get()
            ->groupBy('section');

        $site = $contents->get('site', collect())->first();

        [$overview, $sections] = $this->layout($contents);

        $headerRow = $contents->get('gallery_header', collect())->first();
        $header = [
            'eyebrow' => $headerRow?->subtitle ?: 'Gallery',
            'title' => $headerRow?->title ?: 'A visual journey through Casa Verde.',
            'text' => $headerRow?->description ?: 'Discover the villas, sunsets, flavors and quiet island moments that shape every stay.',
        ];

        return view('gallery', compact('contents', 'site', 'header', 'overview', 'sections'));
    }

    /**
     * Gallery layout: the built-in defaults, replaced by whatever has been
     * entered in the admin ("gallery_*" sections). Image paths become URLs here.
     *
     * @param  Collection<string, Collection<int, HomeContent>>  $contents
     * @return array{0: array<int, array<string, mixed>>, 1: array<string, array<string, mixed>>}
     */
    private function layout(Collection $contents): array
    {
        [$overview, $sections] = $this->defaultLayout();

        // Overview mosaic: each tile keeps its category; the admin row with the
        // same position replaces its photo and label.
        $tiles = $contents->get('gallery_overview', collect())->keyBy('sort_order');

        if ($tiles->isNotEmpty()) {
            $overview = collect($overview)
                ->map(function (array $base, int $index) use ($tiles): ?array {
                    $row = $tiles->get($index + 1);

                    return $row ? [
                        'cat' => $base['cat'],
                        'label' => $row->title ?: $base['label'],
                        'img' => $row->image ?: $base['img'],
                    ] : null;
                })
                ->filter()
                ->values()
                ->all();
        }

        // Category titles and intro lines, by position.
        $headings = $contents->get('gallery_headings', collect())->keyBy('sort_order');

        foreach (self::CATEGORIES as $index => $key) {
            if ($row = $headings->get($index + 1)) {
                $sections[$key]['title'] = $row->title ?: $sections[$key]['title'];
                $sections[$key]['subtitle'] = $row->subtitle ?: $sections[$key]['subtitle'];
            }

            // Photos: when the admin has any for this category, they are the gallery.
            $rows = $contents->get("gallery_{$key}", collect())->filter(fn (HomeContent $row): bool => filled($row->image));

            if ($rows->isNotEmpty()) {
                $sections[$key]['items'] = $rows->map(function (HomeContent $row) use ($sections, $key): array {
                    $item = [
                        'img' => $row->image,
                        'alt' => $row->title ?: $sections[$key]['title'],
                    ];

                    if (filled($row->subtitle)) {
                        $item['label'] = $row->subtitle;
                        $item['icon'] = $row->icon ?: 'island';
                    }

                    return $item;
                })->values()->all();
            }
        }

        $overview = array_map(fn (array $tile): array => ['img' => $this->url($tile['img'])] + $tile, $overview);

        foreach ($sections as $key => $section) {
            $sections[$key]['items'] = array_map(
                fn (array $item): array => ['img' => $this->url($item['img'])] + $item,
                $section['items']
            );
        }

        return [$overview, $sections];
    }

    /**
     * Built-in layout (image paths relative to public/). Also used to fill the
     * admin the first time. Every slot lists image candidates in priority
     * order: drop real photos into public/images/gallery/<category>/ and they
     * are picked up automatically.
     */
    public function defaultLayout(): array
    {
        $overview = [
            ['cat' => 'sunsets',     'label' => 'Sunsets',    'img' => $this->pickPath('overview/sunsets', ['images/main.jpg'])],
            ['cat' => 'villas',      'label' => 'Villas',     'img' => $this->pickPath('overview/villas', ['images/standard.jpg'])],
            ['cat' => 'wellness',    'label' => 'Wellness',   'img' => $this->pickPath('overview/wellness', ['images/wellness/hero.jpg'])],
            ['cat' => 'dining',      'label' => 'Dining',     'img' => $this->pickPath('overview/dining', ['images/dining3.jpg'])],
            ['cat' => 'resort',      'label' => 'Resort',     'img' => $this->pickPath('overview/resort-1', ['images/about-resort.jpg'])],
            ['cat' => 'resort',      'label' => 'Resort',     'img' => $this->pickPath('overview/resort-2', ['images/contact-hero.jpg'])],
            ['cat' => 'experiences', 'label' => 'Experience', 'img' => $this->pickPath('overview/experience', ['images/exp2.jpg'])],
        ];

        $sections = [
            'resort' => [
                'title' => 'The Resort',
                'subtitle' => 'Cliffside views, tropical gardens and quiet corners of Casa Verde.',
                'layout' => 'resort',
                'items' => [
                    ['img' => $this->pickPath('resort/1', ['images/long-stay.jpg']), 'alt' => 'Casa Verde from above at dusk'],
                    ['img' => $this->pickPath('resort/2', ['images/about-hero.jpg']), 'alt' => 'Sunset over the cliff deck'],
                    ['img' => $this->pickPath('resort/3', ['images/about-resort.jpg']), 'alt' => 'Resort grounds and pool'],
                    ['img' => $this->pickPath('resort/4', ['images/contact-hero.jpg']), 'alt' => 'Villas and garden'],
                    ['img' => $this->pickPath('resort/5', ['images/exp3.jpg']), 'alt' => 'Coastline near the resort'],
                ],
            ],
            'villas' => [
                'title' => 'Villas',
                'subtitle' => 'Private spaces designed for comfort, calm and island living.',
                'layout' => 'villas',
                'items' => [
                    ['img' => $this->pickPath('villas/1', ['images/premium.jpg']), 'alt' => 'Premium Villa'],
                    ['img' => $this->pickPath('villas/2', ['images/standard.jpg']), 'alt' => 'Standard Villa'],
                    ['img' => $this->pickPath('villas/3', ['images/standard.jpg']), 'alt' => 'Villa terrace'],
                    ['img' => $this->pickPath('villas/4', ['images/premium.jpg']), 'alt' => 'Villa exterior'],
                    ['img' => $this->pickPath('villas/5', ['images/about-resort.jpg']), 'alt' => 'Villas among the gardens'],
                    ['img' => $this->pickPath('villas/6', ['images/contact-hero.jpg']), 'alt' => 'Villas seen from above'],
                ],
            ],
            'dining' => [
                'title' => 'Dining',
                'subtitle' => 'Flavors, sunsets and tables made for slow evenings.',
                'layout' => 'dining',
                'items' => [
                    ['img' => $this->pickPath('dining/1', ['images/dining3.jpg']), 'alt' => 'The restaurant at sunset'],
                    ['img' => $this->pickPath('dining/2', ['images/dining1.jpg']), 'alt' => 'Sunset dinner for two'],
                    ['img' => $this->pickPath('dining/3', ['images/dining2.jpg']), 'alt' => 'Private dining setup'],
                    ['img' => $this->pickPath('dining/4', ['images/dining4.jpg']), 'alt' => 'Cocktails at the bar'],
                    ['img' => $this->pickPath('dining/5', ['images/dining/dining-hero.jpg']), 'alt' => 'Freshly prepared dishes'],
                ],
            ],
            'wellness' => [
                'title' => 'Wellness',
                'subtitle' => 'Soft rituals, natural textures and quiet moments of renewal.',
                'layout' => 'wellness',
                'items' => [
                    ['img' => $this->pickPath('wellness/1', ['images/wellness1.jpg']), 'alt' => 'Spa essentials'],
                    ['img' => $this->pickPath('wellness/2', ['images/wellness/hero.jpg']), 'alt' => 'Spa treatment room'],
                    ['img' => $this->pickPath('wellness/3', ['images/wellness/package.jpg']), 'alt' => 'Massage and flower bath'],
                ],
            ],
            'experiences' => [
                'title' => 'Experiences',
                'subtitle' => 'Island days shaped by water, movement and discovery.',
                'layout' => 'experiences',
                'items' => [
                    ['img' => $this->pickPath('experiences/1', ['images/exp2.jpg']), 'alt' => 'Hidden cave', 'label' => 'Cave Exploration', 'icon' => 'cave'],
                    ['img' => $this->pickPath('experiences/2', ['images/experiences/snorkeling.jpg']), 'alt' => 'Snorkeling', 'label' => 'Snorkeling Adventure', 'icon' => 'snorkel'],
                    ['img' => $this->pickPath('experiences/3', ['images/experiences/padel.jpg']), 'alt' => 'Padel court', 'label' => 'Padel Match', 'icon' => 'padel'],
                    ['img' => $this->pickPath('experiences/4', ['images/exp3.jpg']), 'alt' => 'Island hopping', 'label' => 'Island Hopping', 'icon' => 'island'],
                    ['img' => $this->pickPath('experiences/5', ['images/experiences/hero.jpg']), 'alt' => 'Sunset yoga on the deck'],
                ],
            ],
            'sunsets' => [
                'title' => 'Sunsets',
                'subtitle' => 'Golden hour, every evening, in its own quiet way.',
                'layout' => 'sunsets',
                'items' => [
                    ['img' => $this->pickPath('sunsets/1', ['images/about-hero.jpg']), 'alt' => 'Sunset over the sea'],
                    ['img' => $this->pickPath('sunsets/2', ['images/main.jpg']), 'alt' => 'Sunset from the villa balcony'],
                    ['img' => $this->pickPath('sunsets/3', ['images/exp1.jpg']), 'alt' => 'Watching the sun go down'],
                    ['img' => $this->pickPath('sunsets/4', ['images/dining1.jpg']), 'alt' => 'Sunset toast'],
                ],
            ],
        ];

        return [$overview, $sections];
    }

    private function pickPath(string $slot, array $fallbacks): string
    {
        foreach (['jpg', 'jpeg', 'webp', 'png'] as $ext) {
            $path = "images/gallery/{$slot}.{$ext}";

            if (file_exists(public_path($path))) {
                return $path;
            }
        }

        foreach ($fallbacks as $path) {
            if (file_exists(public_path($path))) {
                return $path;
            }
        }

        return 'images/main.jpg';
    }

    /**
     * Public URL for an image path; a missing file falls back to a safe photo.
     */
    private function url(string $path): string
    {
        return asset(file_exists(public_path($path)) ? $path : 'images/main.jpg');
    }
}
