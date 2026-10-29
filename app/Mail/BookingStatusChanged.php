<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

/** Sent to the guest when the team confirms or cancels their request in the admin. */
class BookingStatusChanged extends Mailable
{
    public function __construct(public Booking $booking) {}

    public function envelope(): Envelope
    {
        $subject = $this->booking->status === 'confirmed'
            ? "Your stay is confirmed – {$this->booking->reference}"
            : "About your booking request {$this->booking->reference}";

        return new Envelope(subject: $subject.' – Casa Verde Cliff Resort & Spa');
    }

    public function content(): Content
    {
        return new Content(view: 'mail.booking-status');
    }
}
