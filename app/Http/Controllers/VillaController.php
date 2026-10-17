<?php

namespace App\Http\Controllers;

use App\Models\HomeContent;
use Illuminate\Support\Str;

class VillaController extends Controller
{
    public function index()
    {
        $contents = HomeContent::where('active', true)
            ->orderBy('sort_order')
            ->get()
            ->groupBy('section');

        $site = $contents
            ->get('site', collect())
            ->first();

        $villasHeader = $contents
            ->get('villas_header', collect())
            ->first();

        $villas = $contents
            ->get('villas', collect());

        $villaPhotos = $this->photos($contents, $villas);

        return view('villa', compact(
            'contents',
            'site',
            'villasHeader',
            'villas',
            'villaPhotos'
        ));
    }

    /**
     * The photos of each villa card, keyed by the villa's slug. The admin list
     * (Villas > "Villa card: Photos") wins; without it the card photo comes first,
     * followed by the villa's gallery photos.
     *
     * @return array<string, array<int, string>>
     */
    protected function photos($contents, $villas): array
    {
        $own = $contents->get('villas_photos', collect())->groupBy(fn ($row) => Str::slug((string) $row->title));
        $exists = fn (?string $path): bool => filled($path) && file_exists(public_path($path));
        $photos = [];

        foreach ($villas as $villa) {
            $slug = Str::slug($villa->title);

            $list = $own->has($slug)
                ? $own->get($slug)->pluck('image')->all()
                : array_merge([$villa->image, config("villas.{$slug}.hero")], (array) config("villas.{$slug}.gallery", []));

            $photos[$slug] = array_values(array_unique(array_filter($list, $exists)));

            if ($photos[$slug] === [] && filled($villa->image)) {
                $photos[$slug] = [$villa->image];
            }
        }

        return $photos;
    }

    public function show(string $slug)
    {
        $contents = HomeContent::where('active', true)
            ->orderBy('sort_order')
            ->get()
            ->groupBy('section');

        $site = $contents->get('site', collect())->first();

        $villa = $contents
            ->get('villas', collect())
            ->first(fn ($item) => Str::slug($item->title) === $slug);

        abort_if(! $villa, 404);

        // Rich detail content (highlights, amenities, floor plan...) lives in
        // config/villas.php; fall back to the DB values if a villa has none.
        $detail = config("villas.{$slug}", []);

        $features = is_string($villa->features)
            ? json_decode($villa->features, true)
            : $villa->features;

        return view('villa-show', [
            'contents' => $contents,
            'site'     => $site,
            'villa'    => $villa,
            'detail'   => $detail,
            'intro'    => $detail['intro'] ?? $villa->description,
            'tagline'  => $detail['tagline'] ?? $villa->subtitle,
            'heroImage' => $detail['hero'] ?? $villa->image,
            'features' => $features ?? [],
        ]);
    }
}
