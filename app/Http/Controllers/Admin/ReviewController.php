<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ReviewController extends Controller
{
    /**
     * Every review guests wrote on the website, newest first.
     */
    public function index(): View
    {
        return view('admin.reviews.index', [
            'reviews' => Review::query()->latest()->latest('id')->simplePaginate(15),
            'total' => Review::count(),
            'waiting' => Review::where('is_published', false)->count(),
            'needsApproval' => (bool) config('reviews.require_approval'),
        ]);
    }

    /**
     * Show a review on the site, or hide it again.
     */
    public function update(Review $review): RedirectResponse
    {
        $review->update(['is_published' => ! $review->is_published]);

        return back()->with('status', $review->is_published
            ? 'The review is now shown on the Reviews page.'
            : 'The review is hidden from the Reviews page.');
    }

    public function destroy(Review $review): RedirectResponse
    {
        $review->delete();

        return back()->with('status', 'The review was deleted.');
    }
}
