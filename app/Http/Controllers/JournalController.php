<?php

namespace App\Http\Controllers;

use App\Models\HomeContent;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\View\View;

/**
 * The Journal: a list of articles (/blog) and one page per article (/blog/{slug}).
 * Everything is edited in the admin: Journal > Page heading / Articles.
 */
class JournalController extends Controller
{
    public function index(): View
    {
        [$contents, $site] = $this->shared();
        $header = $contents->get('journal_header', collect())->first();

        return view('journal.index', [
            'contents' => $contents,
            'site' => $site,
            'title' => $header?->title ?: 'Resort Journal',
            'text' => $header?->description ?: 'Stories, local guides, and news from the cliffs of Casa Verde.',
            'articles' => $this->articles($contents),
            'readLabel' => $header?->button_text ?: 'Read Article',
        ]);
    }

    public function show(string $slug): View
    {
        [$contents, $site] = $this->shared();
        $articles = $this->articles($contents);
        $article = $articles->firstWhere('slug', $slug);

        abort_if($article === null, 404);

        $header = $contents->get('journal_header', collect())->first();

        return view('journal.show', [
            'moreHeading' => $header?->subtitle ?: 'More Stories',
            'readLabel' => $header?->button_text ?: 'Read Article',
            'contents' => $contents,
            'site' => $site,
            'article' => $article,
            'more' => $articles->where('slug', '!=', $slug)->values(),
        ]);
    }

    /** @return array{0: Collection<string, Collection<int, HomeContent>>, 1: ?HomeContent} */
    protected function shared(): array
    {
        $contents = HomeContent::where('active', true)->orderBy('sort_order')->get()->groupBy('section');

        return [$contents, $contents->get('site', collect())->first()];
    }

    /** @return Collection<int, array<string, mixed>> */
    protected function articles(Collection $contents): Collection
    {
        $used = [];
        $photos = $contents->get('journal_photos', collect())
            ->filter(fn (HomeContent $row): bool => filled($row->image) && file_exists(public_path($row->image)))
            ->values();

        return $contents->get('journal_articles', collect())
            ->filter(fn (HomeContent $row): bool => trim((string) $row->title) !== '')
            ->map(function (HomeContent $row) use (&$used, $photos): array {
                $slug = Str::slug((string) $row->title) ?: 'article-'.$row->id;

                if (isset($used[$slug])) {
                    $slug .= '-'.$row->id;
                }

                $used[$slug] = true;
                $body = trim((string) $row->description);
                $short = trim((string) $row->button_text);

                return [
                    'slug' => $slug,
                    'title' => trim((string) $row->title),
                    'date' => trim((string) $row->subtitle),
                    'image' => filled($row->image) && file_exists(public_path($row->image)) ? $row->image : null,
                    'excerpt' => $short !== '' ? $short : Str::limit(trim(preg_replace('/\s+/', ' ', preg_replace(['/^#{2,3}\s*/m', '/^\s*[-*]\s+/m', '/\*\*/'], '', $body))), 150),
                    'paragraphs' => $body === '' ? [] : array_values(array_filter(preg_split('/\R{2,}/', $body), fn ($p) => trim($p) !== '')),
                    // Extra photos: linked to the article by its id (chosen in the admin) or by its title.
                    'gallery' => $photos
                        ->filter(function (HomeContent $photo) use ($row): bool {
                            $link = trim((string) $photo->subtitle);

                            return $link !== '' && ($link === (string) $row->id || strcasecmp($link, trim((string) $row->title)) === 0);
                        })
                        ->map(fn (HomeContent $photo): array => ['image' => $photo->image, 'caption' => trim((string) $photo->title)])
                        ->values()
                        ->all(),
                ];
            })
            ->values();
    }
}
