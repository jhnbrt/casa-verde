<?php

use App\Models\HomeContent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function experienceCards(int $n): void
{
    HomeContent::where('section', 'experiences')->delete();

    foreach (range(1, $n) as $i) {
        HomeContent::create([
            'section' => 'experiences', 'title' => "Card {$i}", 'description' => "Text {$i}",
            'image' => 'images/main.jpg', 'sort_order' => $i, 'active' => true,
        ]);
    }
}

beforeEach(function () {
    HomeContent::updateOrCreate(['section' => 'experiences_header'], [
        'title' => 'Moments that stay with you.', 'subtitle' => 'EXPERIENCES', 'sort_order' => 1, 'active' => true,
    ]);
});

test('six cards make the animated 3D carousel with arrows and dots', function () {
    experienceCards(6);

    $html = $this->get('/')->assertOk()->getContent();

    expect($html)->toContain('data-exp-carousel')
        ->and(substr_count($html, 'class="exp-slide"'))->toBe(6)
        ->and(substr_count($html, 'data-exp-go='))->toBe(6)
        ->and($html)->toContain('data-exp-prev')->toContain('data-exp-next')
        ->and($html)->toContain('data-pos="0"')->toContain('data-pos="1"')->toContain('data-pos="-1"')
        ->toContain('data-pos="2"')->toContain('data-pos="-2"')->toContain('data-pos="3"'); // two on each side are on show
});

test('the turning speed comes from the admin and 0 stops it', function () {
    experienceCards(4);

    HomeContent::where('section', 'experiences_header')->update(['price' => 3]);
    $this->get('/')->assertSee('data-autoplay="3000"', false);

    HomeContent::where('section', 'experiences_header')->update(['price' => 0]);
    $this->get('/')->assertSee('data-autoplay="0"', false);

    HomeContent::where('section', 'experiences_header')->update(['price' => null]);
    $this->get('/')->assertSee('data-autoplay="5000"', false);
});

test('fewer than three cards stay as a simple row', function () {
    experienceCards(2);

    $this->get('/')->assertOk()->assertSee('class="experience-container"', false)->assertDontSee('data-exp-carousel', false);
});

test('a card switched off in the admin leaves the carousel', function () {
    experienceCards(4);
    HomeContent::where('section', 'experiences')->where('title', 'Card 4')->update(['active' => false]);

    $html = $this->get('/')->getContent();

    expect(substr_count($html, 'class="exp-slide"'))->toBe(3)->and($html)->not->toContain('Card 4');
});

test('the migration adds three stand-in cards once, and only to the original three', function () {
    HomeContent::where('section', 'experiences')->delete();
    foreach (['Spectacular Sunsets', 'Ocean & Cave', 'Island Adventures'] as $i => $title) {
        HomeContent::create(['section' => 'experiences', 'title' => $title, 'image' => 'images/main.jpg', 'sort_order' => $i + 1, 'active' => true]);
    }

    $migration = require database_path('migrations/2026_11_08_000100_add_three_home_experiences.php');
    $migration->up();
    $migration->up(); // running again changes nothing

    expect(HomeContent::where('section', 'experiences')->count())->toBe(6)
        ->and(HomeContent::where('section', 'experiences')->orderBy('sort_order')->pluck('title')->all())
        ->toBe(['Spectacular Sunsets', 'Ocean & Cave', 'Island Adventures', 'Snorkeling Adventure', 'Padel Match', 'Billiards Lounge']);

    foreach (HomeContent::where('section', 'experiences')->get() as $card) {
        if (str_starts_with($card->image, 'images/2026')) {
            expect(file_exists(public_path($card->image)))->toBeTrue();
        }
    }
});

test('the admin can set the speed and edit the cards', function () {
    experienceCards(3);
    $admin = User::factory()->create(['is_admin' => true]);
    $header = HomeContent::where('section', 'experiences_header')->first();

    $this->actingAs($admin)->get(route('admin.content.edit', $header))
        ->assertOk()->assertSee('Turn to the next card every (seconds)');

    $this->actingAs($admin)->get(route('admin.content.edit', HomeContent::where('section', 'experiences')->first()))
        ->assertOk()->assertSee('Card title');
});


test('the ensure-six migration tops the carousel up to six cards with editable mock content', function () {
    HomeContent::where('section', 'experiences')->delete();
    // a site that already has 4 cards (one switched off), so the earlier "exactly 3" step did not apply
    foreach (['Spectacular Sunsets', 'Ocean & Cave', 'Island Adventures', 'Padel Match'] as $i => $title) {
        HomeContent::create(['section' => 'experiences', 'title' => $title, 'image' => 'images/main.jpg', 'sort_order' => $i + 1, 'active' => $i < 3]);
    }

    $migration = require database_path('migrations/2026_11_09_000100_ensure_six_home_experiences.php');
    $migration->up();
    $migration->up(); // running again changes nothing

    $titles = HomeContent::where('section', 'experiences')->orderBy('sort_order')->pluck('title')->all();

    expect($titles)->toHaveCount(6)
        ->and(array_count_values($titles)['Padel Match'])->toBe(1)   // no duplicates of cards that exist
        ->and(HomeContent::where('section', 'experiences')->whereNull('description')->count())->toBe(4); // the 4 existing cards are untouched; the 2 added ones have text

    foreach (HomeContent::where('section', 'experiences')->whereNotNull('description')->get() as $card) {
        expect(file_exists(public_path($card->image)))->toBeTrue();
    }
});

test('a site with no experience cards at all is left alone', function () {
    HomeContent::where('section', 'experiences')->delete();

    (require database_path('migrations/2026_11_09_000100_ensure_six_home_experiences.php'))->up();

    expect(HomeContent::where('section', 'experiences')->count())->toBe(0);
});
