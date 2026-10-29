<?php

use App\Models\HomeContent;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// A throw-away icon so the test does not depend on which pictures the project ships with.
beforeEach(function () {
    $this->icon = 'images/zz-card-icon.png';

    if (! function_exists('imagepng')) {
        $this->markTestSkipped('The GD extension is needed to make a test icon.');
    }

    $image = imagecreatetruecolor(64, 64);
    imagepng($image, public_path($this->icon));
    imagedestroy($image);
});

afterEach(function () {
    @unlink(public_path($this->icon));
});

test('the Home page villa cards have their own icon entries, separate from the Villas page', function () {
    expect(HomeContent::where('section', 'home_villa_feature_icons')->count())->toBe(6);

    HomeContent::where('section', 'home_villa_feature_icons')
        ->where('title', 'Standard Villa')
        ->where('sort_order', 1)
        ->update(['icon' => $this->icon]);

    $this->get('/')->assertOk()->assertSee('zz-card-icon.png', false);
    $this->get('/villas')->assertOk()->assertDontSee('zz-card-icon.png', false);
});

test('the Villas page icons do not change the Home page', function () {
    HomeContent::where('section', 'villas_feature_icons')
        ->where('title', 'Standard Villa')
        ->where('sort_order', 1)
        ->update(['icon' => $this->icon]);

    $this->get('/villas')->assertOk()->assertSee('zz-card-icon.png', false);
    $this->get('/')->assertOk()->assertDontSee('zz-card-icon.png', false);
});
