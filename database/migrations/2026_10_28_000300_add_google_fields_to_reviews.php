<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Lets the reviews table also hold reviews copied from Google.
 * Website reviews keep source = "website"; Google ones are "google" and carry Google's own id,
 * so running the sync again updates them instead of creating duplicates.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->string('source', 20)->default('website')->after('id');
            $table->string('external_id', 191)->nullable()->unique()->after('source');
            $table->timestamp('reviewed_at')->nullable()->after('comment');
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropUnique(['external_id']);
            $table->dropColumn(['source', 'external_id', 'reviewed_at']);
        });
    }
};
