<?php

use App\Mail\BookingReceived;
use App\Mail\BookingRequested;
use App\Mail\BookingStatusChanged;
use App\Models\Booking;
use App\Models\HomeContent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

beforeEach(function () {
    HomeContent::whereIn('section', ['villas', 'booking_rates', 'booking_page', 'booking_labels', 'booking_confirmation', 'booking_settings'])->delete();

    HomeContent::create(['section' => 'villas', 'title' => 'Standard Villa', 'price' => 9000, 'description' => 'A roomy villa.', 'sort_order' => 1, 'active' => true]);

    // Regular 9,000 a night, promo 5,000 for the next three weeks.
    HomeContent::create([
        'section' => 'booking_rates', 'title' => 'Standard Villa', 'subtitle' => '9000', 'price' => 5000,
        'button_text' => now()->addDays(1)->toDateString(), 'button_url' => now()->addDays(21)->toDateString(),
        'description' => "NON-REFUNDABLE RATES\n• All rooms are non-smoking.", 'sort_order' => 1, 'active' => true,
    ]);
});

function validBooking(array $overrides = []): array
{
    return array_merge([
        'check_in' => now()->addDays(6)->toDateString(),
        'check_out' => now()->addDays(8)->toDateString(),
        'adults' => 2,
        'children' => 0,
        'name' => 'Maria Santos',
        'email' => 'maria@example.com',
        'phone' => '+63 917 000 0000',
        'country' => 'Philippines',
        'special_requests' => 'Late arrival',
        'terms' => '1',
        '_villa' => 'standard-villa',
    ], $overrides);
}

test('the booking page lists each villa with its prices, terms and form', function () {
    $this->get('/book')
        ->assertOk()
        ->assertSee('Standard Villa')
        ->assertSee('₱5,000.00')
        ->assertSee('₱9,000.00')
        ->assertSee('Terms &amp; Conditions', false)
        ->assertSee('Confirm booking request');
});

test('a villa link opens that villa and an unknown villa is not found', function () {
    $this->get('/book/standard-villa')->assertOk()->assertSee('data-open="true"', false);
    $this->get('/book/no-such-villa')->assertNotFound();
});

test('a request is saved with the price worked out on the server and emailed', function () {
    Mail::fake();

    $this->post('/book/standard-villa', validBooking(['total' => 1]))
        ->assertRedirect(route('booking.confirmed'));

    $booking = Booking::firstOrFail();

    expect($booking->nights)->toBe(2)
        ->and((float) $booking->total)->toBe(10000.0)   // 2 nights at the 5,000 promo rate, whatever the browser sent
        ->and($booking->status)->toBe('pending')
        ->and($booking->reference)->toStartWith('CV-')
        ->and($booking->terms_accepted_at)->not->toBeNull();

    Mail::assertSent(BookingRequested::class);
    Mail::assertSent(BookingReceived::class, fn ($mail) => $mail->hasTo('maria@example.com'));

    $this->get('/book/received')->assertOk()->assertSee($booking->reference);
});

test('nights outside the promo are charged the regular rate', function () {
    Mail::fake();

    $this->post('/book/standard-villa', validBooking([
        'check_in' => now()->addDays(40)->toDateString(),
        'check_out' => now()->addDays(42)->toDateString(),
    ]))->assertRedirect(route('booking.confirmed'));

    expect((float) Booking::firstOrFail()->total)->toBe(18000.0);
});

test('a request is refused without agreeing to the terms, with bad dates or when a bot fills the hidden field', function () {
    Mail::fake();

    $this->post('/book/standard-villa', validBooking(['terms' => null]))->assertSessionHasErrors('terms');
    $this->post('/book/standard-villa', validBooking(['check_out' => now()->addDays(5)->toDateString()]))->assertSessionHasErrors('check_out');
    $this->post('/book/standard-villa', validBooking(['check_in' => now()->subDay()->toDateString()]))->assertSessionHasErrors('check_in');
    $this->post('/book/standard-villa', validBooking(['website' => 'http://spam.example']))->assertSessionHasErrors('website');

    expect(Booking::count())->toBe(0);
    Mail::assertNothingSent();
});

