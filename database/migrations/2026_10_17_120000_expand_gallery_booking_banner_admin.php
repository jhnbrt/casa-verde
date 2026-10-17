<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        $row = DB::table('home_contents')->where('section', 'gallery_cta_icon')->orderBy('id')->first();
        if (! $row) {
            DB::table('home_contents')->insert([
                'section' => 'gallery_cta_icon',
                'title' => 'Ready to experience it in person?',
                'icon' => 'leaf',
                'sort_order' => 1,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } elseif ($row->title === 'Gallery booking banner icon') {
            DB::table('home_contents')->where('id', $row->id)
                ->update(['title' => 'Ready to experience it in person?', 'updated_at' => now()]);
        }
    }
    public function down(): void {}
};
