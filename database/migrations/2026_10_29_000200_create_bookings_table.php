<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 20)->unique();
            $table->string('villa_slug');
            $table->string('villa_title');
            $table->date('check_in');
            $table->date('check_out');
            $table->unsignedSmallInteger('nights');
            $table->unsignedSmallInteger('adults');
            $table->unsignedSmallInteger('children')->default(0);
            $table->string('name');
            $table->string('email');
            $table->string('phone', 40);
            $table->string('country');
            $table->text('special_requests')->nullable();
            $table->decimal('total', 12, 2);
            $table->string('status', 20)->default('pending'); // pending, confirmed, cancelled
            $table->timestamp('terms_accepted_at')->nullable();
            $table->string('cloudbeds_reservation_id')->nullable(); // filled once Cloudbeds is connected
            $table->timestamps();

            $table->index(['status', 'check_in']);
        });

        // Starting rows for the admin (Site content > Booking). Only added when missing.
        $now = now();
        $has = fn (string $section, ?string $title = null) => DB::table('home_contents')
            ->where('section', $section)
            ->when($title, fn ($q) => $q->where('title', $title))
            ->exists();

        if (! $has('booking_page')) {
            DB::table('home_contents')->insert([
                'section' => 'booking_page',
                'title' => 'Reserve your villa',
                'subtitle' => 'BOOK YOUR STAY',
                'description' => 'Choose your villa, pick your dates and send your request. Our team confirms it by email.',
                'sort_order' => 1,
                'active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $villas = DB::table('home_contents')->where('section', 'villas')->orderBy('sort_order')->get();

        foreach ($villas as $i => $villa) {
            if ($has('booking_rates', $villa->title)) {
                continue;
            }

            $rate = config('booking.rates.'.Str::slug($villa->title), []);

            DB::table('home_contents')->insert([
                'section' => 'booking_rates',
                'title' => $villa->title,
                'subtitle' => isset($rate['regular']) ? (string) $rate['regular'] : (string) (int) $villa->price,
                'price' => $rate['promo'] ?? null,
                'description' => config('booking.terms'),
                'button_text' => $rate['from'] ?? null,
                'button_url' => $rate['to'] ?? null,
                'sort_order' => $i + 1,
                'active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
        DB::table('home_contents')->whereIn('section', ['booking_page', 'booking_rates'])->delete();
    }
};
