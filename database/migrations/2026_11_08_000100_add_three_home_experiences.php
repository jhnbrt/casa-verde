<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Adds three more cards to the Home page Experiences carousel, using photos already in the
     * project. They are stand-ins: replace each photo, title and text in the admin
     * (Site content > Home page: Experiences > Experience cards).
     * Only runs while the home page still has its original 3 cards, and never twice.
     */
    public function up(): void
    {
        $titles = ['Snorkeling Adventure', 'Padel Match', 'Billiards Lounge'];

        $existing = DB::table('home_contents')->where('section', 'experiences');

        if ((clone $existing)->count() !== 3 || (clone $existing)->whereIn('title', $titles)->exists()) {
            return;
        }

        $now = now();
        $order = (int) (clone $existing)->max('sort_order');

        $cards = [
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
        ];

        foreach ($cards as $i => $card) {
            DB::table('home_contents')->insert($card + [
                'section' => 'experiences',
                'sort_order' => $order + $i + 1,
                'active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        DB::table('home_contents')
            ->where('section', 'experiences')
            ->whereIn('title', ['Snorkeling Adventure', 'Padel Match', 'Billiards Lounge'])
            ->delete();
    }
};
