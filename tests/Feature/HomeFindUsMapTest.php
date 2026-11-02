<?php

use App\Models\HomeContent;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function mapRow(array $values = []): HomeContent
{
    return HomeContent::updateOrCreate(['section' => 'contact_map'], $values + [
        'title' => 'Find us on the map', 'sort_order' => 1, 'active' => true,
    ]);
}

test('Find us on the home page is a live Google Map, not a photo', function () {
    mapRow(['description' => 'https://www.google.com/maps?q=Casa%20Verde&t=k&z=17&output=embed']);

    $this->get('/')
        ->assertOk()
        ->assertSee('Find us on the map')
        ->assertSee('https://www.google.com/maps?q=Casa%20Verde&amp;t=k&amp;z=17&amp;output=embed', false)
        ->assertDontSee('Casa Verde location map');
});

test('an empty map address shows the satellite map of the resort', function () {
    mapRow(['description' => null]);

    $this->get('/')->assertOk()->assertSee('t=k', false)->assertSee('output=embed', false);
});

test('only Google Maps addresses can be embedded', function () {
    mapRow(['description' => 'https://evil.example.com/maps?x=1']);

    $this->get('/')->assertOk()->assertDontSee('evil.example.com')->assertSee('google.com/maps', false);
});

test('the contact page uses the same map', function () {
    mapRow(['description' => 'https://www.google.com/maps?q=Somewhere&output=embed']);

    $this->get('/contact')->assertOk()->assertSee('q=Somewhere', false);
});
