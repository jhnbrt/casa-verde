<?php

use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

it('copies Google reviews into the reviews table without duplicating them', function () {
    config(['services.google_reviews' => [
        'driver' => 'places', 'api_key' => 'k', 'place_id' => 'P1', 'min_rating' => 1, 'publish' => true,
    ]]);

    Http::fake(['places.googleapis.com/*' => Http::response(['reviews' => [[
        'name' => 'places/P1/reviews/abc',
        'rating' => 5,
        'publishTime' => '2026-09-01T10:00:00Z',
        'text' => ['text' => 'Wonderful cliffside stay'],
        'authorAttribution' => ['displayName' => 'Maria S.'],
    ]]])]);

    $this->artisan('reviews:sync-google')->assertSuccessful();
    $this->artisan('reviews:sync-google')->assertSuccessful();

    expect(Review::where('source', 'google')->count())->toBe(1)
        ->and(Review::first()->name)->toBe('Maria S.');

    $this->get('/reviews')->assertOk()->assertSee('Wonderful cliffside stay')->assertSee('Google review');
});

it('keeps a review hidden in the admin hidden when the sync runs again', function () {
    config(['services.google_reviews' => [
        'driver' => 'places', 'api_key' => 'k', 'place_id' => 'P1', 'min_rating' => 1, 'publish' => true,
    ]]);

    Http::fake(['places.googleapis.com/*' => Http::response(['reviews' => [[
        'name' => 'places/P1/reviews/abc', 'rating' => 4,
        'text' => ['text' => 'Nice'], 'authorAttribution' => ['displayName' => 'Ben'],
    ]]])]);

    $this->artisan('reviews:sync-google')->assertSuccessful();
    Review::first()->update(['is_published' => false]);
    $this->artisan('reviews:sync-google')->assertSuccessful();

    expect(Review::first()->is_published)->toBeFalse();
});

it('fails clearly when the keys are missing', function () {
    config(['services.google_reviews' => ['driver' => 'places', 'api_key' => null, 'place_id' => null]]);

    $this->artisan('reviews:sync-google')->assertFailed();
});

it('finds the Place ID by name', function () {
    config(['services.google_reviews.api_key' => 'k']);

    Http::fake(['places.googleapis.com/*' => Http::response(['places' => [[
        'id' => 'ChIJexample', 'displayName' => ['text' => 'Casa Verde Cliff Resort & Spa'],
        'formattedAddress' => 'Camotes', 'rating' => 4.8, 'userRatingCount' => 120,
    ]]])]);

    $this->artisan('reviews:find-google-place')->expectsOutputToContain('ChIJexample')->assertSuccessful();
});
