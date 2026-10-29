<div style="font-family:Arial,sans-serif;color:#12241f;line-height:1.6;max-width:560px">
    @if ($booking->status === 'confirmed')
        <h2 style="margin:0 0 8px;color:#06211b">Your stay is confirmed, {{ \Illuminate\Support\Str::before($booking->name, ' ') }}!</h2>
        <p>We look forward to welcoming you to the <strong>{{ $booking->villa_title }}</strong>.</p>
    @else
        <h2 style="margin:0 0 8px;color:#06211b">About your booking request</h2>
        <p>Dear {{ \Illuminate\Support\Str::before($booking->name, ' ') }}, we are sorry: we could not confirm your request for the <strong>{{ $booking->villa_title }}</strong> and it has been cancelled. Please reply to this email and we will gladly look for other dates or a different villa.</p>
    @endif
    <table cellpadding="6" style="border-collapse:collapse;width:100%;font-size:15px;background:#f7f2ea">
        <tr><td><strong>Reference</strong></td><td>{{ $booking->reference }}</td></tr>
        <tr><td><strong>Check-in</strong></td><td>{{ $booking->check_in->format('D, M j, Y') }}</td></tr>
        <tr><td><strong>Check-out</strong></td><td>{{ $booking->check_out->format('D, M j, Y') }} ({{ $booking->nights }} {{ \Illuminate\Support\Str::plural('night', $booking->nights) }})</td></tr>
        <tr><td><strong>Guests</strong></td><td>{{ $booking->adults }} adult(s), {{ $booking->children }} child(ren)</td></tr>
        @if ($booking->status === 'confirmed')
            <tr><td><strong>Total</strong></td><td>₱{{ number_format($booking->total, 2) }}</td></tr>
        @endif
    </table>
    <p>Casa Verde Cliff Resort &amp; Spa</p>
</div>
