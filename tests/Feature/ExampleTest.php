<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

// The home page reads its content from the database, so the tables must exist.
uses(RefreshDatabase::class);

test('the application returns a successful response', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});
