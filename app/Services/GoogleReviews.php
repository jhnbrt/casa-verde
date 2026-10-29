<?php

namespace App\Services;

use App\Models\Review;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * Copies the resort's Google reviews into the local "reviews" table.
 * The public Reviews page never calls Google; it reads the saved copies, so it stays fast.
 */
class GoogleReviews
{
    private const RATINGS = ['ONE' => 1, 'TWO' => 2, 'THREE' => 3, 'FOUR' => 4, 'FIVE' => 5];

    /**
     * @return array{fetched: int, saved: int}
     */
    public function sync(): array
    {
        $config = (array) config('services.google_reviews');

        $reviews = match ($config['driver'] ?? 'places') {
            'business_profile' => $this->fromBusinessProfile($config),
            default => $this->fromPlaces($config),
        };

        $saved = 0;

        foreach ($reviews as $item) {
            // Star-only reviews have no text to show in the carousel.
            if ($item['rating'] < (int) ($config['min_rating'] ?? 1) || blank($item['comment'])) {
                continue;
            }

            $review = Review::firstOrNew(['external_id' => $item['id']]);
            $review->fill([
                'source' => 'google',
                'name' => mb_substr($item['name'], 0, 60),
                'place' => null,
                'rating' => min(5, max(1, $item['rating'])),
                'comment' => $item['comment'],
                'reviewed_at' => $item['date'],
            ]);

            // A review hidden in the admin stays hidden when the sync runs again.
            if (! $review->exists) {
                $review->is_published = (bool) ($config['publish'] ?? true);
                $review->created_at = $item['date'] ?? now();
            }

            $review->save();
            $saved++;
        }

        return ['fetched' => count($reviews), 'saved' => $saved];
    }

    /**
     * Look a place up by name so its Place ID can be copied into the .env file.
     *
     * @return array<int, array{id: string, name: string, address: string, rating: mixed, count: mixed}>
     */
    public function findPlace(string $query): array
    {
        $key = config('services.google_reviews.api_key');

        if (blank($key)) {
            throw new RuntimeException('Set GOOGLE_PLACES_API_KEY in the .env file first.');
        }

        $response = Http::timeout(15)
            ->withHeaders([
                'X-Goog-Api-Key' => $key,
                'X-Goog-FieldMask' => 'places.id,places.displayName,places.formattedAddress,places.rating,places.userRatingCount',
            ])
            ->post('https://places.googleapis.com/v1/places:searchText', ['textQuery' => $query])
            ->throw();

        return collect($response->json('places', []))->map(fn (array $p): array => [
            'id' => (string) ($p['id'] ?? ''),
            'name' => (string) ($p['displayName']['text'] ?? ''),
            'address' => (string) ($p['formattedAddress'] ?? ''),
            'rating' => $p['rating'] ?? '-',
            'count' => $p['userRatingCount'] ?? '-',
        ])->all();
    }

    /**
     * Places API (New): GET /v1/places/{id}. Google returns up to 5 reviews, no paging.
     */
    private function fromPlaces(array $config): array
    {
        if (blank($config['api_key']) || blank($config['place_id'])) {
            throw new RuntimeException('Set GOOGLE_PLACES_API_KEY and GOOGLE_PLACE_ID in the .env file.');
        }

        $response = Http::timeout(15)
            ->withHeaders([
                'X-Goog-Api-Key' => $config['api_key'],
                'X-Goog-FieldMask' => 'reviews',
            ])
            ->get('https://places.googleapis.com/v1/places/'.rawurlencode($config['place_id']))
            ->throw();

        return collect($response->json('reviews', []))->map(fn (array $r): array => [
            'id' => (string) ($r['name'] ?? ''),
            'name' => (string) ($r['authorAttribution']['displayName'] ?? 'Google user'),
            'rating' => (int) ($r['rating'] ?? 0),
            'comment' => trim((string) ($r['originalText']['text'] ?? $r['text']['text'] ?? '')),
            'date' => isset($r['publishTime']) ? Carbon::parse($r['publishTime']) : null,
        ])->filter(fn (array $r): bool => $r['id'] !== '')->values()->all();
    }

    /**
     * Business Profile API: every review of the location, newest first, 50 per page.
     */
    private function fromBusinessProfile(array $config): array
    {
        foreach (['client_id', 'client_secret', 'refresh_token', 'account_id', 'location_id'] as $key) {
            if (blank($config[$key] ?? null)) {
                throw new RuntimeException('Business Profile sync needs GOOGLE_CLIENT_ID, GOOGLE_CLIENT_SECRET, GOOGLE_REFRESH_TOKEN, GOOGLE_BUSINESS_ACCOUNT_ID and GOOGLE_BUSINESS_LOCATION_ID in the .env file.');
            }
        }

        $token = Http::asForm()->timeout(15)->post('https://oauth2.googleapis.com/token', [
            'client_id' => $config['client_id'],
            'client_secret' => $config['client_secret'],
            'refresh_token' => $config['refresh_token'],
            'grant_type' => 'refresh_token',
        ])->throw()->json('access_token');

        $url = sprintf(
            'https://mybusiness.googleapis.com/v4/accounts/%s/locations/%s/reviews',
            $config['account_id'],
            $config['location_id'],
        );

        $items = [];
        $pageToken = null;

        do {
            $page = Http::withToken($token)->timeout(20)
                ->get($url, array_filter(['pageSize' => 50, 'pageToken' => $pageToken]))
                ->throw()->json();

            foreach ($page['reviews'] ?? [] as $r) {
                $items[] = [
                    'id' => (string) ($r['name'] ?? $r['reviewId'] ?? ''),
                    'name' => (string) ($r['reviewer']['displayName'] ?? 'Google user'),
                    'rating' => self::RATINGS[$r['starRating'] ?? ''] ?? 0,
                    'comment' => trim((string) ($r['comment'] ?? '')),
                    'date' => isset($r['createTime']) ? Carbon::parse($r['createTime']) : null,
                ];
            }

            $pageToken = $page['nextPageToken'] ?? null;
        } while ($pageToken);

        return array_values(array_filter($items, fn (array $r): bool => $r['id'] !== ''));
    }
}
