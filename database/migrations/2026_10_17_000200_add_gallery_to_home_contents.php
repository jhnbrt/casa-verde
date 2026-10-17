<?php

use App\Models\HomeContent;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Each villa gets its own list of gallery photos, edited on the villa entry in the admin
 * (the photos that open when a visitor clicks the villa picture on the Villas page).
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('home_contents', 'gallery')) {
            Schema::table('home_contents', function (Blueprint $table) {
                $table->text('gallery')->nullable()->after('features');
            });
        }

        HomeContent::fillVillaGalleries();
    }

    public function down(): void
    {
        if (Schema::hasColumn('home_contents', 'gallery')) {
            Schema::table('home_contents', function (Blueprint $table) {
                $table->dropColumn('gallery');
            });
        }
    }
};
