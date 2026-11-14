<?php

use App\Models\HomeContent;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('the yoga page opens with its classes, schedule and questions', function () {
    $this->get('/yoga-reset')
        ->assertOk()
        ->assertSee('Breathe. Stretch. Reset.')
        ->assertSee('id="classes"', false)
        ->assertSee('Sunrise Flow')
        ->assertSee('id="schedule"', false)
        ->assertSee('id="faq"', false)
        ->assertSee('Do I need yoga experience?')
        ->assertDontSee('images/dining3.jpg', false);
});

test('questions and answers come from the admin', function () {
    HomeContent::where('section', 'yoga_faq')->delete();
    HomeContent::create(['section' => 'yoga_faq', 'title' => 'Is there parking?', 'description' => "Yes, it is free.\nNear the gate.", 'sort_order' => 1, 'active' => true]);

    $this->get('/yoga-reset')
        ->assertOk()
        ->assertSee('<details class="yg-faq__item">', false)
        ->assertSee('Is there parking?')
        ->assertSee('Near the gate.');
});

test('the questions section disappears when there are none', function () {
    HomeContent::where('section', 'yoga_faq')->delete();

    $this->get('/yoga-reset')->assertOk()->assertDontSee('id="faq"', false);
});

test('the teachers section only appears once a teacher is added', function () {
    $this->get('/yoga-reset')->assertOk()->assertDontSee('id="teachers"', false);

    HomeContent::create(['section' => 'yoga_teachers', 'title' => 'Maria Santos', 'subtitle' => 'Lead teacher', 'description' => 'Twelve years of teaching.', 'sort_order' => 1, 'active' => true]);

    $this->get('/yoga-reset')
        ->assertOk()
        ->assertSee('id="teachers"', false)
        ->assertSee('Maria Santos')
        ->assertSee('Lead teacher');
});

test('a class shows its price only when the admin sets one', function () {
    $this->get('/yoga-reset')->assertOk()->assertDontSee('class="yg-price"', false);

    HomeContent::where('section', 'yoga_types')->orderBy('sort_order')->first()->update(['price' => 1250]);

    $this->get('/yoga-reset')
        ->assertOk()
        ->assertSee('class="yg-price"', false)
        ->assertSee('₱1,250');
});

test('the sample guest quote stays hidden until it is a real review', function () {
    $this->get('/yoga-reset')->assertOk()->assertDontSee('class="yg-quote"', false);

    HomeContent::where('section', 'yoga_quote')->update(['title' => 'Anna K.']);

    $this->get('/yoga-reset')->assertOk()->assertSee('class="yg-quote"', false)->assertSee('Anna K.');
});

test('a photo too small to look sharp is left out and a sharp one is shown', function () {
    HomeContent::where('section', 'yoga_hero')->update(['image' => 'images/experiences/hero.jpg']);

    $this->get('/yoga-reset')->assertOk()->assertDontSee('yg-hero__photo', false);

    HomeContent::where('section', 'yoga_hero')->update(['image' => 'images/main.jpg']);

    $this->get('/yoga-reset')->assertOk()->assertSee('yg-hero--photo', false)->assertSee('yg-hero__photo', false);
});
