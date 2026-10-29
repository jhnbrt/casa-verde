<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

/** Goes to the resort's inbox for every new booking request. */
class BookingRequested extends Mailable
{
    public function __construct(public Booking $booking) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: [new Address($this->booking->email, $this->booking->name)],
            subject: "[Casa Verde] New booking request {$this->booking->reference} – {$this->booking->villa_title}",
        );
    }

    public function content(): Content
    {
        return new Content(view: 'mail.booking-requested');
    }
}
