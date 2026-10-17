<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * The round "+" button next to the guest avatars used to open the contact section.
 * It now jumps to the review form. Only the old default is replaced, so a link
 * someone typed in the admin is left alone.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('home_contents')
            ->where('section', 'reviews_header')
            ->where('button_url', '/#contact')
            ->update(['button_url' => '#rv-share']);
    }

    public function down(): void
    {
        DB::table('home_contents')
            ->where('section', 'reviews_header')
            ->where('button_url', '#rv-share')
            ->update(['button_url' => '/#contact']);
    }
};
