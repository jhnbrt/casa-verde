<?php

namespace App\Http\Controllers;

use App\Models\HomeContent;

class GalleryController extends Controller
{
    public function index()
    {
        $contents = HomeContent::where('active', true)
            ->orderBy('sort_order')
            ->get()
            ->groupBy('section');

        $site = $contents->get('site', collect())->first();

        [$overview, $sections] = $this->layout();

        return view('gallery', compact('contents', 'site', 'overview', 'sections'));
    }

    /**
     * Gallery layout. Every slot lists image candidates in priority order:
     * drop real photos into public/images/gallery/<category>/ and they are
     * picked up automatically; until then the closest existing image is used.
     */
    private function layout(): array
    {
        $overview = [
            ['cat' => 'sunsets',     'label' => 'Sunsets',    'img' => $this->pick('overview/sunsets', ['images/main.jpg'])],
            ['cat' => 'villas',      'label' => 'Villas',     'img' => $this->pick('overview/villas', ['images/standard.jpg'])],
            ['cat' => 'wellness',    'label' => 'Wellness',   'img' => $this->pick('overview/wellness', ['images/wellness/hero.jpg'])],
            ['cat' => 'dining',      'label' => 'Dining',     'img' => $this->pick('overview/dining', ['images/dining3.jpg'])],
            ['cat' => 'resort',      'label' => 'Resort',     'img' => $this->pick('overview/resort-1', ['images/about-resort.jpg'])],
            ['cat' => 'resort',      'label' => 'Resort',     'img' => $this->pick('overview/resort-2', ['images/contact-hero.jpg'])],
            ['cat' => 'experiences', 'label' => 'Experience', 'img' => $this->pick('overview/experience', ['images/exp2.jpg'])],
        ];

        $sections = [
            'resort' => [
                'title' => 'The Resort',
                'subtitle' => 'Cliffside views, tropical gardens and quiet corners of Casa Verde.',
                'layout' => 'resort',
                'items' => [
                    ['img' => $this->pick('resort/1', ['images/long-stay.jpg']), 'alt' => 'Casa Verde from above at dusk'],
                    ['img' => $this->pick('resort/2', ['images/about-hero.jpg']), 'alt' => 'Sunset over the cliff deck'],
                    ['img' => $this->pick('resort/3', ['images/about-resort.jpg']), 'alt' => 'Resort grounds and pool'],
                    ['img' => $this->pick('resort/4', ['images/contact-hero.jpg']), 'alt' => 'Villas and garden'],
                    ['img' => $this->pick('resort/5', ['images/exp3.jpg']), 'alt' => 'Coastline near the resort'],
                ],
            ],
            'villas' => [
                'title' => 'Villas',
                'subtitle' => 'Private spaces designed for comfort, calm and island living.',
                'layout' => 'villas',
                'items' => [
                    ['img' => $this->pick('villas/1', ['images/premium.jpg']), 'alt' => 'Premium Villa'],
                    ['img' => $this->pick('villas/2', ['images/standard.jpg']), 'alt' => 'Standard Villa'],
                    ['img' => $this->pick('villas/3', ['images/standard.jpg']), 'alt' => 'Villa terrace'],
                    ['img' => $this->pick('villas/4', ['images/premium.jpg']), 'alt' => 'Villa exterior'],
                    ['img' => $this->pick('villas/5', ['images/about-resort.jpg']), 'alt' => 'Villas among the gardens'],
                    ['img' => $this->pick('villas/6', ['images/contact-hero.jpg']), 'alt' => 'Villas seen from above'],
                ],
            ],
            'dining' => [
                'title' => 'Dining',
                'subtitle' => 'Flavors, sunsets and tables made for slow evenings.',
                'layout' => 'dining',
                'items' => [
                    ['img' => $this->pick('dining/1', ['images/dining3.jpg']), 'alt' => 'The restaurant at sunset'],
                    ['img' => $this->pick('dining/2', ['images/dining1.jpg']), 'alt' => 'Sunset dinner for two'],
                    ['img' => $this->pick('dining/3', ['images/dining2.jpg']), 'alt' => 'Private dining setup'],
                    ['img' => $this->pick('dining/4', ['images/dining4.jpg']), 'alt' => 'Cocktails at the bar'],
                    ['img' => $this->pick('dining/5', ['images/dining/dining-hero.jpg']), 'alt' => 'Freshly prepared dishes'],
                ],
            ],
            'wellness' => [
                'title' => 'Wellness',
                'subtitle' => 'Soft rituals, natural textures and quiet moments of renewal.',
                'layout' => 'wellness',
                'items' => [
                    ['img' => $this->pick('wellness/1', ['images/wellness1.jpg']), 'alt' => 'Spa essentials'],
                    ['img' => $this->pick('wellness/2', ['images/wellness/hero.jpg']), 'alt' => 'Spa treatment room'],
                    ['img' => $this->pick('wellness/3', ['images/wellness/package.jpg']), 'alt' => 'Massage and flower bath'],
                ],
            ],
            'experiences' => [
                'title' => 'Experiences',
                'subtitle' => 'Island days shaped by water, movement and discovery.',
                'layout' => 'experiences',
                'items' => [
                    ['img' => $this->pick('experiences/1', ['images/exp2.jpg']), 'alt' => 'Hidden cave', 'label' => 'Cave Exploration', 'icon' => 'cave'],
                    ['img' => $this->pick('experiences/2', ['images/experiences/snorkeling.jpg']), 'alt' => 'Snorkeling', 'label' => 'Snorkeling Adventure', 'icon' => 'snorkel'],
                    ['img' => $this->pick('experiences/3', ['images/experiences/padel.jpg']), 'alt' => 'Padel court', 'label' => 'Padel Match', 'icon' => 'padel'],
                    ['img' => $this->pick('experiences/4', ['images/exp3.jpg']), 'alt' => 'Island hopping', 'label' => 'Island Hopping', 'icon' => 'island'],
                    ['img' => $this->pick('experiences/5', ['images/experiences/hero.jpg']), 'alt' => 'Sunset yoga on the deck'],
                ],
            ],
            'sunsets' => [
                'title' => 'Sunsets',
                'subtitle' => 'Golden hour, every evening, in its own quiet way.',
                'layout' => 'sunsets',
                'items' => [
                    ['img' => $this->pick('sunsets/1', ['images/about-hero.jpg']), 'alt' => 'Sunset over the sea'],
                    ['img' => $this->pick('sunsets/2', ['images/main.jpg']), 'alt' => 'Sunset from the villa balcony'],
                    ['img' => $this->pick('sunsets/3', ['images/exp1.jpg']), 'alt' => 'Watching the sun go down'],
                    ['img' => $this->pick('sunsets/4', ['images/dining1.jpg']), 'alt' => 'Sunset toast'],
                ],
            ],
        ];

        return [$overview, $sections];
    }

    private function pick(string $slot, array $fallbacks): string
    {
        foreach (['jpg', 'jpeg', 'webp', 'png'] as $ext) {
            $path = "images/gallery/{$slot}.{$ext}";

            if (file_exists(public_path($path))) {
                return asset($path);
            }
        }

        foreach ($fallbacks as $path) {
            if (file_exists(public_path($path))) {
                return asset($path);
            }
        }

        return asset('images/main.jpg');
    }
}
