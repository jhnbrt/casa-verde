<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

/** "We got your request" email to the guest. */
class BookingReceived extends Mailable
{
    public function __construct(public Booking $booking) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: "We received your booking request {$this->booking->reference} – Casa Verde Cliff Resort & Spa");
    }

    public function content(): Content
    {
        return new Content(view: 'mail.booking-received');
    }
}
