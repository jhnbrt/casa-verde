<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * The wellness heading lost its line breaks when it was saved from a
     * single-line admin field. Put the three lines back.
     */
    public function up(): void
    {
        DB::table('home_contents')
            ->where('section', 'wellness_intro')
            ->where('title', 'Restore.Renew.Rebalance.')
            ->update(['title' => "Restore.\nRenew.\nRebalance."]);
    }

    public function down(): void
    {
        //
    }
};
