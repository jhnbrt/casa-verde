<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\BookingStatusChanged;
use App\Models\Booking;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BookingController extends Controller
{
    /** Every booking request from the website, newest first, filtered by status and/or a search. */
    public function index(Request $request): View
    {
        $status = $this->status($request);
        $search = trim((string) $request->query('q', ''));

        return view('admin.bookings.index', [
            'bookings' => $this->filtered($status, $search)
                ->latest()->latest('id')->simplePaginate(15)->withQueryString(),
            'status' => $status,
            'search' => $search,
            'counts' => collect(Booking::STATUSES)->mapWithKeys(fn ($s) => [$s => Booking::where('status', $s)->count()]),
        ]);
    }

    /** The list as a spreadsheet (CSV), honouring the same filters. */
    public function export(Request $request): StreamedResponse
    {
        $query = $this->filtered($this->status($request), trim((string) $request->query('q', '')))
            ->latest()->latest('id');

        // A cell starting with = + - @ would run as a formula in Excel; a leading quote keeps it text.
        $safe = fn ($v) => is_string($v) && preg_match('/^[=+\-@\t\r]/', $v) ? "'".$v : $v;

        return response()->streamDownload(function () use ($query, $safe) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // lets Excel read accents correctly
            fputcsv($out, ['Reference', 'Status', 'Villa', 'Check-in', 'Check-out', 'Nights', 'Adults', 'Children', 'Total (PHP)', 'Name', 'Email', 'Phone', 'Country', 'Special requests', 'Received']);

            $query->chunk(200, function ($rows) use ($out, $safe) {
                foreach ($rows as $b) {
                    fputcsv($out, array_map($safe, [
                        $b->reference, $b->status, $b->villa_title,
                        $b->check_in->toDateString(), $b->check_out->toDateString(), $b->nights,
                        $b->adults, $b->children, number_format((float) $b->total, 2, '.', ''),
                        $b->name, $b->email, $b->phone, $b->country,
                        str_replace(["\r\n", "\r", "\n"], ' ', (string) $b->special_requests),
                        $b->created_at->toDateTimeString(),
                    ]));
                }
            });

            fclose($out);
        }, 'casa-verde-bookings-'.now()->format('Y-m-d').'.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /** Mark a request confirmed or cancelled; the guest is emailed when it changes to one of those. */
    public function update(Request $request, Booking $booking): RedirectResponse
    {
        $data = $request->validate(['status' => ['required', 'in:'.implode(',', Booking::STATUSES)]]);

        $changed = $booking->status !== $data['status'];
        $booking->update(['status' => $data['status']]);

        $message = "Booking {$booking->reference} marked as {$data['status']}.";

        if ($changed && in_array($data['status'], ['confirmed', 'cancelled'], true)) {
            try {
                Mail::to($booking->email)->send(new BookingStatusChanged($booking));
                $message .= " {$booking->name} was emailed.";
            } catch (\Throwable $e) {
                report($e);
                $message .= ' The email to the guest could not be sent, please write to them yourself.';
            }
        }

        return back()->with('status', $message);
    }

    public function destroy(Booking $booking): RedirectResponse
    {
        $booking->delete();

        return back()->with('status', 'The booking request was deleted.');
    }

    protected function status(Request $request): ?string
    {
        return in_array($request->query('status'), Booking::STATUSES, true) ? $request->query('status') : null;
    }

    protected function filtered(?string $status, string $search): Builder
    {
        return Booking::query()
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($search !== '', function ($q) use ($search) {
                $like = '%'.addcslashes($search, '%_\\').'%';
                $q->where(fn ($w) => $w->where('name', 'like', $like)
                    ->orWhere('email', 'like', $like)
                    ->orWhere('reference', 'like', $like)
                    ->orWhere('villa_title', 'like', $like));
            });
    }
}
