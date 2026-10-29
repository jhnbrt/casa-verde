<?php

namespace App\Http\Controllers;

use App\Mail\BookingReceived;
use App\Mail\BookingRequested;
use App\Models\Booking;
use App\Models\HomeContent;
use App\Support\BookingRates;
use App\Support\BookingSettings;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BookingController extends Controller
{
    /** The booking page: every villa with its photos, rate, terms and request form. */
    public function index(?string $slug = null): View
    {
        $settings = BookingSettings::all();
        $villas = $settings['open'] ? BookingRates::villas() : [];

        abort_if($settings['open'] && $slug !== null && ! isset($villas[$slug]), 404);

        $contents = HomeContent::where('active', true)->orderBy('sort_order')->get()->groupBy('section');

        return view('book', [
            'contents' => $contents,
            'site' => $contents->get('site', collect())->first(),
            'page' => $contents->get('booking_page', collect())->first(),
            'villas' => $villas,
            'open' => $slug,
            'bookingOpen' => $settings['open'],
            'closedMessage' => $settings['closed_message'],
            'L' => BookingSettings::labels($contents),
            'maxGuests' => $settings['max_guests'],
            'maxNights' => $settings['max_nights'],
        ]);
    }

    /** Validates the request, works out the price on the server and saves it as a pending booking. */
    public function store(Request $request, string $slug): RedirectResponse
    {
        $settings = BookingSettings::all();

        if (! $settings['open']) {
            return redirect()->route('booking.index');
        }

        $villas = BookingRates::villas();
        abort_if(! isset($villas[$slug]), 404);
        $villa = $villas[$slug];

        $maxGuests = $settings['max_guests'];
        $maxNights = $settings['max_nights'];

        $data = $request->validate([
            'check_in' => ['required', 'date', 'after_or_equal:today'],
            'check_out' => ['required', 'date', 'after:check_in'],
            'adults' => ['required', 'integer', 'min:1', 'max:'.$maxGuests],
            'children' => ['required', 'integer', 'min:0', 'max:'.$maxGuests],
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['required', 'string', 'max:40', 'regex:/^[0-9+()\-.\s]{6,}$/'],
            'country' => ['required', 'string', 'max:80'],
            'special_requests' => ['nullable', 'string', 'max:1000'],
            'terms' => ['accepted'],
            'website' => ['nullable', 'max:0'], // honeypot: people leave it empty, bots fill it
        ], [
            'check_in.after_or_equal' => 'Check-in cannot be in the past.',
            'check_out.after' => 'Check-out must be after check-in.',
            'terms.accepted' => 'Please agree to the Terms & Conditions to continue.',
            'phone.regex' => 'Please enter a valid phone number.',
            'website.max' => 'Something went wrong. Please try again.',
        ]);

        $checkIn = Carbon::parse($data['check_in'])->startOfDay();
        $checkOut = Carbon::parse($data['check_out'])->startOfDay();
        $quote = BookingRates::quote($villa, $checkIn, $checkOut);

        if ($quote['nights'] > $maxNights) {
            return back()->withInput()->withErrors(
                ['check_out' => "Online requests are limited to {$maxNights} nights. Please contact us for longer stays."]
            )->with('open_villa', $slug);
        }

        $booking = Booking::create([
            'reference' => $this->newReference(),
            'villa_slug' => $slug,
            'villa_title' => $villa['title'],
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'nights' => $quote['nights'],
            'adults' => $data['adults'],
            'children' => $data['children'],
            'name' => trim($data['name']),
            'email' => $data['email'],
            'phone' => trim($data['phone']),
            'country' => trim($data['country']),
            'special_requests' => filled($data['special_requests'] ?? null) ? trim($data['special_requests']) : null,
            'total' => $quote['total'],
            'status' => 'pending',
            'terms_accepted_at' => now(),
        ]);

        // The request is saved first; an email problem must never lose a booking.
        try {
            Mail::to($settings['notify'])->send(new BookingRequested($booking));
            Mail::to($booking->email)->send(new BookingReceived($booking));
        } catch (\Throwable $e) {
            report($e);
        }

        return redirect()->route('booking.confirmed')->with('booking_id', $booking->id);
    }

    /** "Request received" page. Only shown right after a request, never for a guessed address. */
    public function confirmed(Request $request): View|RedirectResponse
    {
        $id = $request->session()->get('booking_id') ?? $request->session()->get('last_booking_id');

        $booking = $id ? Booking::find($id) : null;

        if (! $booking) {
            return redirect()->route('booking.index');
        }

        $request->session()->put('last_booking_id', $booking->id); // survives a page refresh

        $contents = HomeContent::where('active', true)->orderBy('sort_order')->get()->groupBy('section');

        return view('book-confirmed', [
            'contents' => $contents,
            'site' => $contents->get('site', collect())->first(),
            'booking' => $booking,
            'copy' => BookingSettings::confirmation($contents, Str::before($booking->name, ' '), $booking->email),
        ]);
    }

    protected function newReference(): string
    {
        do {
            $reference = 'CV-'.strtoupper(Str::random(6));
        } while (Booking::where('reference', $reference)->exists());

        return $reference;
    }
}
