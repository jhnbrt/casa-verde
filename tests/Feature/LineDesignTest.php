<?php

use App\Models\HomeContent;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('the same divider shows on the pages and follows the site-wide ornament', function () {
    HomeContent::where('section', 'site_divider')->delete();

    HomeContent::create([
        'section' => 'site_divider',
        'title' => '#aa8844',
        'subtitle' => 'solid',
        'icon' => 'images/logo.png',
        'sort_order' => 1,
        'active' => true,
    ]);

    foreach (['/', '/about', '/reviews', '/events'] as $page) {
        $this->get($page)
            ->assertOk()
            ->assertSee('cv-rule', false)
            ->assertSee('color:#aa8844', false)
            ->assertSee('images/logo.png', false);
    }
});

test('a section cannot pick its own ornament any more', function () {
    HomeContent::where('section', 'site_divider')->delete();

    HomeContent::create([
        'section' => 'site_divider',
        'icon' => 'leaf',
        'sort_order' => 1,
        'active' => true,
    ]);

    $html = view('components.rule', ['ornament' => 'rings'])->render();

    expect($html)->toContain('cv-rule')
        ->and($html)->toContain('M5 19c0-9 5-14 14-14') // the leaf from the site-wide design
        ->and($html)->not->toContain('circle cx="9" cy="14.5"') // not the rings
        ->and($html)->toContain('width="42"'); // the one shared size
});
