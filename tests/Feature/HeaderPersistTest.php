<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('every page keeps the header in place while the visitor moves between pages', function () {
    foreach (['/', '/villas', '/wellness', '/dining', '/contact'] as $url) {
        $this->get($url)
            ->assertOk()
            ->assertSee('@view-transition', false)
            ->assertDontSee('view-transition-name: cv-header', false)
            ->assertSee('is-leaving', false)
            ->assertSee('window.cvFitNav', false);
    }
});
