<?php

namespace Database\Seeders;

use App\Models\HomeContent;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class VillaPhotosSeeder extends Seeder
{
    /**
     * Load the photos of each villa card (its card photo, then its gallery) into the admin
     * so they can be changed there. Safe to run more than once; nothing is added twice.
     */
    public function run(): void
    {
        if (HomeContent::where('section', 'villas_photos')->exists()) {
            return;
        }

        $order = 0;

        foreach (HomeContent::where('section', 'villas')->orderBy('sort_order')->get() as $villa) {
            $slug = Str::slug((string) $villa->title);
            $photos = array_merge([$villa->image, config("villas.{$slug}.hero")], (array) config("villas.{$slug}.gallery", []));

            foreach (array_values(array_unique(array_filter($photos))) as $photo) {
                if (! file_exists(public_path($photo))) {
                    continue;
                }

                HomeContent::create([
                    'section' => 'villas_photos',
                    'title' => $villa->title,
                    'image' => $photo,
                    'sort_order' => ++$order,
                    'active' => true,
                ]);
            }
        }
    }
}
