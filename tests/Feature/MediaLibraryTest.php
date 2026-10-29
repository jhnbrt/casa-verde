<?php

use App\Models\HomeContent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('only admins can open the media library', function () {
    $this->get(route('admin.media.index'))->assertRedirect();
});

test('the media library lists the pictures and says which ones are in use', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    HomeContent::create([
        'section' => 'site',
        'title' => 'Casa Verde',
        'image' => 'images/logo.png',
        'sort_order' => 1,
        'active' => true,
    ]);

    $this->actingAs($admin)
        ->get(route('admin.media.index'))
        ->assertOk()
        ->assertSee('Media library')
        ->assertSee('images/logo.png')
        ->assertSee('In use now');
});

test('the media library can be filtered', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $this->actingAs($admin)
        ->get(route('admin.media.index', ['type' => 'symbol', 'status' => 'unused', 'q' => 'zzz-nothing']))
        ->assertOk()
        ->assertSee('No pictures match');
});
