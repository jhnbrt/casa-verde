<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('home_contents')
            ->where('section', 'home_labels')
            ->where('sort_order', 6)
            ->whereIn('icon', ['✧', ''])
            ->update(['icon' => 'images/icons/ico-lotus.png']);
    }

    public function down(): void
    {
        // Keep the selected decoration when rolling back.
    }
};
