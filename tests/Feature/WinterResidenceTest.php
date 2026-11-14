<?php

use App\Models\HomeContent;
use Database\Seeders\WinterResidenceSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('the winterresidenz page shows its defaults', function () {
    $this->get('/winterresidenz')
        ->assertOk()
        ->assertSee('Winterresidenz auf Camotes Island')
        ->assertSee('Im Aufenthalt inklusive')
        ->assertSee('2.899')
        ->assertSee('5.499');
});

test('the first tab is the cover with its feature strip', function () {
    $this->get('/winterresidenz')
        ->assertOk()
        ->assertSee('Den Winter neu erleben – sorgenfrei unter Palmen')
        ->assertSee('Willkommen')
        ->assertSee('Rundum-Service')
        ->assertSee('wr-badges--features', false);
});

test('the winter block is no longer in the footer', function () {
    $this->get('/about')->assertOk()->assertDontSee('id="winterresidenz"', false);
});

test('the header has a dropdown under long stay and the footer links to the page', function () {
    HomeContent::create(['section' => 'navigation', 'title' => 'Long Stay', 'button_url' => '/long-stay', 'sort_order' => 1, 'active' => true]);

    $this->get('/about')
        ->assertOk()
        ->assertSee('data-nav-sub', false)
        ->assertSee(route('winter.index'), false)
        ->assertSee('Winterresidenz');
});

test('the long stay page has a banner that leads to the winterresidenz page', function () {
    $this->get('/long-stay')
        ->assertOk()
        ->assertSee('ls-winter', false)
        ->assertSee('Winterresidenz für 3–5 Monate');
});

test('the content loads into the admin without overwriting edits', function () {
    // the migrations already seed these sections; start from a clean slate
    HomeContent::whereIn('section', [
        'winter', 'winter_tabs', 'winter_badges', 'winter_prices', 'long_stay_winter',
    ])->delete();

    HomeContent::create(['section' => 'winter', 'title' => 'Mein Winter', 'sort_order' => 1, 'active' => true]);

    app(WinterResidenceSeeder::class)->run();
    app(WinterResidenceSeeder::class)->run();

    expect(HomeContent::where('section', 'winter')->count())->toBe(1);
    expect(HomeContent::where('section', 'winter')->value('title'))->toBe('Mein Winter');
    expect(HomeContent::where('section', 'winter_tabs')->count())->toBe(7);
    expect(HomeContent::where('section', 'winter_badges')->count())->toBe(11);
    expect(HomeContent::where('section', 'winter_prices')->count())->toBe(2);
    expect(HomeContent::where('section', 'long_stay_winter')->count())->toBe(1);
});

test('an edited price is respected', function () {
    app(WinterResidenceSeeder::class)->run();

    HomeContent::where('section', 'winter_prices')->where('title', 'Einzelresidenz')->update(['price' => 3199]);

    $this->get('/winterresidenz')->assertOk()->assertSee('3.199')->assertDontSee('2.899');
});

test('switching off the heading hides the page, its menu link and its banner', function () {
    app(WinterResidenceSeeder::class)->run();

    HomeContent::where('section', 'winter')->update(['active' => false]);

    $this->get('/winterresidenz')->assertNotFound();
    $this->get('/long-stay')->assertOk()->assertDontSee('ls-winter', false);
});

test('the menu link text comes from the admin', function () {
    HomeContent::create(['section' => 'navigation', 'title' => 'Long Stay', 'button_url' => '/long-stay', 'sort_order' => 1, 'active' => true]);
    app(WinterResidenceSeeder::class)->run();

    HomeContent::where('section', 'winter')->update(['button_text' => 'Winter Stay']);

    $this->get('/about')->assertOk()->assertSee('Winter Stay');
});

test('the tab choice lists the tabs by name', function () {
    app(WinterResidenceSeeder::class)->run();

    $choices = \App\Support\WinterResidence::tabChoices();

    expect($choices)->toHaveCount(7)
        ->and($choices[1])->toBe('Willkommen')
        ->and($choices[7])->toBe('Preise & Kontakt');
});
