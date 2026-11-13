<?php

use App\Models\HomeContent;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Puts the complete text of "The Natural Wonders of Camotes Island" in the Journal. It only replaces the short
     * opening lines added earlier; text already changed in the admin is left alone.
     */
    public function up(): void
    {
        $article = HomeContent::where('section', 'journal_articles')
            ->whereRaw('lower(trim(title)) = ?', ['the natural wonders of camotes island'])
            ->first();

        if (! $article || mb_strlen(trim((string) $article->description)) > 400) {
            return;
        }

        $article->update(['description' => <<<'TXT'
Welcome to the Camotes Islands, an untouched tropical escape located east of the Cebu mainland. Often overlooked by mainstream tourists, this cluster of islands offers a perfect blend of raw beauty, crystal-clear waters, and a quiet charm that feels worlds away from the busy city. Comprising four main islands—Poro, Pacijan, Ponson, and Tulang—Camotes is the ultimate destination for travelers seeking both thrilling adventures and absolute tranquility.

Here is a guide to exploring the natural wonders of Camotes and discovering its most luxurious retreat.

## The Natural Wonders of Camotes

Camotes Island is packed with exciting attractions for all types of travelers, from deep subterranean caves to pristine white-sand beaches.

### 1. Mystical Caves and Underground Pools

One of the best things about Camotes Island is its density of spectacular caves.

- **Amazing Island Cave:** A deep hole featuring insanely blue water, perfect for a refreshing dip or practicing your freediving skills.
- **Bukilat Cave:** The largest and most popular cavern, boasting natural sunlight that streams through the ceiling to illuminate the cool, subterranean waters below. It was historically used as a hiding place by locals during the war.
- **Timubo Cave:** An enchanting spot where visitors navigate down into a cool, clear underground pool to swim.

### 2. Pristine Beaches and Islets

The coastline is ringed with stunning beaches that invite you to slow down.

- **Santiago White Beach (Santiago Bay):** Known for its wide stretch of powdery white sand and shallow, crystal-clear waters. When the tide is high, it’s the perfect spot to wade, and at sunset, you can enjoy fresh grilled seafood right by the shore.
- **Tulang Diot Island:** A tiny islet just off the northern tip of Pacijan. A short boat ride takes you to this secluded paradise, which is excellent for snorkeling, diving, and lounging on the azure coast.
- **Bakhaw Beach:** If you prefer a quieter and more secluded vibe, this underrated spot offers peaceful shores away from the crowds.

### 3. Thrills and Serenity

- **Buho Rock Resort:** Designed for adrenaline junkies, this cliff-diving destination features platforms of varying heights where you can plunge straight into the vibrant blue sea.
- **Lake Danao Park:** Affectionately known as "Lovers Lake," this stunning freshwater lake offers kayaking, paddle boating, and serene picnic spots. For a bit more excitement, you can ride a zipline straight across the water.

## Spotlight: Casa Verde Cliff Resort & Spa

After a day of cliff jumping and cave exploring, there is no better place to retreat than the Casa Verde Cliff Resort & Spa. Located remotely to offer the perfect combination of privacy and relaxation, this high-end sanctuary elevates the Camotes Island experience to pure luxury.

### Luxurious Villas and Wellness

Nestled within lush gardens, the resort's villas are designed to merge indoor comfort with outdoor tranquility. Guests can expect expansive interiors, high-end amenities, and stunning bathrooms featuring freestanding bathtubs bathed in natural light.

For ultimate rejuvenation, the resort’s Spa & Wellness center combines traditional Filipino healing techniques with timeless spa classics, using locally sourced ingredients for a deeply restorative experience. They even feature a unique "Hilom Cave"—a private sanctuary carved into living rock with natural stone walls and shimmering mineral deposits.

### Unmatched Amenities

Casa Verde doesn't just offer a place to sleep; it provides a comprehensive lifestyle experience:

- **Infinity Pool:** Perched right on the cliffside, the main pool offers breathtaking panoramic ocean views.
- **Sunset Restaurant & Bar Berlin:** Enjoy exquisite flavors and classic cocktails while overlooking the azure sea from dramatic cliffs.
- **Active Recreation:** Uniquely, the resort features a Padel court for a dynamic game, alongside a 24-hour fitness gym and opportunities for snorkeling and canoeing.

## Planning Your Escape

### How to Get There

Getting to the islands is part of the adventure! The most common route is taking a fast ferry (like OceanJet) from Cebu City's Pier 1 directly to Poro Port, which takes about 1.5 hours. Alternatively, you can take a bus or van from Cebu City to Danao Port, and then board a RoRo or pump boat to Consuelo Port in Camotes.

### Getting Around

The most convenient way to explore Camotes is by renting a tricycle or a scooter. Be prepared for a few bumpy roads, but navigating the small islands is relatively easy, especially if you have a map or GPS handy.

Whether you are looking to dive into an underground cave, leap from a seaside cliff, or simply soak in a luxurious bathtub at Casa Verde overlooking the ocean, Camotes Island promises an unforgettable tropical getaway.
TXT]);
    }

    public function down(): void
    {
        // Intentionally empty: removing content is not something to undo silently.
    }
};
