<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /** Puts the booking page wording, "request received" text and settings into the admin. Only adds what is missing. */
    public function up(): void
    {
        $now = now();

        $add = function (string $section, array $rows) use ($now) {
            if (DB::table('home_contents')->where('section', $section)->exists()) {
                return;
            }

            foreach (array_values($rows) as $i => $row) {
                DB::table('home_contents')->insert($row + [
                    'section' => $section,
                    'sort_order' => $i + 1,
                    'active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        };

        foreach (['booking_labels', 'booking_confirmation', 'booking_settings'] as $section) {
            $rows = config("pagecontent.{$section}", []);

            if ($section === 'booking_settings') {
                $rows[0]['title'] = (string) config('booking.notify', '');
                $rows[0]['subtitle'] = (string) config('booking.max_nights', 30);
                $rows[0]['price'] = config('booking.max_guests', 10);
            }

            $add($section, $rows);
        }
    }

    public function down(): void
    {
        DB::table('home_contents')->whereIn('section', ['booking_labels', 'booking_confirmation', 'booking_settings'])->delete();
    }
};
