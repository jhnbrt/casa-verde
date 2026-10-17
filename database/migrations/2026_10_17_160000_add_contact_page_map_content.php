<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        $now = now();
        foreach ([
            'contact_page' => [
                'title' => 'Get in touch',
                'subtitle' => 'CONTACT US',
                'description' => 'We would love to help you plan your stay at Casa Verde Cliff Resort & Spa.',
            ],
            'contact_map' => [
                'title' => 'Find us on the map',
                'subtitle' => 'Mankahilo, Consuelo, Camotes Islands, Cebu 6050, Philippines',
                'description' => 'https://www.google.com/maps?q=Casa%20Verde%20Cliff%20Resort%20and%20Spa%2C%20Mankahilo%2C%20Consuelo%2C%20Camotes%2C%20Cebu&t=k&z=17&output=embed',
                'button_text' => 'Open in Google Maps',
                'button_url' => 'https://www.google.com/maps/search/?api=1&query=Casa%20Verde%20Cliff%20Resort%20and%20Spa%20Camotes%20Cebu',
            ],
        ] as $section => $values) {
            if (!DB::table('home_contents')->where('section', $section)->exists()) {
                DB::table('home_contents')->insert(array_merge([
                    'section' => $section, 'sort_order' => 1, 'active' => true,
                    'created_at' => $now, 'updated_at' => $now,
                ], $values));
            }
        }
    }
    public function down(): void
    {
        // Preserve content edited by the site administrator.
    }
};
