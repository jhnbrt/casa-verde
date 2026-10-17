<?php

use App\Models\HomeContent;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;

/**
 * The Premium Villa's beds are ONE highlight, "Queen Bed & Twin Beds", showing two different
 * icons side by side (queen bed + twin beds). The first icon is the entry's Icon, the second
 * is its "Second icon". Works whether the entry is still the original single one or was
 * split in two by an earlier version. Safe to run twice.
 */
return new class extends Migration
{
    public function up(): void
    {
        $queenPlaceholder = 'images/icons/placeholders/queen-bed.svg';
        $queenIcon = file_exists(public_path($queenPlaceholder)) ? $queenPlaceholder : 'queen-bed';

        $rows = HomeContent::where('section', 'villas_highlights')
            ->orderBy('sort_order')
            ->get()
            ->filter(fn (HomeContent $row): bool => Str::slug((string) $row->subtitle) === 'premium-villa')
            ->values();

        $queen = $rows->first(fn (HomeContent $row): bool => preg_match('/^\s*queen\s*bed\s*$/i', (string) $row->title) === 1);
        $twin = $rows->first(fn (HomeContent $row): bool => preg_match('/^\s*twin\s*beds?\s*$/i', (string) $row->title) === 1);
        $combined = $rows->first(fn (HomeContent $row): bool => preg_match('/queen\s*bed\s*(&|and|\+)\s*twin\s*beds?/i', (string) $row->title) === 1);

        if ($queen && $twin) {
            // Put the two entries back together: queen icon first, twin icon second.
            $queen->update([
                'title' => 'Queen Bed & Twin Beds',
                'image' => $twin->icon,
            ]);

            $gap = (int) $twin->sort_order;
            $twin->delete();

            HomeContent::where('section', 'villas_highlights')->where('sort_order', '>', $gap)->decrement('sort_order');

            return;
        }

        if ($combined && blank($combined->image)) {
            // Original single entry: its current icon becomes the twin beds icon, the queen bed icon is added in front.
            $combined->update([
                'image' => $combined->icon,
                'icon' => $queenIcon,
            ]);
        }
    }

    public function down(): void
    {
        // Intentionally empty: removing content is not something to undo silently.
    }
};
