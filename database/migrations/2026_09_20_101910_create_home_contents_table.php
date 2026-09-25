<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('home_contents', function (Blueprint $table) {
            $table->id();

            $table->string('section');
            $table->string('title')->nullable();
            $table->text('subtitle')->nullable();
            $table->text('description')->nullable();

            $table->string('image')->nullable();
            $table->string('icon')->nullable();

            $table->decimal('price', 10, 2)->nullable();

            $table->text('features')->nullable();

            $table->string('button_text')->nullable();
            $table->string('button_url')->nullable();

            $table->integer('sort_order')->default(0);
            $table->boolean('active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('home_contents');
    }
};