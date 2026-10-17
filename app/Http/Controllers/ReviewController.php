<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Models\HomeContent;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(): View
    {
        $contents = HomeContent::where('active', true)
            ->orderBy('sort_order')
            ->get()
            ->groupBy('section');

        $site = $contents->get('site', collect())->first();

        // The newest guest reviews come first, so a rating just sent is the first one shown.
        $guestReviews = Review::published()
            ->latest()
            ->latest('id')
            ->limit(max(0, (int) config('reviews.guest_limit', 20)))
            ->get();

        $page = $this->buildPage($contents, config('reviews'), $guestReviews);
        $guestSummary = $this->guestSummary();

        return view('reviews', compact('contents', 'site', 'page', 'guestSummary'));
    }

    /**
     * Save a review sent through the form on the Reviews page.
     */
    public function store(StoreReviewRequest $request): RedirectResponse
    {
        $back = redirect()->to(route('reviews.index').'#rv-share');

        // Hidden trap field: people never see it, spam bots fill it in. Thank them and save nothing.
        if ($request->filled('website')) {
            return $back->with('review_status', 'Thank you for sharing your experience!');
        }

        $data = $request->validated();
        $published = ! config('reviews.require_approval');

        Review::create([
            'name' => $data['name'],
            'place' => $data['place'] ?? null,
            'rating' => $data['rating'],
            'comment' => $data['comment'],
            'is_published' => $published,
        ]);

        return $back
            ->with('review_status', $published
                ? 'Thank you! Your review is now on this page.'
                : 'Thank you! Your review will appear on this page once the resort has approved it.')
            ->with('review_published', $published);
    }

    /**
     * Average star rating and number of the guest reviews written on the website,
     * or null while there are none.
     *
     * @return array{average: float, count: int}|null
     */
    protected function guestSummary(): ?array
    {
        $count = Review::published()->count();

        if ($count === 0) {
            return null;
        }

        return [
            'average' => round((float) Review::published()->avg('rating'), 1),
            'count' => $count,
        ];
    }

    /**
     * Merge admin-managed "reviews_*" content over the mock-up defaults.
     *
     * @param  Collection<string, Collection<int, HomeContent>>  $contents
     * @param  array<string, mixed>  $defaults
     * @param  Collection<int, Review>  $guestReviews  published reviews written on the website
     * @return array<string, mixed>
     */
    protected function buildPage(Collection $contents, array $defaults, Collection $guestReviews): array
    {
        $header = $contents->get('reviews_header', collect())->first();
        $itemRows = $contents->get('reviews_items', collect());
        $statRows = $contents->get('reviews_stats', collect());
        $iconRows = $contents->get('reviews_icons', collect())->keyBy('sort_order');
        $image = fn (int $position): ?string => HomeContent::customIconImage($iconRows->get($position)?->icon);

        $guestPhotos = array_values((array) config('reviews.guest_photos', []));
        $guestItems = $guestReviews->map(fn (Review $review): array => [
            'name' => $review->name,
            'place' => (string) $review->place,
            'quote' => $review->comment,
            'rating' => $review->rating,
            'image' => $guestPhotos !== [] ? $guestPhotos[$review->id % count($guestPhotos)] : null,
            'avatar' => null,
        ])->values()->all();

        $curatedItems = $itemRows->isNotEmpty()
            ? $itemRows->map(fn (HomeContent $row): array => [
                'name' => (string) $row->title,
                'place' => (string) $row->subtitle,
                'quote' => (string) $row->description,
                'rating' => $this->rating($row->price),
                'image' => $row->image,
                'avatar' => $row->icon,
            ])->values()->all()
            : array_map(fn (array $item): array => $item + ['rating' => 5], $defaults['items']);

        return [
            'header' => [
                'eyebrow' => $header?->subtitle ?: $defaults['header']['eyebrow'],
                'title' => $header?->title ?: $defaults['header']['title'],
                'text' => $header?->description ?: $defaults['header']['text'],
                'button_text' => $header?->button_text ?: $defaults['header']['button_text'],
                'button_url' => $header?->button_url ?: $defaults['header']['button_url'],
                'button_icon' => HomeContent::customIconImage($header?->icon),
                'background' => $header && filled($header->image) ? $header->image : null,
                'autoplay' => $header && filled($header->price)
                    ? max(0, (float) $header->price)
                    : $defaults['header']['autoplay'],
            ],
            // Uploaded icon pictures; null keeps the built-in drawing.
            'icons' => [
                'star' => $image(1),
                'prev' => $image(2),
                'next' => $image(3),
            ],
            'items' => array_merge($guestItems, $curatedItems),
            'stats' => $statRows->isNotEmpty()
                ? $statRows->map(fn (HomeContent $row): array => [
                    'value' => (string) $row->title,
                    'label' => (string) $row->subtitle,
                ])->values()->all()
                : $defaults['stats'],
        ];
    }

    /**
     * Star rating from the admin "price" column: 1 to 5, five stars when left empty.
     */
    protected function rating(mixed $value): int
    {
        return filled($value) ? (int) max(0, min(5, round((float) $value))) : 5;
    }
}