test('a failing mail server never loses the booking', function () {
    Mail::shouldReceive('to')->andThrow(new RuntimeException('SMTP down'));

    $this->post('/book/standard-villa', validBooking())->assertRedirect(route('booking.confirmed'));

    expect(Booking::count())->toBe(1);
});

test('the received page sends visitors without a booking back to the booking page', function () {
    $this->get('/book/received')->assertRedirect(route('booking.index'));
});


test('the page wording comes from the admin and falls back to the original text', function () {
    HomeContent::create(['section' => 'booking_labels', 'title' => 'Grab this villa', 'sort_order' => 4, 'active' => true]);
    HomeContent::create(['section' => 'booking_labels', 'title' => '', 'sort_order' => 12, 'active' => true]);

    $this->get('/book')
        ->assertOk()
        ->assertSee('Grab this villa')
        ->assertSee('Confirm booking request'); // the empty entry uses the original wording
});

test('the settings limit guests and the nights of a stay', function () {
    Mail::fake();
    HomeContent::create(['section' => 'booking_settings', 'title' => 'front@resort.test', 'subtitle' => '3', 'price' => 4, 'active' => true]);

    $this->post('/book/standard-villa', validBooking(['adults' => 5]))->assertSessionHasErrors('adults');
    $this->post('/book/standard-villa', validBooking([
        'check_in' => now()->addDays(6)->toDateString(),
        'check_out' => now()->addDays(10)->toDateString(),
    ]))->assertSessionHasErrors('check_out');

    $this->post('/book/standard-villa', validBooking(['adults' => 4]))->assertRedirect(route('booking.confirmed'));

    Mail::assertSent(BookingRequested::class, fn ($mail) => $mail->hasTo('front@resort.test'));
});

test('switching the settings entry off pauses online booking', function () {
    HomeContent::create(['section' => 'booking_settings', 'description' => 'Back on Monday.', 'active' => false]);

    $this->get('/book')->assertOk()->assertSee('Back on Monday.')->assertDontSee('Confirm booking request');
    $this->post('/book/standard-villa', validBooking())->assertRedirect(route('booking.index'));

    expect(Booking::count())->toBe(0);
});

test('a villa whose rates entry is switched off cannot be booked online', function () {
    HomeContent::where('section', 'booking_rates')->update(['active' => false]);

    $this->get('/book')->assertOk()->assertDontSee('Reserve this villa');
    $this->get('/book/standard-villa')->assertNotFound();
});

test('the received message is editable and fills in the guest', function () {
    Mail::fake();
    HomeContent::create(['section' => 'booking_confirmation', 'title' => 'Salamat, :name!', 'description' => 'Check :email soon.', 'active' => true]);

    $this->post('/book/standard-villa', validBooking());

    $this->get('/book/received')->assertSee('Salamat, Maria!')->assertSee('Check maria@example.com soon.');
});

test('admins manage bookings: search, confirm with an email to the guest, and download a CSV', function () {
    Mail::fake();
    $admin = User::factory()->create(['is_admin' => true]);
    $this->post('/book/standard-villa', validBooking());
    $this->post('/book/standard-villa', validBooking(['name' => 'Juan Dela Cruz', 'email' => 'juan@example.com']));
    $booking = Booking::where('email', 'juan@example.com')->firstOrFail();

    $this->get(route('admin.bookings.index'))->assertRedirect(); // guests are sent to log in

    $this->actingAs($admin)->get(route('admin.bookings.index', ['q' => 'Juan']))
        ->assertOk()->assertSee('Juan Dela Cruz')->assertDontSee('Maria Santos');

    $this->actingAs($admin)->put(route('admin.bookings.update', $booking), ['status' => 'confirmed'])->assertRedirect();
    expect($booking->fresh()->status)->toBe('confirmed');
    Mail::assertSent(BookingStatusChanged::class, fn ($mail) => $mail->hasTo('juan@example.com'));

    $csv = $this->actingAs($admin)->get(route('admin.bookings.export'));
    $csv->assertOk()->assertDownload();
    expect($csv->streamedContent())->toContain('juan@example.com')->toContain('Reference');
});
