<?php

use App\Models\HomeContent;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('the reviews page shows the built-in content before anything is saved in the admin', function () {
    $this->get('/reviews')
        ->assertOk()
        ->assertSee('What our guests say')
        ->assertSee('Lukas H.')
        ->assertSee('data-slot="4.9 / 5"', false)
        ->assertSee('data-autoplay="7000"', false);
});

test('the automatic change can be switched off from the admin', function () {
    HomeContent::create([
        'section' => 'reviews_header',
        'title' => 'What our guests say',
        'price' => 0,
        'sort_order' => 1,
        'active' => true,
    ]);

    $this->get('/reviews')->assertOk()->assertSee('data-autoplay="0"', false);
});

test('reviews saved in the admin replace the built-in ones', function () {
    HomeContent::create([
        'section' => 'reviews_items',
        'title' => 'Maria S.',
        'subtitle' => 'Cebu City',
        'description' => 'A quiet and lovely stay.',
        'price' => 4,
        'sort_order' => 1,
        'active' => true,
    ]);

    HomeContent::create([
        'section' => 'reviews_stats',
        'title' => '1,200+',
        'subtitle' => 'Guests welcomed',
        'sort_order' => 1,
        'active' => true,
    ]);

    $this->get('/reviews')
        ->assertOk()
        ->assertSee('Maria S.')
        ->assertSee('4 out of 5 stars')
        ->assertSee('data-slot="1,200+"', false)
        ->assertDontSee('Lukas H.');
});

// ---------------------------------------------------------------------------
// Reviews written by guests on the website
// ---------------------------------------------------------------------------

test('the reviews page has the form guests use to rate their stay', function () {
    $this->get('/reviews')
        ->assertOk()
        ->assertSee('id="rv-share"', false)
        ->assertSee('name="rating"', false)
        ->assertSee('name="comment"', false);
});

test('a guest can send a rating and it shows on the reviews page straight away', function () {
    $this->post('/reviews', [
        'name' => 'Teresa Q.',
        'place' => 'Manila',
        'rating' => 4,
        'comment' => 'Lovely cliffside villa and a wonderful breakfast.',
    ])->assertRedirect(route('reviews.index').'#rv-share');

    $this->assertDatabaseHas('reviews', [
        'name' => 'Teresa Q.',
        'rating' => 4,
        'is_published' => true,
    ]);

    $this->get('/reviews')
        ->assertOk()
        ->assertSee('Teresa Q.')
        ->assertSee('Lovely cliffside villa and a wonderful breakfast.')
        ->assertSee('4 out of 5 stars')
        ->assertSee('4.0 / 5');
});

test('the newest guest review comes before the built-in ones', function () {
    $this->post('/reviews', [
        'name' => 'Teresa Q.',
        'rating' => 5,
        'comment' => 'Lovely cliffside villa and a wonderful breakfast.',
    ]);

    $this->get('/reviews')->assertOk()->assertSeeInOrder(['Teresa Q.', 'Lukas H.']);
});

test('a review with a missing or invalid rating is not saved', function () {
    $this->from('/reviews')
        ->post('/reviews', ['name' => 'Teresa Q.', 'comment' => 'Lovely cliffside villa and a wonderful breakfast.'])
        ->assertSessionHasErrors('rating');

    $this->post('/reviews', ['name' => 'Teresa Q.', 'rating' => 9, 'comment' => 'Lovely cliffside villa and a wonderful breakfast.'])
        ->assertSessionHasErrors('rating');

    $this->assertDatabaseCount('reviews', 0);
});

test('a review needs a name and a comment, and may not contain web links', function () {
    $this->post('/reviews', ['rating' => 5])->assertSessionHasErrors(['name', 'comment']);

    $this->post('/reviews', [
        'name' => 'Spammer',
        'rating' => 5,
        'comment' => 'Cheap deals at https://spam.example today',
    ])->assertSessionHasErrors('comment');

    $this->assertDatabaseCount('reviews', 0);
});

test('a filled-in trap field means a bot, so nothing is saved', function () {
    $this->post('/reviews', [
        'name' => 'Bot',
        'rating' => 5,
        'comment' => 'This is a perfectly normal looking review.',
        'website' => 'bot.example',
    ])->assertRedirect();

    $this->assertDatabaseCount('reviews', 0);
});

test('with approval switched on a new review waits until it is shown from the admin', function () {
    config(['reviews.require_approval' => true]);

    $this->post('/reviews', [
        'name' => 'Teresa Q.',
        'rating' => 5,
        'comment' => 'Lovely cliffside villa and a wonderful breakfast.',
    ]);

    $this->assertDatabaseHas('reviews', ['name' => 'Teresa Q.', 'is_published' => false]);
    $this->get('/reviews')->assertOk()->assertDontSee('Teresa Q.');

    $admin = User::factory()->create(['is_admin' => true]);
    $review = Review::firstOrFail();

    $this->actingAs($admin)->put(route('admin.reviews.update', $review))->assertRedirect();

    $this->get('/reviews')->assertOk()->assertSee('Teresa Q.');
});

test('only an admin can manage guest reviews, and can hide and delete them', function () {
    $review = Review::create([
        'name' => 'Teresa Q.',
        'rating' => 5,
        'comment' => 'Lovely cliffside villa and a wonderful breakfast.',
    ]);

    $this->get(route('admin.reviews.index'))->assertRedirect();

    $admin = User::factory()->create(['is_admin' => true]);

    $this->actingAs($admin)->get(route('admin.reviews.index'))->assertOk()->assertSee('Teresa Q.');

    $this->actingAs($admin)->put(route('admin.reviews.update', $review))->assertRedirect();
    expect($review->fresh()->is_published)->toBeFalse();
    $this->get('/reviews')->assertDontSee('Teresa Q.');

    $this->actingAs($admin)->delete(route('admin.reviews.destroy', $review))->assertRedirect();
    $this->assertDatabaseCount('reviews', 0);
});
