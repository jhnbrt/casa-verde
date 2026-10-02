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

        return view('villa', compact(
            'contents',
            'site',
            'villasHeader',
            'villas'
        ));
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
