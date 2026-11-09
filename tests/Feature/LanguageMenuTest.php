<?php

use App\Models\HomeContent;
use App\Models\User;
use App\Support\Languages;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

beforeEach(function () {
    HomeContent::where('section', Languages::SECTION)->delete();
    Cache::flush();
});

test('every public page has the language menu with the offered languages', function () {
    $this->get('/')->assertOk()
        ->assertSee('data-cv-lang', false)
        ->assertSee('한국어')->assertSee('日本語')->assertSee('Deutsch')
        ->assertDontSee('Tiếng Việt'); // not offered until the admin ticks it

    $this->get('/villas')->assertOk()->assertSee('data-cv-lang', false);
});

test('a first-time visitor is given a language from the country the host reports', function () {
    $this->withHeader('CF-IPCountry', 'KR')->get('/')->assertOk()->assertSee('"auto":"ko"', false);
    $this->withHeader('CF-IPCountry', 'JP')->get('/')->assertSee('"auto":"ja"', false);
    $this->withHeader('CF-IPCountry', 'TW')->get('/')->assertSee('"auto":"zh-TW"', false);
    $this->withHeader('CF-IPCountry', 'PH')->get('/')->assertSee('"auto":"en"', false);
});

test('without a country the browser language decides', function () {
    $this->withHeader('Accept-Language', 'ko-KR,ko;q=0.9,en;q=0.8')->get('/')->assertSee('"auto":"ko"', false);
    $this->withHeader('Accept-Language', 'zh-Hant-HK,zh;q=0.9')->get('/')->assertSee('"auto":"zh-TW"', false);
    $this->withHeader('Accept-Language', 'zh-Hans-CN')->get('/')->assertSee('"auto":"zh-CN"', false);
    $this->withHeader('Accept-Language', 'fil-PH,fil;q=0.9')->get('/')->assertSee('"auto":"tl"', false);
    $this->withHeader('Accept-Language', 'en-US,en;q=0.9,de;q=0.5')->get('/')->assertSee('"auto":"en"', false);
    $this->withHeader('Accept-Language', 'sw-KE')->get('/')->assertSee('"auto":"en"', false);
});

test('the country wins over the browser, and a language that is not offered is skipped', function () {
    $this->withHeaders(['CF-IPCountry' => 'DE', 'Accept-Language' => 'ko'])->get('/')->assertSee('"auto":"de"', false);

    Languages::save(['ko'], true, true, false);   // German is no longer offered

    $this->withHeaders(['CF-IPCountry' => 'DE', 'Accept-Language' => 'ko'])->get('/')->assertSee('"auto":"ko"', false);
});

// One visitor per test: Laravel keeps cookies from one request to the next inside a test.
test('a visitor who already chose a language is not looked up again', function () {
    $this->withHeader('CF-IPCountry', 'KR')->withUnencryptedCookie('cv_lang', 'de')->get('/')->assertSee('"auto":null', false);
});

test('a visitor who was already detected is not looked up again', function () {
    $this->withHeader('CF-IPCountry', 'KR')->withUnencryptedCookie('cv_auto', 'ko')->get('/')->assertSee('"auto":null', false);
});

test('a visitor who picked Automatic is looked up again', function () {
    $this->withHeader('CF-IPCountry', 'KR')->withUnencryptedCookie('cv_lang', 'auto')->get('/')->assertSee('"auto":"ko"', false);
});

test('the country can be found from the IP address, once, when the host does not say', function () {
    Http::fake(['ipwho.is/*' => Http::response(['success' => true, 'country_code' => 'JP'])]);

    $this->withServerVariables(['REMOTE_ADDR' => '203.0.113.50'])->get('/')->assertSee('"auto":"ja"', false);
    $this->withServerVariables(['REMOTE_ADDR' => '203.0.113.50'])->get('/')->assertSee('"auto":"ja"', false);

    Http::assertSentCount(1); // the answer is kept
});

test('a failing lookup service never breaks the page', function () {
    Http::fake(['ipwho.is/*' => Http::response('down', 500)]);

    $this->withServerVariables(['REMOTE_ADDR' => '203.0.113.60'])->get('/')->assertOk()->assertSee('"auto":"en"', false);
});

test('no IP address is sent anywhere when the lookup is switched off', function () {
    Http::fake();
    Languages::save(['ko', 'ja'], true, true, false);

    $this->withServerVariables(['REMOTE_ADDR' => '203.0.113.70'])->get('/')->assertOk();

    Http::assertNothingSent();
});

test('the menu can be hidden and automatic choice can be turned off', function () {
    Languages::save(['ko'], false, true, false);
    $this->get('/')->assertOk()->assertDontSee('data-cv-lang', false)->assertDontSee('googtrans');

    Languages::save(['ko'], true, false, false);
    $this->withHeader('CF-IPCountry', 'KR')->get('/')->assertSee('"auto":null', false)->assertDontSee('Based on where you are');
});

test('search engines and link previews are left in English', function () {
    $this->withHeaders(['CF-IPCountry' => 'KR', 'User-Agent' => 'Mozilla/5.0 (compatible; Googlebot/2.1)'])
        ->get('/')->assertSee('"auto":"en"', false);
});

test('the admin Languages page is for admins and saves the choices', function () {
    $this->get(route('admin.languages.edit'))->assertRedirect();

    $admin = User::factory()->create(['is_admin' => true]);

    $this->actingAs($admin)->get(route('admin.languages.edit'))->assertOk()->assertSee('Languages offered');

    $this->actingAs($admin)->put(route('admin.languages.update'), [
        'languages' => ['ko', 'vi'], 'show' => '1', 'auto' => '1',
    ])->assertRedirect();

    $settings = Languages::settings();

    expect($settings['codes'])->toBe(['en', 'ko', 'vi'])   // English is always kept
        ->and($settings['show'])->toBeTrue()
        ->and($settings['auto'])->toBeTrue()
        ->and($settings['ip_lookup'])->toBeFalse();       // left unticked

    $this->get('/')->assertSee('Tiếng Việt')->assertDontSee('Deutsch');
});

test('the admin cannot save a language that does not exist', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $this->actingAs($admin)->put(route('admin.languages.update'), ['languages' => ['xx']])->assertSessionHasErrors('languages.*');
});
