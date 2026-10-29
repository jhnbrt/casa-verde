<div style="font-family:Arial,sans-serif;color:#12241f;line-height:1.6;max-width:560px">
    <h2 style="margin:0 0 8px;color:#06211b">Thank you, {{ \Illuminate\Support\Str::before($booking->name, ' ') }}!</h2>
    <p>We received your booking request for the <strong>{{ $booking->villa_title }}</strong>. Our team will confirm availability by email shortly. <strong>Your stay is not confirmed until you hear from us.</strong></p>
    <table cellpadding="6" style="border-collapse:collapse;width:100%;font-size:15px;background:#f7f2ea">
        <tr><td><strong>Reference</strong></td><td>{{ $booking->reference }}</td></tr>
        <tr><td><strong>Check-in</strong></td><td>{{ $booking->check_in->format('D, M j, Y') }}</td></tr>
        <tr><td><strong>Check-out</strong></td><td>{{ $booking->check_out->format('D, M j, Y') }} ({{ $booking->nights }} {{ \Illuminate\Support\Str::plural('night', $booking->nights) }})</td></tr>
        <tr><td><strong>Guests</strong></td><td>{{ $booking->adults }} adult(s), {{ $booking->children }} child(ren)</td></tr>
        <tr><td><strong>Estimated total</strong></td><td>₱{{ number_format($booking->total, 2) }}</td></tr>
    </table>
    <p style="color:#52625c;font-size:14px">Rates are non-refundable. Please keep this reference if you need to contact us.</p>
    <p>Casa Verde Cliff Resort &amp; Spa</p>
</div>
