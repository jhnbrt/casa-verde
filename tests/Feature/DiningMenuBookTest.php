<?php

use App\Models\HomeContent;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('the food menu button opens the menu book with its cover and pages', function () {
    $this->get('/dining')
        ->assertOk()
        ->assertSee('data-menu-book="mb-food"', false)
        ->assertSee('id="mb-food"', false)
        ->assertSee('images/dining/menu/cover.jpg', false)
        ->assertSee('images/dining/menu/mains-1.jpg', false)
        ->assertSee('images/dining/menu/mains-2.jpg', false)
        ->assertSee('Click here');
});

test('the drinks menu button stays a normal link', function () {
    $this->get('/dining')->assertOk()->assertSee('EXPLORE DRINKS MENU');
});

test('pages and the hint text come from the admin', function () {
    HomeContent::create(['section' => 'dining_page_food_menu', 'title' => 'Food menu', 'image' => 'images/dining/menu/cover.jpg', 'button_text' => 'Open me', 'sort_order' => 1, 'active' => true]);
    HomeContent::create(['section' => 'dining_page_food_menu_pages', 'title' => 'Mains', 'image' => 'images/dining/menu/mains-1.jpg', 'sort_order' => 1, 'active' => true]);

    $this->get('/dining')
        ->assertOk()
        ->assertSee('Open me')
        ->assertSee('images/dining/menu/mains-1.jpg', false)
        ->assertDontSee('images/dining/menu/mains-2.jpg', false);
});

test('without a cover image the button is a normal link and there is no book', function () {
    HomeContent::create(['section' => 'dining_page_food_menu', 'title' => 'Food menu', 'image' => null, 'sort_order' => 1, 'active' => true]);

    $this->get('/dining')
        ->assertOk()
        ->assertDontSee('data-menu-book', false)
        ->assertDontSee('id="mb-food"', false);
});
