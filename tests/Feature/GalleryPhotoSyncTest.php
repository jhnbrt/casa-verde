<?php

use App\Models\HomeContent;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('photos used on other pages also show in the matching gallery category', function () {
    HomeContent::create([
        'section' => 'experiences_page_more',
        'title' => 'Sunset yoga',
        'image' => 'images/wellness4.jpg',
        'sort_order' => 1,
        'active' => true,
    ]);

    $this->get('/gallery')
        ->assertOk()
        ->assertSee('More photos')
        ->assertSee('images/wellness4.jpg', false)
        ->assertSee('Sunset yoga');
});

test('logos and icons never become gallery photos', function () {
    HomeContent::create([
        'section' => 'dining_page_features',
        'title' => 'Mark',
        'image' => 'images/logo.png',
        'sort_order' => 1,
        'active' => true,
    ]);

    $this->get('/gallery')->assertOk()->assertDontSee('data-alt="Mark"', false);
});

test('hiding the photo sync entry shows only the chosen gallery photos', function () {
    HomeContent::where('section', 'gallery_sync')->update(['active' => false]);

    HomeContent::create([
        'section' => 'experiences_page_more',
        'title' => 'Sunset yoga',
        'image' => 'images/wellness4.jpg',
        'sort_order' => 1,
        'active' => true,
    ]);

    $this->get('/gallery')->assertOk()->assertDontSee('images/wellness4.jpg', false);
});
