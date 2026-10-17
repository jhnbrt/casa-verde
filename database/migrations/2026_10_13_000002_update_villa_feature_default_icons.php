<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        $icons = [
            'Standard Villa' => ['couple', 'leaf', 'tropical-home'],
            'Premium Villa' => ['privacy-lock', 'sunset', 'star'],
        ];
        foreach ($icons as $villa => $names) {
            foreach ($names as $i => $icon) {
                DB::table('home_contents')
                    ->where('section', 'villas_feature_icons')
                    ->where('title', $villa)
                    ->where('sort_order', $i + 1)
                    ->whereIn('icon', ['♧', '', 'club'])
                    ->update(['icon' => $icon, 'updated_at' => now()]);
            }
        }
    }
    public function down(): void {}
};
