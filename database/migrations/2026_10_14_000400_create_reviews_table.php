<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Reviews written by guests through the form on the Reviews page.
 * (The reviews edited by hand in the admin stay in home_contents.)
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->string('name', 60);
            $table->string('place', 80)->nullable();
            $table->unsignedTinyInteger('rating');
            $table->text('comment');
            $table->boolean('is_published')->default(true);
            $table->timestamps();

            $table->index(['is_published', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
