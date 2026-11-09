<?php

namespace App\Support;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class VillaPhotos
{
    /**
     * The photos that open in each villa's gallery, keyed by the villa's slug. They come from
     * the "Gallery photos" box on the villa entry in the admin; a villa without any uses its
     * card photo, wide photo and gallery photos. The Villas page and the Home page's
     * "Our villas" section both use this, so they always show the same pictures.
     *
     * @param  Collection<int, \App\Models\HomeContent>  $villas
     * @return array<string, array<int, string>>
     */
    public static function for(Collection $villas): array
    {
        $exists = fn ($path): bool => is_string($path) && filled($path) && file_exists(public_path($path));
        $photos = [];

        foreach ($villas as $villa) {
            $slug = Str::slug($villa->title);

            $list = ! empty($villa->gallery)
                ? (array) $villa->gallery
                : array_merge([$villa->image, config("villas.{$slug}.hero")], (array) config("villas.{$slug}.gallery", []));

            $photos[$slug] = array_values(array_unique(array_filter($list, $exists)));

            if ($photos[$slug] === [] && filled($villa->image)) {
                $photos[$slug] = [$villa->image];
            }
        }

        return $photos;
    }
}
