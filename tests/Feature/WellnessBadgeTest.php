<?php

use App\Models\HomeContent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;

uses(RefreshDatabase::class);

beforeEach(function () {
    HomeContent::where('section', 'wellness_page_treatments')->delete();

    $this->card = HomeContent::create([
        'section' => 'wellness_page_treatments', 'title' => 'Hot Stone Massage',
        'description' => 'Warm volcanic stones.', 'image' => 'images/wellness/hot-stone.jpg', 'sort_order' => 1, 'active' => true,
    ]);
});

test('a treatment without an icon has no badge', function () {
    $this->get('/wellness')->assertOk()->assertSee('Hot Stone Massage')->assertDontSee('class="wellness-card-badge"', false);
});

test('a treatment shows the badge icon set in the admin', function () {
    $this->card->update(['icon' => 'stones']);

    $this->get('/wellness')->assertOk()->assertSee('class="wellness-card-badge"', false);
});

test('the badge icon can be typed or uploaded in the admin', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $fields = ['section' => 'wellness_page_treatments', 'title' => 'Hot Stone Massage', 'sort_order' => 1, 'active' => 1];

    $this->actingAs($admin)->get(route('admin.content.edit', $this->card))
        ->assertOk()->assertSee('Badge icon on the photo');

    $this->actingAs($admin)->put(route('admin.content.update', $this->card), $fields + ['icon' => 'leaf'])->assertRedirect();
    expect($this->card->fresh()->icon)->toBe('leaf');

    $this->actingAs($admin)->put(route('admin.content.update', $this->card), $fields + [
        'icon_image' => UploadedFile::fake()->image('badge.png', 80, 80),
    ])->assertRedirect();

    $icon = $this->card->fresh()->icon;
    @unlink(public_path($icon));
    expect($icon)->toStartWith('images/');
});


test('the migration gives the existing treatment photos an editable badge icon', function () {
    $this->card->update(['image' => 'images/wellness/hot-stone.jpg', 'icon' => null]);

    (require database_path('migrations/2026_11_02_000100_set_wellness_treatment_badge_icons.php'))->up();

    expect($this->card->fresh()->icon)->toBe('stones');

    // an icon the admin already chose is never replaced
    $this->card->update(['icon' => 'lotus']);
    (require database_path('migrations/2026_11_02_000100_set_wellness_treatment_badge_icons.php'))->up();

    expect($this->card->fresh()->icon)->toBe('lotus');
});
