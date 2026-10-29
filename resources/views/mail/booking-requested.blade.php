<div style="font-family:Arial,sans-serif;color:#12241f;line-height:1.55;max-width:560px">
    <h2 style="margin:0 0 4px">New booking request</h2>
    <p style="margin:0 0 16px;color:#52625c">Reference <strong>{{ $booking->reference }}</strong> · waiting for your confirmation</p>
    <table cellpadding="6" style="border-collapse:collapse;width:100%;font-size:15px">
        <tr><td><strong>Villa</strong></td><td>{{ $booking->villa_title }}</td></tr>
        <tr><td><strong>Check-in</strong></td><td>{{ $booking->check_in->format('D, M j, Y') }}</td></tr>
        <tr><td><strong>Check-out</strong></td><td>{{ $booking->check_out->format('D, M j, Y') }} ({{ $booking->nights }} {{ \Illuminate\Support\Str::plural('night', $booking->nights) }})</td></tr>
        <tr><td><strong>Guests</strong></td><td>{{ $booking->adults }} adult(s), {{ $booking->children }} child(ren)</td></tr>
        <tr><td><strong>Total</strong></td><td>₱{{ number_format($booking->total, 2) }}</td></tr>
        <tr><td><strong>Name</strong></td><td>{{ $booking->name }}</td></tr>
        <tr><td><strong>Email</strong></td><td>{{ $booking->email }}</td></tr>
        <tr><td><strong>Phone</strong></td><td>{{ $booking->phone }}</td></tr>
        <tr><td><strong>Country</strong></td><td>{{ $booking->country }}</td></tr>
        @if($booking->special_requests)
            <tr><td valign="top"><strong>Requests</strong></td><td style="white-space:pre-line">{{ $booking->special_requests }}</td></tr>
        @endif
    </table>
    <p style="margin-top:18px"><a href="{{ route('admin.bookings.index') }}">Open in the admin</a></p>
</div>
