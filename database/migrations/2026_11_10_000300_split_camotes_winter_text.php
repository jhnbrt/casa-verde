<?php

use App\Models\HomeContent;
use Illuminate\Database\Migrations\Migration;

/**
 * The "Camotes Island" tab shows its long description below the photos, like on the slide.
 * A line with only --- in the tab text marks the split. This updates the text loaded by the
 * first version, but only while it is still exactly the original (nothing edited is touched).
 */
return new class extends Migration
{
    public function up(): void
    {
        $original = "Ein ruhiger, sicherer Ort fernab vom Massentourismus – ideal für einen entspannten und sorgenfreien Aufenthalt.\n\nCamotes Island gehört zu den ruhigeren, ursprünglichen Inseln der Philippinen. Fernab vom Trubel großer Touristenzentren finden Sie hier eine sichere und entspannte Umgebung. Weiße Strände, klares Wasser und beeindruckende Natur schaffen den perfekten Rahmen für einen erholsamen Aufenthalt – ganz ohne Hektik.\n\nGleichzeitig profitieren Sie von einer guten Anbindung an Cebu und medizinischer Versorgung in erreichbarer Nähe.";

        $updated = collect(config('winter.tabs'))->firstWhere('label', 'Camotes Island')['text'] ?? null;

        if ($updated === null) {
            return;
        }

        HomeContent::where('section', 'winter_tabs')
            ->where('title', 'Camotes Island')
            ->where('description', $original)
            ->update(['description' => $updated]);
    }

    public function down(): void
    {
        // Intentionally empty.
    }
};
