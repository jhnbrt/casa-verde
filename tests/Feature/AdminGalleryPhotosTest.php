<?php

use App\Models\HomeContent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->create(['is_admin' => true]);
    $this->uploaded = [];
});

afterEach(function () {
    foreach ($this->uploaded as $path) {
        @unlink(public_path($path));
    }
});

test('only admins can open the gallery photos page', function () {
    $this->get(route('admin.gallery.index'))->assertRedirect();
});

test('the gallery photos page lists the overview tiles and every category', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.gallery.index'))
        ->assertOk()
        ->assertSee('Gallery photos')
        ->assertSee('Overview tiles')
        ->assertSee('Villas')
        ->assertSee('Sunsets');
});

test('a photo can be replaced and its caption changed', function () {
    $this->actingAs($this->admin)->get(route('admin.gallery.index'));

    $photo = HomeContent::where('section', 'gallery_villas')->orderBy('sort_order')->firstOrFail();

    $this->actingAs($this->admin)
        ->put(route('admin.gallery.update', $photo), [
            'title' => 'New caption',
            'image' => UploadedFile::fake()->image('new-villa.jpg', 800, 600),
        ])
        ->assertRedirect();

    $photo->refresh();
    $this->uploaded[] = $photo->image;

    expect($photo->title)->toBe('New caption')
        ->and($photo->image)->toStartWith('images/')
        ->and($photo->image)->toContain('new-villa')
        ->and(file_exists(public_path($photo->image)))->toBeTrue();

    $this->get('/gallery')->assertOk()->assertSee($photo->image, false);
});

test('photos can be moved and removed', function () {
    $this->actingAs($this->admin)->get(route('admin.gallery.index'));

    $rows = HomeContent::where('section', 'gallery_dining')->orderBy('sort_order')->get();
    [$first, $second] = [$rows[0], $rows[1]];

    $this->actingAs($this->admin)->post(route('admin.gallery.move', $first), ['direction' => 'down'])->assertRedirect();

    expect($first->fresh()->sort_order)->toBeGreaterThan($second->fresh()->sort_order);

    $this->actingAs($this->admin)->delete(route('admin.gallery.destroy', $second))->assertRedirect();

    expect(HomeContent::find($second->id))->toBeNull();
});

test('entries that are not gallery photos cannot be changed here', function () {
    $other = HomeContent::create(['section' => 'footer_cta', 'title' => 'Banner', 'sort_order' => 1, 'active' => true]);

    $this->actingAs($this->admin)
        ->put(route('admin.gallery.update', $other), ['title' => 'Hacked'])
        ->assertNotFound();

    expect($other->fresh()->title)->toBe('Banner');
});

test('the label icon of an Experiences photo and the booking banner can be changed', function () {
    $this->actingAs($this->admin)->get(route('admin.gallery.index'));

    $photo = HomeContent::where('section', 'gallery_experiences')->orderBy('sort_order')->firstOrFail();
    $banner = HomeContent::where('section', 'gallery_cta_icon')->firstOrFail();

    $this->actingAs($this->admin)->put(route('admin.gallery.update', $photo), [
        'icon_image' => UploadedFile::fake()->image('cave-icon.png', 64, 64),
    ])->assertRedirect();

    $this->actingAs($this->admin)->put(route('admin.gallery.update', $banner), [
        'title' => 'Come and stay with us',
        'button_text' => 'Reserve',
        'icon_image' => UploadedFile::fake()->image('banner-icon.png', 64, 64),
    ])->assertRedirect();

    $photo->refresh();
    $banner->refresh();
    $this->uploaded[] = $photo->icon;
    $this->uploaded[] = $banner->icon;

    expect($photo->icon)->toStartWith('images/')->and($photo->icon)->toContain('cave-icon')
        ->and($banner->title)->toBe('Come and stay with us')
        ->and($banner->button_text)->toBe('Reserve')
        ->and($banner->icon)->toContain('banner-icon');

    $this->get('/gallery')->assertOk()->assertSee('Come and stay with us');
});

test('an unsafe SVG icon is refused', function () {
    $this->actingAs($this->admin)->get(route('admin.gallery.index'));

    $banner = HomeContent::where('section', 'gallery_cta_icon')->firstOrFail();
    $before = $banner->icon;

    $this->actingAs($this->admin)->put(route('admin.gallery.update', $banner), [
        'icon_image' => UploadedFile::fake()->createWithContent('bad.svg', '<svg xmlns="http://www.w3.org/2000/svg"><script>alert(1)</script></svg>'),
    ])->assertSessionHasErrors();

    expect($banner->fresh()->icon)->toBe($before);
});
