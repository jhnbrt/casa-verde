<?php

use App\Models\HomeContent;
use App\Support\FoodMenu;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('the food menu button opens the menu book with its cover and dish pages', function () {
    $pages = FoodMenu::pages(HomeContent::where('active', true)->orderBy('sort_order')->get()->groupBy('section'));

    $this->get('/dining')
        ->assertOk()
        ->assertSee('data-menu-book="mb-food"', false)
        ->assertSee('id="mb-food"', false)
        ->assertSee('images/dining/menu/cover.jpg', false)
        ->assertSee('Open Menu')
        ->assertSee('Salmon Steak')
        ->assertSee('695')
        ->assertSee('Buttered Garlic Shrimp')
        ->assertSee('data-pages="'.count($pages).'"', false);
});

test('the drinks menu button stays a normal link', function () {
    $this->get('/dining')->assertOk()->assertSee('EXPLORE DRINKS MENU');
});

test('the hint text on the cover comes from the admin', function () {
    HomeContent::create(['section' => 'dining_page_food_menu', 'title' => 'Food menu', 'image' => 'images/dining/menu/cover.jpg', 'button_text' => 'Open me', 'sort_order' => 1, 'active' => true]);

    $this->get('/dining')->assertOk()->assertSee('Open me');
});

test('a new dish and a new category from the admin appear in the book', function () {
    // the migrations already load the real menu; start from an empty one
    HomeContent::whereIn('section', ['dining_page_food_menu_categories', 'dining_page_food_menu_dishes'])->delete();

    $category = HomeContent::create(['section' => 'dining_page_food_menu_categories', 'title' => 'Desserts', 'sort_order' => 1, 'active' => true]);

    HomeContent::create([
        'section' => 'dining_page_food_menu_dishes',
        'title' => 'Mango Panna Cotta',
        'subtitle' => (string) $category->id,
        'price' => 280,
        'description' => 'Silky vanilla cream with fresh Cebu mango.',
        'features' => ['nuts', 'cheese'],
        'sort_order' => 1,
        'active' => true,
    ]);

    $this->get('/dining')
        ->assertOk()
        ->assertSee('Desserts')
        ->assertSee('Mango Panna Cotta')
        ->assertSee('280')
        ->assertSee('Silky vanilla cream with fresh Cebu mango.')
        ->assertSee('data-pages="1"', false);
});

test('two dishes fit on a page and more dishes make more pages', function () {
    HomeContent::whereIn('section', ['dining_page_food_menu_categories', 'dining_page_food_menu_dishes'])->delete();

    $category = HomeContent::create(['section' => 'dining_page_food_menu_categories', 'title' => 'Starters', 'sort_order' => 1, 'active' => true]);

    foreach (range(1, 7) as $i) {
        HomeContent::create(['section' => 'dining_page_food_menu_dishes', 'title' => "Starter {$i}", 'subtitle' => (string) $category->id, 'price' => 100 + $i, 'sort_order' => $i, 'active' => true]);
    }

    $pages = FoodMenu::pages(HomeContent::where('active', true)->get()->groupBy('section'));

    expect($pages)->toHaveCount(4)
        ->and($pages[0]['dishes'])->toHaveCount(2)
        ->and($pages[3]['dishes'])->toHaveCount(1)
        ->and($pages[0]['heading'])->toBe('Starters');
});

test('the dietary icons of a dish are the ones named in the admin', function () {
    $pages = FoodMenu::pages(collect());

    $curry = collect($pages)->pluck('dishes')->flatten(1)->firstWhere('name', 'Chicken Curry');

    expect($curry)->not->toBeNull()
        ->and(collect($curry['icons'])->pluck('label')->all())->toBe(['Chicken', 'Chili']);
});

test('a dish whose category link is stale still lands in its printed category', function () {
    HomeContent::whereIn('section', ['dining_page_food_menu_categories', 'dining_page_food_menu_dishes'])->delete();

    HomeContent::create(['section' => 'dining_page_food_menu_categories', 'title' => 'Mains', 'sort_order' => 1, 'active' => true]);
    HomeContent::create(['section' => 'dining_page_food_menu_dishes', 'title' => 'Salmon Steak', 'subtitle' => '99999', 'price' => 695, 'sort_order' => 1, 'active' => true]);

    $pages = FoodMenu::pages(HomeContent::where('active', true)->get()->groupBy('section'));

    expect($pages)->toHaveCount(1)
        ->and($pages[0]['heading'])->toBe('Mains')
        ->and($pages[0]['dishes'][0]['name'])->toBe('Salmon Steak');
});

test('without a cover image the button is a normal link and there is no book', function () {
    // the migrations already add the real food-menu row (with its cover); start from a clean section
    HomeContent::where('section', 'dining_page_food_menu')->delete();

    HomeContent::create(['section' => 'dining_page_food_menu', 'title' => 'Food menu', 'image' => null, 'sort_order' => 1, 'active' => true]);

    $this->get('/dining')
        ->assertOk()
        ->assertDontSee('data-menu-book', false)
        ->assertDontSee('id="mb-food"', false);
});
