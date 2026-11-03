<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** A second, smaller icon for entries that need one (the offer cards' highlight line). */
    public function up(): void
    {
        if (! Schema::hasColumn('home_contents', 'perk_icon')) {
            Schema::table('home_contents', function (Blueprint $table) {
                $table->string('perk_icon')->nullable()->after('icon');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('home_contents', 'perk_icon')) {
            Schema::table('home_contents', function (Blueprint $table) {
                $table->dropColumn('perk_icon');
            });
        }
    }
};
