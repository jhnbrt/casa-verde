<?php

use App\Models\HomeContent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;

uses(RefreshDatabase::class);

const ISLAND = 'M8 59h64M12 64h55';            // the drawn palm island
const LEAF = 'M5 19c0-9 5-14 14-14';           // the built-in leaf icon
const STONES = 'ellipse cx="12" cy="18"';      // the built-in stones icon

beforeEach(function () {
    HomeContent::where('section', 'offers')->delete();

    $this->offer = HomeContent::create([
        'section' => 'offers', 'title' => 'Stay 3, Pay 2', 'icon' => '🏝️',
        'subtitle' => "1 NIGHT FREE\nwhen you stay 2 nights or more",
        'description' => 'The more nights you stay, the more you save.', 'sort_order' => 1, 'active' => true,
    ]);
});

test('the starting emoji shows the original drawing', function () {
    $this->get('/special-offers')->assertOk()->assertSee(ISLAND, false);
});

test('an empty icon keeps the original drawing for the first cards', function () {
    $this->offer->update(['icon' => null]);

    $this->get('/special-offers')->assertOk()->assertSee(ISLAND, false);
});

test('an icon name typed in the admin replaces the big icon', function () {
    $this->offer->update(['icon' => 'leaf']);

    $this->get('/special-offers')->assertOk()->assertSee(LEAF, false)->assertDontSee(ISLAND, false);
});

test('the small icon beside the highlight line can be changed on its own', function () {
    $this->offer->update(['perk_icon' => 'stones']);

    $this->get('/special-offers')->assertOk()->assertSee(STONES, false)->assertSee(ISLAND, false);
});

test('an emoji is shown as typed', function () {
    $this->offer->update(['icon' => '🌺', 'perk_icon' => '⭐']);

    $this->get('/special-offers')->assertOk()->assertSee('🌺')->assertSee('⭐')->assertDontSee(ISLAND, false);
});

test('both icons can be typed or uploaded in the admin', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $fields = ['section' => 'offers', 'title' => 'Stay 3, Pay 2', 'sort_order' => 1, 'active' => 1];

    $this->actingAs($admin)->get(route('admin.content.edit', $this->offer))
        ->assertOk()
        ->assertSee('Card icon (big, beside the title)')
        ->assertSee('Small icon (beside the highlight line)');

    $this->actingAs($admin)->put(route('admin.content.update', $this->offer), $fields + ['icon' => 'leaf', 'perk_icon' => 'stones'])->assertRedirect();
    expect($this->offer->fresh()->icon)->toBe('leaf')->and($this->offer->fresh()->perk_icon)->toBe('stones');

    $this->actingAs($admin)->put(route('admin.content.update', $this->offer), $fields + [
        'icon' => 'leaf',
        'perk_icon_image' => UploadedFile::fake()->image('small.png', 60, 60),
    ])->assertRedirect();

    $perk = $this->offer->fresh()->perk_icon;
    @unlink(public_path($perk));
    expect($perk)->toStartWith('images/');

    // clearing the box goes back to the original drawing
    $this->actingAs($admin)->put(route('admin.content.update', $this->offer), $fields + ['icon' => '', 'perk_icon' => ''])->assertRedirect();
    expect($this->offer->fresh()->icon)->toBeNull()->and($this->offer->fresh()->perk_icon)->toBeNull();
});

test('other sections keep their icon untouched when the second icon is not in the form', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $row = HomeContent::create(['section' => 'villas', 'title' => 'X', 'icon' => 'villa', 'perk_icon' => 'keep', 'sort_order' => 1, 'active' => true]);

    $this->actingAs($admin)->put(route('admin.content.update', $row), ['section' => 'villas', 'title' => 'X', 'icon' => 'villa'])->assertRedirect();

    expect($row->fresh()->perk_icon)->toBe('keep');
});
