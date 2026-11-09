<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Makes sure the Home page Experiences carousel has at least 6 cards. If fewer exist
     * (for example the earlier "add three cards" step was skipped because the site had a
     * different number of cards), the missing ones are added with mock titles and text.
     * Everything is ordinary admin content: Site content > Home page: Experiences > Experience cards.
     * Cards that already exist are never changed, and nothing is added once there are 6 or more.
     */
    public function up(): void
    {
        $existing = DB::table('home_contents')->where('section', 'experiences');

        $have = (clone $existing)->count();

        if ($have === 0 || $have >= 6) {
            return;
        }

        $titles = (clone $existing)->pluck('title')->map(fn ($t) => mb_strtolower((string) $t))->all();
        $order = (int) (clone $existing)->max('sort_order');
        $now = now();

        $standIns = [
            [
                'title' => 'Snorkeling Adventure',
                'description' => 'Float above coral gardens in clear, warm water just off the island.',
                'image' => 'images/20261017_164247_0677384e-ef2b-403d-9eb2-c52a65e193e6_snorkeling.jpg',
            ],
            [
                'title' => 'Padel Match',
                'description' => 'Challenge friends and family on our tropical padel court.',
                'image' => 'images/20261017_164140_7d7898f3-7663-40bc-9835-b19bde194549_pickleball.jpg',
            ],
            [
                'title' => 'Billiards Lounge',
                'description' => 'Unwind with a game and a drink as the evening glow settles over the sea.',
                'image' => 'images/20261017_164148_915cb6ab-8f5f-41f1-8e36-2659df58cb82_billiards.jpg',
            ],
            [
                'title' => 'Open-Air Fitness',
                'description' => 'Start your day with a workout surrounded by palms and fresh sea air.',
                'image' => 'images/20261017_164438_acd35a28-15fa-459d-ab40-c574b71093a5_open-air-fitness.jpg',
            ],
            [
                'title' => 'Sunset Rituals',
                'description' => 'Slow evenings, golden skies and a glass in hand on the cliff edge.',
                'image' => 'images/experiences/sunset-rituals.jpg',
            ],
            [
                'title' => 'Island Escapes',
                'description' => 'Hop between hidden beaches and discover the quiet side of Camotes.',
                'image' => 'images/exp3.jpg',
            ],
        ];

        foreach ($standIns as $card) {
            if ($have >= 6) {
                break;
            }

            if (in_array(mb_strtolower($card['title']), $titles, true) || ! file_exists(public_path($card['image']))) {
                continue;
            }

            DB::table('home_contents')->insert($card + [
                'section' => 'experiences',
                'sort_order' => ++$order,
                'active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $have++;
        }
    }

    public function down(): void
    {
        // Nothing to undo: these are ordinary cards the admin can edit or delete.
    }
};
